<?php

namespace App\Mail;

use App\Models\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class GenericMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mail;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // set mail from address, subject and view
        $mail = $this->from('info@dehqanis.com', 'Dehqanis')
            ->subject($this->mail->subject)
            ->view('emails.generic-mail');

        // set attachments
        foreach ($this->mail->getMedia() as $attachment) {
            $mail->attach($attachment->getPath(), [
                'as' => $attachment->file_name,
                'mime' => $attachment->mime_type,
            ]);
        }

        return $mail;
    }
}
