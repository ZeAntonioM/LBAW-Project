<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\PendingMail;
use Illuminate\Mail\Markdown;
use Illuminate\Queue\SerializesModels;

class MailModel extends Mailable
{
    public $mailData;

    // A
    public function __construct($mailData) {
        $this->mailData = $mailData;
    }

    // B
    public function build() {
        $subject = $this->mailData['subject'];
    
        switch ($subject) {
            case 'Recover password':
                $view = 'mail.forgot';
                break;
            case 'Invite to join project':
                $view = 'mail.invite';
                break;
        }
    
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject($subject)
            ->view($view, ['mailData' => $this->mailData]);
    }
    
}
