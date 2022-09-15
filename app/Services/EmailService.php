<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMailer;
use App\Models\User;

class EmailService {

    protected $user;

    public function __construct() 
    {
    }

    /*
    * Send verification email
    */

    public function sendVerificationEmail($user)
    {
        if(isset($user)){
            $this->user = $user;
        } else {return;}
        
        $data['user'] = $this->user;
        $link = [];

        $login = url("/api/v1/auth/verify/$this->user->id/$this->user->verification_token");
        $link['text'] = "Verify Now";
        $link['url'] = $login;
        $data['link'] = $link;
        $data['subject'] = "Verify Your Email";

        Mail::send(new GenericMailer($data, 'emails.verify'));
        
    }    

    public function sendPhoneTokenViaEmail(string $token)
    {
        $data = User::find(auth()->id())->toArray();
        $data['user'] = auth()->user();
        $data['otp'] = $token;
        $data['subject'] = "Phone Number Verification";

        Mail::queue(new GenericMailer($data, 'emails.otp'));
    }
}
