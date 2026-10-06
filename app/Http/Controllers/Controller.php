<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Str;

use Mail;
use DB;
use Artisan;
use PDF;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $data;

    public function __construct(){
        $this->data['favicon'] = DB::table('settings')->where('slug','=','site_favicon')->first();
        $this->data['site_title'] = DB::table('settings')->where('slug','=','site_title')->first();

        $this->data['site_logo_white'] = DB::table('settings')->where('slug','=','site_logo')->first();
        $this->data['site_logo_dark'] = DB::table('settings')->where('slug','=','site_logo_dark')->first();

        $this->data['address'] = DB::table('settings')->where('slug','=','address')->first();

        $this->data['site_email'] = DB::table('settings')->where('slug','=','site_email')->first();
    
        $this->data['site_contact'] = DB::table('settings')->where('slug','=','site_contact')->first(); //admission contact
        
        $this->data['facebook_url'] = DB::table('settings')->where('slug','=','facebook_url')->first();
        $this->data['twitter_url'] = DB::table('settings')->where('slug','=','twitter_url')->first();
        $this->data['instagram_url'] = DB::table('settings')->where('slug','=','instagram_url')->first();
        $this->data['youtube_url'] = DB::table('settings')->where('slug','=','youtube_url')->first();

        
    }

    public function clear_cache() {
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');

        return redirect()->route('admin-dashboard')->with('success_msg', 'Cache,View is cleared');
    }

    public function get_currency_code(){
        $currency_code = DB::table('settings')->where('slug','=','currency_code')->first();
        return $currency_code->value;
    }

    public function get_currency_sign(){
        $currency_sign = DB::table('settings')->where('slug','=','currency_sign')->first();
        return $currency_sign->value;
    }

    public function rand_string($digits) {
        $alphanum = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
        return $rand;
    }

    public function rand_number($digits) {
        $alphanum = "123456789" . time();
        $rand = substr(str_shuffle($alphanum), 0, $digits);
      	
        return $rand;
    }

    function get_user_ip() {
		if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
			//check for ip from share internet
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		} elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			// Check for the Proxy User
			$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
		} else {
			$ip = $_SERVER["REMOTE_ADDR"];
		}
		return $ip;
	}
    function send_sms($mob_no, $otp, $sender) {
      $apiKey = '85d2fd53-7d66-11ef-8b17-0200cd936042';

      $url = 'https://2factor.in/API/V1/'.$apiKey.'/SMS/'.$mob_no.'/'.$otp.'+/'.$sender;
      $url = preg_replace("/ /", "%20", $url);

      $arrContextOptions=array(
        "ssl"=>array(
             "verify_peer"=>false,
             "verify_peer_name"=>false,
        ),
      );  
      $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
      return 1;
    }
    function sendTransSms($mob_no, $content, $sender) {
        $apiKey = '85d2fd53-7d66-11ef-8b17-0200cd936042';
        $data = 'module=TRANS_SMS&apikey='.$apiKey.'&to='.$mob_no.'&from='.$sender.'&msg='.$content;
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://2factor.in/API/R1/',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => "$data",
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        // echo $response;exit;
        return 1;
    }

    public function SendMail($data) {
        $template = view('mail.layouts.template')->render();
        $content = view('mail.' . $data['template'], $data['data'])->render();
        
        $view = str_replace('[[email_message]]', $content, $template);
        $data['content'] = $view;
        
        Mail::send([], [], function ($message) use ($data) {
            $message->from(env('MAIL_USERNAME', 'info@admin.com'), env('APP_NAME', 'Laravel'));
            $message->replyTo(env('MAIL_USERNAME', 'no-reply@admin.com'), env('APP_NAME', 'Laravel'));
            $message->subject($data['subject']);
            // $message->setBody($data['content'], 'text/html');
            $message->html($data['content']); // laravel 10 changes
            $message->to($data['to']);
        });
    }

    public function get_email_data($slug, $replacedata = array()) {
        $email_data = DB::table('email_content')->where('email_code',$slug)->first();
        $email_msg = "";
        $email_array = array();
        $email_msg = $email_data->body;
        $subject = $email_data->subject;
        if (!empty($replacedata)) {
            foreach ($replacedata as $key => $value) {
                $email_msg = str_replace("{{" . $key . "}}", $value, $email_msg);
            }
        }
        return array('body' => $email_msg, 'subject' => $subject);
    }
    
    
    public function sendCustomMail(array $mailData)
    {
        try{
            
            Mail::send([], [], function ($message) use ($mailData) {
                $message->from(env('MAIL_USERNAME', 'info@admin.com'), env('APP_NAME', 'Laravel'));
                $message->replyTo(env('MAIL_USERNAME', 'no-reply@admin.com'), env('APP_NAME', 'Laravel'));
                $message->subject($mailData['subject']);
                // $message->setBody($data['content'], 'text/html');
                $message->html($mailData['body']); // laravel 10 changes
                $message->to($mailData['to']);
            });
        

        }
        catch (Exception $e){

        }

        return true;
    }

}
