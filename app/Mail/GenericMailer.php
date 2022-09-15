<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class GenericMailer extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $fname;
    public $lname;
    public $email;
    public $subject;
    public $template;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data, $template)
    {
        $this->data = $data;
        if (isset($data['user'])) {
            $this->user = $data['user'];
            if (is_array($this->user)) {
                $this->user = new User($this->user);
            }
            $this->email = $this->user->email;
        }
        if (isset($data['first_name'])) {
            $this->fname = $data['first_name'];
        }
        if (isset($data['last_name'])) {
            $this->lname = $data['last_name'];
        }
        if (isset($data['email'])) {
            $this->email = $data['email'];
        }
        if (isset($data['subject'])) {
            $this->subject = $data['subject'];
        }
        $this->template = $template;
    }

    public function getFullName()
    {
        if (!empty($this->user)) {
            return $this->user->fullName;
        } else {
            return $this->fname.' '.$this->lname;
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->email, $this->getfullName())        
            ->from(config('app.support_email'), config('app.name'))
            ->subject($this->subject ?: 'Email Notification')
            ->view($this->template, $this->data);
    }
}
