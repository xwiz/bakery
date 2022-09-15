<?php

namespace App\Models;

trait OTPTrait
{

    /**
     * Runs security checks on the user attempting to login
     *
     * @param Illuminate\Http\Request $request The request object
     */
    public function securityCheck($request)
    {
        //check for test user 
        if($this->role->id == config('constants.roles.test')){
            return true;
        }


        //check user agent
        $user_agent = $request->header('User-Agent');
        $ip = $request->ip();

        if (!$user_agent) {
            //todo: probably require API client to pass manually if not available in header
            $user_agent = $request->get('user_agent');
        }
        $new_agent = false;
        $result = $this->isRecognizedUserAgent($user_agent);
        
        if ($result === false) {
            $message = OtpLogin::generateOTP($this->id, $ip, $user_agent);
            $device = get_device_info_from_user_agent($user_agent, 'all');
    
            return [
                'message' => $message,//e.g. Enter the OTP sent to ****
                'device' => $device['device_name'],
            ];
        } elseif ($result === null) {
            $this->saveDevice($user_agent);
        }

        //ip checks
        $result = $this->isRecognizedIP($ip);
        if ($result === false) {
            $ipFunction = $new_agent == true ? 'ip_in_range' : 'ip_in_range2';
            //check if IP in range of known addresses
            foreach ($this->userLocations()->desc('created_at')->get() as $ipa) {
                if ($ipFunction($ip, $ipa->ip_address)) {
                    //save user ip to known list
                    extract($this->isRecognizedLocation($ip));//result,country
                
                    if ($country != null) {
                        $this->saveLocation($ip, $country->id);
                    }
                    return true;
                }
            }

            //check location since ip is not known or in range
            $result = $this->isRecognizedLocation($ip);
            if ($result['result'] === false) {
                $message = OtpLogin::generateOTP($this->id, $ip, $user_agent);
                return [
                    'message' => $message,
                    'ip' => $ip,
                ];
            } else {
                //note: can only be true if not false, since null will be returned only if no user location is saved
                //this would have prevented the original loop from running
                $this->saveLocation($ip, $result['country']->id);
                return true;
            }
        } elseif ($result === null) {
            extract($this->isRecognizedLocation($ip));//result,country
            if ($country != null) {
                $this->saveLocation($ip, $country->id);
            }
        }
        return true;
    }

    /**
     * Checks if given OTP for user is the correct otp and saves any new important auth information
     * That may have caused the OTP prompt
     *
     * @param string $otp The One Time Password
     * @param boolean $remember Boolean specifying if new devices should be saved
     *
     * @return bool
     */
    public function otpCheck($otp, $remember = false)
    {
        $attempt = $this->otpLogins()->desc('created_at')->first();
        if ($attempt == null) {
            //this should be impossible
            return false;
        }

        if ($attempt->otp != $otp) {
            $attempt->attempts += 1;
            $attempt->save();

            if ($attempt->attempts > 3) {
                return null;//max attempt reached
            }
            return false;
        }

        
        if ($remember) {
            $info = get_location_info_from_ip($attempt->ip_address);

            if (isset($info['country_code'])) {
                $model = Country::findByCode($info['country_code']);
    
                $this->saveLocation($attempt->ip_address, $model->id);
            }

            $this->saveDevice($attempt->user_agent);
        }
        return true;
    }

    /**
     * Saves the new location information using extracted information
     *
     * @param string $ip
     * @param int $country_id
     *
     * @return App\Models\UserLocation
     */
    public function saveLocation($ip, $country_id)
    {
        return UserLocation::updateOrCreate([
            'ip_address' => $ip,
            'country_id' => $country_id,
            'user_id' => $this->id,
        ], [
            'ip_address' => $ip,
            'country_id' => $country_id,
            'user_id' => $this->id,
        ]);
    }

    /**
     * Saves the new device information
     *
     * @param string $user_agent The user agent
     *
     * @return App\Models\UserDevice
     */
    public function saveDevice($user_agent)
    {
        $device = get_device_info_from_user_agent($user_agent, 'all');
        $data = [
            'name' => $device['device_name'],
            'user_agent' => $user_agent
        ];

        $d = Device::updateOrCreate($data, $data);
        $ud = UserDevice::create([
            'device_id' => $d->id,
            'user_id' => $this->id
        ]);

        return $ud;
    }


    /**
     * Checks if user has logged in from this ip location before
     * Will construct and return an array with the result plus extracted country information
     *
     * @param string $ip
     *
     * @return array
     */
    public function isRecognizedLocation($ip)
    {
        //check if same country
        $country = get_location_info_from_ip($ip);
        if (!isset($country['country_code'])) {
            return ['result' => false, 'country' => null];
        }

        $model = Country::findByCode($country['country_code']);
        $locations = $this->userLocations;
        if (count($locations) > 2) {
            if (!in_array($model->id, $locations->pluck('country_id')->all())) {
                return ['result' => false, 'country' => $model];
            }
            return ['result' => true, 'country' => $model];
        }
        return ['result' => null, 'country' => $model];
    }

    /**
     * Checks if user has logged in from this IP before
     * Returns null if no IPs have been registered for this user
     *
     * @return mixed null|boolean
     */
    public function isRecognizedIP($ip)
    {
        $locations = $this->userLocations;
        if (count($locations) > 2) {
            if (!in_array($ip, $locations->pluck('ip_address')->all())) {
                return false;
            }
            return true;
        }
        return null;
    }

    /**
     * Checks if user has logged in with this user agent before
     * Returns null if no user agents have been registered for this user
     *
     * @return mixed null|boolean
     */
    public function isRecognizedUserAgent($user_agent)
    {
        $agents = $this->devices;
        if (count($agents) > 2) {
            if (!in_array($user_agent, $agents->pluck('user_agent')->all())) {
                return false;
            }
            return true;
        }
        return null;
    }
}
