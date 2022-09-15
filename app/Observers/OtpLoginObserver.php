<?php

namespace App\Observers;

use App\Models\OtpLogin;
use Illuminate\Support\Facades\Mail;
use App\Mail\GenericMailer;

class OtpLoginObserver
{
    /**
     * Handle the otpLogin "created" event.
     *
     * @param  \App\Models\OtpLogin  $otp
     * @return void
     */
    public function created(OtpLogin $otpLogin)
    {
        $user = $otpLogin->user;
        if ($user->email != null) {
            $data = $user->toArray();
            $data['user'] = $user;
            $data['otp'] = $otpLogin->otp;
            $data['subject'] = "Login Verification";

            Mail::queue(new GenericMailer($data, 'emails.otp'));
        }
    }
}
