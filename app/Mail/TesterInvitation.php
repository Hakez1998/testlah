<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TesterInvitation extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->subject('Invitation to test Project: '. $this->data['project'].' on Testlah')
                ->markdown('emails.invitation.testers', [
                    'url' => $this->data['link'],
                    'user' => $this->data['email'],
                    'title' => $this->data['project'],
                    'sender' => User::find(Auth::id())->name,
                ]);
    }
}
