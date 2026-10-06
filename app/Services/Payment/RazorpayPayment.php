<?php
namespace App\Services\Payment;
use App\Services\Payment\PaymentGatewayInterface;
use Razorpay\Api\Api;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Jobs\SendCustomMail;
use App\Models\Booking;
use App\Models\Payment;
use DB;

class RazorpayPayment implements PaymentGatewayInterface
{
    private $exponent;
    private $currency;
    private $razorpay_key_id;
    private $razorpay_key_secret;
    private $currency_sign;

    function __construct(){

        $currency_exponent = DB::table('settings')->where('slug','=','currency_exponent')->first();
        $this->exponent = $currency_exponent->value;

        $currency_sign = DB::table('settings')->where('slug','=','currency_sign')->first();
        $this->currency_sign = $currency_sign->value;

        $currency_code = DB::table('settings')->where('slug','=','currency_code')->first();
        $this->currency = $currency_code->value;

        $razorpay_key = DB::table('payment_settings')->where('slug','=','razorpay_key_id')->first();
        $this->razorpay_key_id = $razorpay_key->value;

        $razorpay_secret = DB::table('payment_settings')->where('slug','=','razorpay_key_secret')->first();
        $this->razorpay_key_secret = $razorpay_secret->value;
    }

    public function create_payment($param)
    {
        $order=[];

        $data = $param['data'];
        $payment_method = $param['payment_method'];

        $orderId = $data->id;

        $amount = 0;

        if($this->exponent == '2'){
            $amount = $data->total_amount * 100;
        }else if($this->exponent == '3'){
            $amount = $data->total_amount * 1000;
        }else{
            $amount = $data->total_amount;
        }
        
        $order['amount'] = $amount;
        $order['currency'] = $this->currency;
        $order['payment_capture'] = 1;
        $order['notes'] = [ 
                "order_id" => $data->id
            ];

        $response_url = route('response');
        $cancel_url = route('cancel').'?payment_method='.$payment_method;

        $api = new Api($this->razorpay_key_id, $this->razorpay_key_secret);

        $razorpayOrder = $api->order->create($order);
        // print_r($razorpayOrder);exit;

        $razorpayOrderId = $razorpayOrder['id'];
        session(['razorpay_order_id'=> $razorpayOrderId]);

        $data = [
            "key"               => $this->razorpay_key_id,
            "amount"            => $amount,
            "name"              => $data->user->name,
            "description"       => 'Booking a '.$data->model,
            "prefill"           => [
                "name"              => $data->user->name,
                "email"             => $data->user->email,
                "contact"           => $data->user->phone,
            ],
            "notes"             => [
                "order_id" => $data->id
            ],
            "theme"             => [
                "color"             => "#1466A2"
            ],
            "order_id"          => $razorpayOrderId
        ];

        $json = json_encode($data);

        return view( 'payment.razorpay-checkout', compact( 'payment_method','json','response_url','cancel_url','razorpayOrderId','orderId') );

    }

    public function response_payment(Request $request){
        $input = $request->all();
        
        $booking_id = $input['order_id'];
        $model = Booking::findOrFail($booking_id);
        $api = new Api($this->razorpay_key_id, $this->razorpay_key_secret);

        $success = true;
        $error = "Payment Failed";

        if (empty($_POST['razorpay_payment_id']) === false){
            try
            {

                $attributes = array(
                    'razorpay_order_id' => $_POST['razorpay_order_id'],
                    'razorpay_payment_id' => $_POST['razorpay_payment_id'],
                    'razorpay_signature' => $_POST['razorpay_signature']
                );
        
                $api->utility->verifyPaymentSignature($attributes);
            }
            catch(SignatureVerificationError $e)
            {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true){
            $razorpayOrder = $api->order->fetch($_POST['razorpay_order_id']);

            // print_r($razorpayOrder);exit;

            $razorpay_order_id = $razorpayOrder['id'];
        
            DB::table('bookings')->where('id', $input['order_id'])->update(['booking_status' => '1']);

            $totalPayments = DB::table('payments')->count();

            $paymentData = [];

            $paymentData['txnid'] = $input['razorpay_payment_id'];
            $paymentData['receipt_no'] = 'INVC' . str_pad($totalPayments + 1, 6, '0', STR_PAD_LEFT);
            $paymentData['booking_id'] = $model->id;
            $paymentData['user_id'] = $model->user_id;
            $paymentData['phone'] = $model->user->phone;
            $paymentData['email'] = $model->user->email;
            $paymentData['amount'] = $model->total_amount;
            $paymentData['payment_method'] = $input['payment_method'];
            $paymentData['payment_status'] = '1';

            $payment_id = DB::table('payments')->insertGetId($paymentData);
            $payment = Payment::findOrFail($payment_id);

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

            

            return redirect()->route('dashboard')->with('success_msg','Payment Successful!');
            
        }else{

            $fetch_data =$request->error;
            $response_data =json_decode(html_entity_decode($fetch_data['metadata']));

            $razorpayOrder = $api->order->fetch($response_data->razorpay_order_id);

            // DB::table('bookings')->where('id', $input['order_id'])->delete();

            $paymentData = [];

            $paymentData['txnid'] = $input['razorpay_payment_id'];
            $paymentData['receipt_no'] = 'INVC' . str_pad($totalPayments + 1, 6, '0', STR_PAD_LEFT);
            $paymentData['booking_id'] = $model->id;
            $paymentData['user_id'] = $model->user_id;
            $paymentData['phone'] = $model->user->phone;
            $paymentData['email'] = $model->user->email;
            $paymentData['amount'] = $model->total_amount;
            $paymentData['payment_method'] = $input['payment_method'];
            $paymentData['payment_status'] = '0';

            $payment_id = DB::table('payments')->insertGetId($paymentData);
            $payment = Payment::findOrFail($payment_id);

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

            return redirect()->route('dashboard')->with('error_msg','Transaction Failed !');
        }

    }

    public function cancel_payment(Request $request){
        $input = $request->all();
        
        $api = new Api($this->razorpay_key_id, $this->razorpay_key_secret);

        $razorpayOrder = $api->order->fetch(session('razorpay_order_id'));
        $orderId =$razorpayOrder['notes']['order_id'];

        DB::table('bookings')->where('id', $orderId)->delete();

        return redirect()->route('dashboard')->with('error_msg','Transaction Canceled !');
    }



}