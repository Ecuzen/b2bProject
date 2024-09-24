<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Exception;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
        $this->view = $data['view'];
        $this->subject = $data['subject'];
    }

    public function build()
    {
        return $this->subject($this->subject)
                    ->view($this->view)
                    ->with('data', $this->data);
    }
    public function send($notifiable)
    {
        try {
            return parent::send($notifiable);
        } catch (Exception $e) {
            return 'Email could not be sent. Error: ' . $e->getMessage();
        }
    }
}