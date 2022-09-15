<?php

use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Str;

/**
 * Get the murmur hash of a given text
 *
 * @param  string $key  Text to hash.
 * @param  number $seed Positive integer only
 * @return number 32-bit positive integer hash
 */
function murmur_hash(string $key, int $seed = 0) : int
{
    $key  = array_values(unpack('C*', $key));
    $klen = count($key);
    $h1   = $seed < 0 ? -$seed : $seed;
    $remainder = $i = 0;
    for ($bytes = $klen - ($remainder = $klen & 3); $i < $bytes;) {
        $k1 = $key[$i]
        | ($key[++$i] << 8)
        | ($key[++$i] << 16)
        | ($key[++$i] << 24);
        ++$i;
        $k1  = (((($k1 & 0xffff) * 0xcc9e2d51) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0xcc9e2d51) & 0xffff) << 16))) & 0xffffffff;
        $k1  = $k1 << 15 | ($k1 >= 0 ? $k1 >> 17 : (($k1 & 0x7fffffff) >> 17) | 0x4000);
        $k1  = (((($k1 & 0xffff) * 0x1b873593) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0x1b873593) & 0xffff) << 16))) & 0xffffffff;
        $h1 ^= $k1;
        $h1  = $h1 << 13 | ($h1 >= 0 ? $h1 >> 19 : (($h1 & 0x7fffffff) >> 19) | 0x1000);
        $h1b = (((($h1 & 0xffff) * 5) + ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 5) & 0xffff) << 16))) & 0xffffffff;
        $h1  = ((($h1b & 0xffff) + 0x6b64) + ((((($h1b >= 0 ? $h1b >> 16 : (($h1b & 0x7fffffff) >> 16) | 0x8000)) + 0xe654) & 0xffff) << 16));
    }
    $k1 = 0;
    switch ($remainder) {
        case 3:
            $k1 ^= $key[$i + 2] << 16;
        //perform shift reductions for 3
        case 2:
            $k1 ^= $key[$i + 1] << 8;
        //perform shift reductions for 2
        case 1:
            $k1 ^= $key[$i];
            $k1  = ((($k1 & 0xffff) * 0xcc9e2d51) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0xcc9e2d51) & 0xffff) << 16)) & 0xffffffff;
            $k1  = $k1 << 15 | ($k1 >= 0 ? $k1 >> 17 : (($k1 & 0x7fffffff) >> 17) | 0x4000);
            $k1  = ((($k1 & 0xffff) * 0x1b873593) + ((((($k1 >= 0 ? $k1 >> 16 : (($k1 & 0x7fffffff) >> 16) | 0x8000)) * 0x1b873593) & 0xffff) << 16)) & 0xffffffff;
            $h1 ^= $k1;
    }
    $h1 ^= $klen;
    $h1 ^= ($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000);
    $h1  = ((($h1 & 0xffff) * 0x85ebca6b) + ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 0x85ebca6b) & 0xffff) << 16)) & 0xffffffff;
    $h1 ^= ($h1 >= 0 ? $h1 >> 13 : (($h1 & 0x7fffffff) >> 13) | 0x40000);
    $h1  = (((($h1 & 0xffff) * 0xc2b2ae35) + ((((($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000)) * 0xc2b2ae35) & 0xffff) << 16))) & 0xffffffff;
    $h1 ^= ($h1 >= 0 ? $h1 >> 16 : (($h1 & 0x7fffffff) >> 16) | 0x8000);
    return $h1;
}

/**
 * Gets the hexed murmur hash of a string
 *
 * @param  string $key  Text to hash.
 * @param  number $seed Positive integer only
 * @return string
 */
function murmur_hex(string $key, int $seed = 0) : string
{
    return base_convert(sprintf("%u\n", murmur_hash($key, $seed)), 10, 32);
}

/**
 * Change/move the index of an element in an array
 */
function move_element(&$array, $a, $b)
{
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}

/**
 * Pushes an array element by key to the front of the array
 */
function move_element_to_front($array, $key)
{
    return array($key => $array[$key]) + $array;
}

/**
 * Generates random numeric or alphanumeric characters
 * 
 * @param int $len The number of characters to generate
 * @param boolean $isNumeric Specify the type of chars to generate, use null for only alphabets
 * @param boolean $useLower Specifiy if lower case should be included
 * 
 * @return string The generated result
 */
function generate_random($len = 6, $isNumeric = true, $useLower = false)
{
    $uLetters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $lLetters = "abcdefghijklmnopqrstuvwxyz";
    $numbers = "0123456789";
    $all = $isNumeric === null ? "" : $numbers;
    if (!$isNumeric) {
        $all .= $uLetters;
        if ($useLower) {
            $all .= $lLetters;
        }
    }
    $result = "";
    $l = strlen($all) - 1;
    for ($i = 0; $i < $len; $i++) {
        $result .= $all[random_int(0, $l)];
    }
    return $result;
}

/**
 * Gets the browser or device information from the user agent
 *
 * @param $agent_string The user agent to parse
 * @param $param The parameter to retrieve
 *
 * @return mixed Array of values, bool or string indicating inquired information
 */
function get_device_info_from_user_agent($agent_string, $param = 'all')
{
    $supported = ['all', 'is_mobile', 'is_tablet', 'is_desktop', 'is_bot', 'browser', 'platform', 'device'];
    if (in_array($param, $supported)) {
        $platform = "";
        if ($param == 'all' || $param == 'platform') {
            $platform = Agent::platform();
            if (strtolower($platform) == 'windows') {
                $platform .= ' '.windows_version_name(Agent::version(Agent::platform()));
            } else {
                $platform .= ' '.Agent::version(Agent::platform());
            }
        }

        switch ($param) {
            case "all":
                $device = [
                    'browser' => Agent::browser(),
                    'device' => Agent::device(),
                    'platform' => $platform,
                    'is_tablet' => Agent::isTablet(),
                    'is_mobile' => Agent::isMobile(),
                    'is_desktop' => Agent::isDesktop(),
                    'is_bot' => Agent::isRobot(),
                ];
                $device_name = $device['is_mobile'] ? $device['device'] : $device['platform'];
                $device['device_name'] = rtrim($device_name);
                $device['device_type'] = $device['is_tablet'] ?
                    Config::get('constants.device_types.tablet') :
                ($device['is_mobile'] ? Config::get('constants.device_types.mobile') : Config::get('constants.device_types.desktop'));
                return $device;
            case "browser":
                return Agent::browser();
            case "device":
                return Agent::device();
            case "platform":
                return $platform;
            case "is_bot":
                return Agent::isRobot();
            case "is_mobile":
                return Agent::isMobile();
            case "is_tablet":
                return Agent::isTablet();
            case "is_desktop":
                return Agent::isDesktop();
        }
    }
    return null;
}

function windows_version_name($version)
{
    $parts = explode('.', $version);
    switch ($parts[0]) {
        case '4':
            break;
        switch ($parts[1]) {
            case '0':
                return '95';
            case '10':
                return '98';
            case '90':
                return 'Me';
        }
        case '5':
            switch ($parts[1]) {
                case '0':
                    return '2000';
                case '1':
                    return 'XP';
                case '2':
                    return '2003';
            }
            break;
        case '6':
            switch ($parts[1]) {
                case '0':
                    return 'Vista';
                case '1':
                    return '7';
                case '2':
                    return '8';
                case '3':
                    return '8.1';
            }
            break;
        case '10':
            switch ($parts[1]) {
                case '0':
                    return '10';
            }
            break;
    }
    return "";
}

/**
 * Check if a given ip is in a network
 * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
 * @param  string $range IP/CIDR netmask eg. 127.0.0.0/24, also 127.0.0.1 is accepted and /32 assumed
 * @return bool true if the ip is in this range / false if not.
 */
function ip_in_range($ip, $range)
{
    if (strpos( $range, '/' ) == false) {
        if (substr($range, -1) != '0') {
            //strip from last dot
            $range = substr($range, 0, strrpos($range, '.')).'.0';
        }
        $range .= '/32';
    }
    // $range is in IP/CIDR format eg 127.0.0.1/24
    list( $range, $netmask ) = explode( '/', $range, 2 );
    $range_decimal = ip2long( $range );
    $ip_decimal = ip2long( $ip );
    $wildcard_decimal = pow( 2, ( 32 - $netmask ) ) - 1;
    $netmask_decimal = ~ $wildcard_decimal;
    return ( ( $ip_decimal & $netmask_decimal ) == ( $range_decimal & $netmask_decimal ) );
}

/**
 * Gross/light check if two ips are in same network
 * @param  string $ip    IP to check in IPV4 format eg. 127.0.0.1
 * @param  string $ip2    IP to check in IPV4 format eg. 127.0.0.1
 * @return bool true if the two ips start with the same first two nets.
 */
function ip_in_range2($ip, $ip2)
{
    $index = strpos($ip, '.', strpos($ip, '.') + 1);
    return Str::startsWith($ip2, substr($ip, 0, $index));
}

function get_visitor_ip($deep_detect = false)
{
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
        $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
    } else {
        $ip = $_SERVER["REMOTE_ADDR"];
    }
    if ($deep_detect) {
        if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    return $ip;
}

function constants_decode($key, $item)
{
    $arr = array_flip(config("constants.$key"));
    if (isset($arr[$item])) {
        $index = $arr[$item];
        return Str::title(str_replace('_', ' ', $index));
    }
    return $item;
}

/**
 * Gets the user location information from given IP from DB or Geocoding service
 *
 * @param $ip The ip to query
 * @param $purpose The parameter you are querying the IP for
 * @param $deep_detect A bool indicating if we are going to try retrieving the IP using advanced search
 *
 * @return mixed Array or string indicating inquired information
 */
function get_location_info_from_ip($ip, $purpose = "all", $deep_detect = true)
{
    if (env('APP_ENV') == 'testing' || $ip == "127.0.0.1") {
        //use fake country for testing
        return ['country_code' => 'US'];
    }
    $output = null;
    if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
        //if IP is spoofed, try to find real IP from cloudflare or SERVER variables
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
    }
    $purpose    = strtolower(trim($purpose));
    $support    = array("country", "country_code", "state", "region", "city", "location", "address", "all");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            //check online service
            //todo: We might consider adding another geocoding service here
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
            $all_info = array(
                "city"           => @$ipdat->geoplugin_city,
                "state"          => @$ipdat->geoplugin_regionName,
                "country"        => @$ipdat->geoplugin_countryName,
                "country_code"   => @$ipdat->geoplugin_countryCode,
                "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                "continent_code" => @$ipdat->geoplugin_continentCode
            );
    
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        //same as all
                    case "all":
                        return $all_info;
                    case "address":
                        $address = array($all_info['country']);
                        if (strlen($all_info['state']) >= 1) {
                            $address[] = $all_info['state'];
                        }
                        if (strlen($all_info['city']) >= 1) {
                            $address[] = $all_info['city'];
                        }
                        $output = implode(", ", array_reverse($address));
                        return $output;
                    case "region":
                        return $all_info['state'];
                    default:
                        if (isset($all_info[$purpose])) {
                            return $all_info[$purpose];
                        }
                        break;
                }
            }
    }
    return $output;
}


/**
 * Partially mangles provided email
 *
 * e.g. opatachibueze@gmail.com = opxxxxxxxxxe@gmail.com
 */
function partial_mangle($value)
{
    if ($value == "") {
        return $value;
    }
    
    $parts = explode('@', $value);
    if (count($parts) != 2) {
        //cannot deal with this
        return $value;
    }
    $emailname = $parts[0];
    if (strlen($emailname) == 3) {
        $first_char = substr($emailname, 0, 1);
        return $first_char.'xx'.'@'.$parts[1];
    }
    $first_2char = substr($emailname, 0, 2);
    $slen = strlen($emailname);
    $last_char = $emailname[$slen - 1];
    return $first_2char.str_repeat('x', $slen - 3).$last_char.'@'.$parts[1];
}

function get_class_basename($className) {
    if ($pos = strrpos($className, '\\')) {
        return substr($className, $pos + 1);
    } else {
        return $className;
    }
}

/**
 * Gets the formatted amount of money
 *
 * @param $amount The amount we need to format
 * @param $decimals The number of decimal places
 *
 * @return string The formatted value
 */
function pretty_naira($amount, $decimals = 2)
{
    $no_digits = strlen(strval(intval($amount)));
    $notation = "";
    $divider = 1;
    switch ($no_digits) {
        case 4:
        case 5:
        case 6:
            $divider = 1000;
            $notation = "K";
            break;
        case 7:
        case 8:
        case 9:
            $divider = 1000000;
            $notation = "M";
            break;
        case 10:
        case 11:
        case 12:
            $divider = 1000000000;
            $notation = "B";
            break;
        case 13:
        case 14:
        case 15:
            $divider = 1000000000000;
            $notation = "T";
            break;
    }
    $value = rtrim(strval(number_format(($amount / $divider), $decimals)), '0');
    return rtrim($value, '.').$notation;
}

/**
 * @param string $reference
 * @return bool
 */
function verify_paystack_transaction(string $reference)
{
    $curl = curl_init();
    $secretKey = env('PAYSTACK_SECRET_KEY');

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer ".$secretKey,
            "Accept: application/json"
        ],
    ));

    $response = json_decode(curl_exec($curl));

    $error = curl_error($curl);
    if ($error) {
        \Log::debug($error);
        return null;
    }

    curl_close($curl);
    return $response;
}


/**
 * @param $account_number
 * @param $bank_code
 * @return false|mixed
 */
function verify_account_number($account_number, $bank_code)
{
    $curl = curl_init();
    $secretKey = env('PAYSTACK_SECRET_KEY');
    $url = "https://api.paystack.co/bank/resolve?account_number=$account_number&bank_code=$bank_code";

    curl_setopt_array($curl, array(
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer ".$secretKey,
      ),
    ));
    
    $response = json_decode(curl_exec($curl));

    if (!$response) {
        $error = curl_error($curl);
        if ($error) {
            \Log::debug($error);
        }
    }
    
    curl_close($curl);

    return $response;
}