<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSignUpMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mail_binding;

    //建構子
    /*public function __construct()
    {
        $this->mail_binding = $mail_binding;
    }

    //執行工作
    public function handle()
    {
        $mail_binding = $this->mail_binding;

        Mail::send(
            'email.singUpEmailNotification',
            $mail_binding,
            function($mail) use ($mail_binding)
            {
                $mail->to($mail_binding['email']);
                $mail->from('');
                $mail->subject('恭喜註冊成功');
            });
    }*/
}
