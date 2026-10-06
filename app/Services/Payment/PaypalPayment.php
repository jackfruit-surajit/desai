<?php
namespace App\Services\Payment;
use App\Services\Payment\PaymentGatewayInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Validator;
use URL;
use Session;
use Redirect;
use Input;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;


use App\Jobs\SendCustomMail;
use App\Models\Booking;
use App\Models\Payment as PaymentModel;
use DB;

class PaypalPayment implements PaymentGatewayInterface
{
    private $exponent;
    private $currency;
    private $razorpay_key_id;
    private $razorpay_key_secret;
    private $currency_sign;

    private $_api_context;

    function __construct(){

        $currency_exponent = DB::table('settings')->where('slug','=','currency_exponent')->first();
        $this->exponent = $currency_exponent->value;

        $currency_sign = DB::table('settings')->where('slug','=','currency_sign')->first();
        $this->currency_sign = $currency_sign->value;

        $currency_code = DB::table('settings')->where('slug','=','currency_code')->first();
        $this->currency = $currency_code->value;

        $paypal_key = DB::table('payment_settings')->where('slug','=','paypal_key_id')->first();
        $this->paypal_key_id = $paypal_key->value;

        $paypal_secret = DB::table('payment_settings')->where('slug','=','paypal_key_secret')->first();
        $this->paypal_key_secret = $paypal_secret->value;

        $paypal_test_mode = DB::table('payment_settings')->where('slug','=','paypal_test_mode')->first();

        if($paypal_test_mode->value){
            $mode = 'sandbox';
        }else{
            $mode = 'live';
        }

        $paypal_configuration = [ 
            'client_id' => $this->paypal_key_id,
            'secret' => $this->paypal_key_secret,
            'settings' => array(
                'mode' => $mode,
                'http.ConnectionTimeOut' => 1000,
                'log.LogEnabled' => true,
                'log.FileName' => storage_path() . '/logs/paypal.log',
                'log.LogLevel' => 'FINE'
            ),
        ];

        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
    }

    public function create_payment($param){
        
        $data = $param['data'];
        $payment_method = $param['payment_method'];

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

    	$item_1 = new Item();

        $item_1->setName($data->model)
            ->setCurrency('USD') //$this->currency
            ->setQuantity(1)
            ->setPrice($data->total_amount);

        $item_list = new ItemList();
        $item_list->setItems(array($item_1));

        $details = new Details();
        $details->setShipping(0)
            ->setTax(0)
            ->setSubtotal($data->total_amount);

        $amount = new Amount();

        $amount->setCurrency('USD') //$this->currency
            ->setTotal($data->total_amount)
            ->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($item_list)
            ->setDescription('Enter Your transaction description')
            ->setInvoiceNumber(uniqid());

        $response_url = route('response').'?payment_method='.$payment_method;
        $cancel_url = route('cancel').'?payment_method='.$payment_method;

        $redirect_urls = new RedirectUrls();
        $redirect_urls->setReturnUrl($response_url)
                    ->setCancelUrl($cancel_url);

        $payment = new Payment();
        $payment->setIntent('Sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirect_urls)
            ->setTransactions(array($transaction));

        try {
            $payment->create($this->_api_context);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            if (\Config::get('app.debug')) {  

                DB::table('bookings')->where('id', $data->id)->delete();

                return redirect()->route('dashboard')->with('error_msg','Connection timeout!');
                           
            } else {

                DB::table('bookings')->where('id', $data->id)->delete();

                return redirect()->route('dashboard')->with('error_msg','Some error occur, sorry for inconvenient!');
                               
            }
        }

        foreach($payment->getLinks() as $link) {
            if($link->getRel() == 'approval_url') {
                $redirect_url = $link->getHref();
                break;
            }
        }

        session(['paypal_payment_id'=> $payment->getId(), 'booking_id'=> $data->id]);
        
        if(isset($redirect_url)) {            
            return Redirect::away($redirect_url);
        }

        DB::table('bookings')->where('id', $data->id)->delete();

        return redirect()->route('dashboard')->with('error_msg','Unknown error occurred!');

    }

    public function response_payment(Request $request){
        $data = $request->all();

        // print_r($data);
        // print_r("-----------------------");

        $paypal_payment_id = session('paypal_payment_id');
        $booking_id = session('booking_id');

        if (empty($request->input('paymentId')) || empty($request->input('PayerID')) || empty($request->input('token'))) {

            DB::table('bookings')->where('id', $booking_id)->delete();
            return redirect()->route('dashboard')->with('error_msg','Payment failed!');

        }

        $payment = Payment::get($request->input('paymentId'), $this->_api_context);        
        $execution = new PaymentExecution();
        $execution->setPayerId($request->input('PayerID'));        
        $result = $payment->execute($execution, $this->_api_context);

        // echo"<pre>";print_r($result);exit;

        $model = Booking::findOrFail($booking_id);

        if ($result->getState() == 'approved') {   

            DB::table('bookings')->where('id', $model->id)->update(['booking_status' => '1']);

            $totalPayments = DB::table('payments')->count();

            $paymentData = [];

            $paymentData['txnid'] = $request->input('paymentId');
            $paymentData['receipt_no'] = 'INVC' . str_pad($totalPayments + 1, 6, '0', STR_PAD_LEFT);
            $paymentData['booking_id'] = $model->id;
            $paymentData['user_id'] = $model->user_id;
            $paymentData['phone'] = $model->user->phone;
            $paymentData['email'] = $model->user->email;
            $paymentData['amount'] = $model->total_amount;
            $paymentData['payment_method'] = $request->input('payment_method');
            $paymentData['payment_status'] = '1';

            $payment_id = DB::table('payments')->insertGetId($paymentData);
            $payment = PaymentModel::findOrFail($payment_id);

            // email data
            $email_code = '';
            $email_data = [];

            
            $email_data['BOOKING_STATUS'] = 'Confirmed';

            $email_data['MODEL'] = $model->model;
            $email_data['NAME'] = $model->user->name;
            $email_data['EMAIL'] = $model->user->email;
            $email_data['PHONE'] = $model->user->phone;

            $email_data['INV'] = $payment->receipt_no;
            $email_data['TXNID'] = $payment->txnid;
            $email_data['PAYMENT_METHOD'] = ucfirst($payment->payment_method);
            $email_data['TOTAL_AMOUNT'] = $payment->total_amount;
            $email_data['PAYMENT_STATUS'] = 'Success';

            if($model->model == 'TourPackage'){
                $email_code = 'tour_receipt';

                $email_data['TOUR'] = $model->tour->package_name;
                $email_data['DAYS'] = $model->tour->days;
                $email_data['NIGHTS'] = $model->tour->nights;
                $email_data['CURR'] = $this->currency_sign;
                $email_data['ADULT_PRICE'] = $model->adult_price;
                $email_data['CHILD_PRICE'] = $model->child_price;

                $email_data['CHECKIN'] = ($model->start_date != '') ? date('jS M,Y',strtotime($model->start_date)):'NA';
                $email_data['CHECKOUT'] = ($model->end_date != '') ? date('jS M,Y',strtotime($model->end_date)):'NA';
                $email_data['ADULTS'] = $model->adults;
                $email_data['CHILDS'] = $model->childs;

            }else if($model->model == 'Hotel'){
                $email_code = 'hotel_receipt';

                $email_data['HOTEL'] = $model->hotel->hotel_name;
                $email_data['CURR'] = $this->currency_sign;
                $email_data['ROOM_PRICE'] = $model->room_price;
                $email_data['ROOMS'] = $model->rooms;

                $email_data['CHECKIN'] = ($model->start_date != '') ? date('jS M,Y',strtotime($model->start_date)):'NA';
                $email_data['CHECKOUT'] = ($model->end_date != '') ? date('jS M,Y',strtotime($model->end_date)):'NA';
                $email_data['ADULTS'] = $model->adults;
                $email_data['CHILDS'] = $model->childs;

            }else if($model->model == 'Restaurant'){
                $email_code = 'restaurant_receipt';

                $email_data['REST'] = $model->restaurant->name;
                $email_data['CURR'] = $this->currency_sign;
                $email_data['PRICE'] = $model->restaurant_price;
                $email_data['TABLES'] = $model->tables;

                $email_data['CHECKIN'] = ($model->start_date != '') ? date('jS M,Y',strtotime($model->start_date)):'NA';
                
                
            }

            SendCustomMail::dispatch($email_code,$email_data)->delay(now()->addSeconds(5));

            // email  end

            return redirect()->route('dashboard')->with('success_msg', 'Payment success !!');

        }else{

            $totalPayments = DB::table('payments')->count();

            $paymentData = [];

            $paymentData['txnid'] = $request->input('paymentId');
            $paymentData['receipt_no'] = 'INVC' . str_pad($totalPayments + 1, 6, '0', STR_PAD_LEFT);
            $paymentData['booking_id'] = $model->id;
            $paymentData['user_id'] = $model->user_id;
            $paymentData['phone'] = $model->user->phone;
            $paymentData['email'] = $model->user->email;
            $paymentData['amount'] = $model->total_amount;
            $paymentData['payment_method'] = $request->input('payment_method');
            $paymentData['payment_status'] = '0';

            $payment_id = DB::table('payments')->insertGetId($paymentData);
            $payment = PaymentModel::findOrFail($payment_id);

            // email data
            $email_code = '';
            $email_data = [];

            
            $email_data['BOOKING_STATUS'] = 'Failed';

            $email_data['MODEL'] = $model->model;
            $email_data['NAME'] = $model->user->name;
            $email_data['EMAIL'] = $model->user->email;
            $email_data['PHONE'] = $model->user->phone;

            $email_data['INV'] = $payment->receipt_no;
            $email_data['TXNID'] = $payment->txnid;
            $email_data['PAYMENT_METHOD'] = ucfirst($payment->payment_method);
            $email_data['TOTAL_AMOUNT'] = $payment->total_amount;
            $email_data['PAYMENT_STATUS'] = 'Failed';

            if($model->model == 'TourPackage'){
                $email_code = 'tour_receipt';

                $email_data['TOUR'] = $model->tour->package_name;
                $email_data['DAYS'] = $model->tour->days;
                $email_data['NIGHTS'] = $model->tour->nights;
                $email_data['CURR'] = $this->currency_sign;
                $email_data['ADULT_PRICE'] = $model->adult_price;
                $email_data['CHILD_PRICE'] = $model->child_price;

                $email_data['CHECKIN'] = ($model->start_date != '') ? date('jS M,Y',strtotime($model->start_date)):'NA';
                $email_data['CHECKOUT'] = ($model->end_date != '') ? date('jS M,Y',strtotime($model->end_date)):'NA';
                $email_data['ADULTS'] = $model->adults;
                $email_data['CHILDS'] = $model->childs;

            }else if($model->model == 'Hotel'){
                $email_code = 'hotel_receipt';

                $email_data['HOTEL'] = $model->hotel->hotel_name;
                $email_data['CURR'] = $this->currency_sign;
                $email_data['ROOM_PRICE'] = $model->room_price;
                $email_data['ROOMS'] = $model->rooms;

                $email_data['CHECKIN'] = ($model->start_date != '') ? date('jS M,Y',strtotime($model->start_date)):'NA';
                $email_data['CHECKOUT'] = ($model->end_date != '') ? date('jS M,Y',strtotime($model->end_date)):'NA';
                $email_data['ADULTS'] = $model->adults;
                $email_data['CHILDS'] = $model->childs;

            }else if($model->model == 'Restaurant'){
                $email_code = 'restaurant_receipt';

                $email_data['REST'] = $model->restaurant->name;
                $email_data['CURR'] = $this->currency_sign;
                $email_data['PRICE'] = $model->restaurant_price;
                $email_data['TABLES'] = $model->tables;

                $email_data['CHECKIN'] = ($model->start_date != '') ? date('jS M,Y',strtotime($model->start_date)):'NA';
                
                
            }

            SendCustomMail::dispatch($email_code,$email_data)->delay(now()->addSeconds(5));
            
            // email  end

            return redirect()->route('dashboard')->with('error_msg','Payment failed!');
        }

    }


    public function cancel_payment(Request $request){
        $data = $request->all();

        $booking_id = session('booking_id');

        DB::table('bookings')->where('id', $booking_id)->delete();

        return redirect()->route('dashboard')->with('error_msg','Transaction Canceled !');
    }

}