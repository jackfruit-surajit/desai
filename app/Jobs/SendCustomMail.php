<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use App\Mail\SendEmail;
use Mail;

class SendCustomMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $email_code;
    public $email_data;

    /**
     * Create a new job instance.
     */
    public function __construct($email_code,$email_data)
    {
        $this->email_code = $email_code;
        $this->email_data = $email_data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        $emailData = DB::table('email_content')->where('email_code',$this->email_code)->first();
        $email_msg = "";
        $email_array = array();
        $email_msg = $emailData->body;
        $subject = $emailData->subject;
        if (!empty($this->email_data)) {
            foreach ($this->email_data as $key => $value) {
                $email_msg = str_replace("{{" . $key . "}}", $value, $email_msg);
            }
        }

        Mail::to($this->email_data['EMAIL'])->send(new SendEmail([
            'subject' => $subject,
            'message' => $email_msg,
        ]));
        
    }
}
