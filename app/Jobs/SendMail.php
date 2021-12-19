<?php

namespace App\Jobs;

use App\Mail\GenericMail;
use App\Models\Mail;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail as MailSender;

class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $mail;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //send mail
        MailSender::to($this->mail->to)->send(new GenericMail($this->mail));

        //set sent at date for mail
        $this->mail->sent_at = Carbon::now();
        $this->mail->save();
    }
}
