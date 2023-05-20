<?php

use App\Models\IPv6;
use App\Models\Browser;
use App\Models\ReviewMapping;
use App\Models\EmailGroup;
// use Image;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

if (!function_exists('get_ip')) {
    function get_ip()
    {
        // $settings = maybe_unserialize( get_option( 'kc_us_settings' ) );
        // $how_to = Helper::get_data( $settings, 'reports_reporting_options_how_to_get_ip', '' );
        // if ( $how_to ) {
        // 	return ! empty( $_SERVER[ $how_to ] ) ? $_SERVER[ $how_to ] : $_SERVER['REMOTE_ADDR'];
        // } else {

        $fields = array(
            'HTTP_CF_CONNECTING_IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED_FOR',
            'HTTP_FORWARDED',
            'REMOTE_ADDR',
        );

        foreach ($fields as $ip_field) {
            if (!empty($_SERVER[$ip_field])) {
                return $_SERVER[$ip_field];
            }
        }
        // }


        return $_SERVER['REMOTE_ADDR'];
    }
}

if (!function_exists('clean')) {
    function clean($var)
    {
        if (is_array($var)) {
            return array_map('self::clean', $var);
        } else {
            return is_scalar($var) ? sanitize_text_field($var) : $var;
        }
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($str, $keep_newlines = false)
    {
        if (is_object($str) || is_array($str)) {
            return '';
        }

        $str = (string) $str;

        $filtered = check_invalid_utf8($str);

        if (strpos($filtered, '<') !== false) {
            $filtered = pre_kses_less_than($filtered);
            // This will strip extra whitespace for us.
            $filtered = wp_strip_all_tags($filtered, false);

            // Use HTML entities in a special case to make sure no later
            // newline stripping stage could lead to a functional tag.
            $filtered = str_replace("<\n", "&lt;\n", $filtered);
        }

        if (!$keep_newlines) {
            $filtered = preg_replace('/[\r\n\t ]+/', ' ', $filtered);
        }
        $filtered = trim($filtered);

        $found = false;
        while (preg_match('/%[a-f0-9]{2}/i', $filtered, $match)) {
            $filtered = str_replace($match[0], '', $filtered);
            $found = true;
        }

        if ($found) {
            // Strip out the whitespace that may now exist after removing the octets.
            $filtered = trim(preg_replace('/ +/', ' ', $filtered));
        }

        return $filtered;
    }
}

if (!function_exists('check_invalid_utf8')) {
    function check_invalid_utf8($string, $strip = false)
    {
        $string = (string) $string;

        if (0 === strlen($string)) {
            return '';
        }

        // Store the site charset as a static to avoid multiple calls to get_option().
        static $is_utf8 = null;
        if (!isset($is_utf8)) {
            $is_utf8 = in_array('UTF-8', array('utf8', 'utf-8', 'UTF8', 'UTF-8'), true);
        }
        if (!$is_utf8) {
            return $string;
        }

        // Check for support for utf8 in the installed PCRE library once and store the result in a static.
        static $utf8_pcre = null;
        if (!isset($utf8_pcre)) {
            // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
            $utf8_pcre = @preg_match('/^./u', 'a');
        }
        // We can't demand utf8 in the PCRE installation, so just return the string in those cases.
        if (!$utf8_pcre) {
            return $string;
        }

        // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- preg_match fails when it encounters invalid UTF8 in $string.
        if (1 === @preg_match('/^./us', $string)) {
            return $string;
        }

        // Attempt to strip the bad chars if requested (not recommended).
        if ($strip && function_exists('iconv')) {
            return iconv('utf-8', 'utf-8', $string);
        }

        return '';
    }
}

if (!function_exists('pre_kses_less_than')) {
    function pre_kses_less_than($text)
    {
        return preg_replace_callback('%<[^>]*?((?=<)|>|$)%', 'pre_kses_less_than_callback', $text);
    }
}

if (!function_exists('pre_kses_less_than_callback')) {
    function pre_kses_less_than_callback($matches)
    {
        if (false === strpos($matches[0], '>')) {
            return esc_html($matches[0]);
        }
        return $matches[0];
    }
}

if (!function_exists('esc_html')) {
    function esc_html($text)
    {
        $safe_text = check_invalid_utf8($text);
        $safe_text = specialchars($safe_text, 3);
        return $safe_text;
    }
}

if (!function_exists('specialchars')) {
    function specialchars($string, $quote_style = 0, $charset = false, $double_encode = false)
    {
        $string = (string) $string;

        if (0 === strlen($string)) {
            return '';
        }

        // Don't bother if there are no specialchars - saves some processing.
        if (!preg_match('/[&<>"\']/', $string)) {
            return $string;
        }

        // Account for the previous behaviour of the function when the $quote_style is not an accepted value.
        if (empty($quote_style)) {
            $quote_style = 0;
        } elseif (16 === $quote_style) {
            $quote_style = 3 | 16;
        } elseif (!in_array($quote_style, array(0, 2, 3, 'single', 'double'), true)) {
            $quote_style = 3;
        }

        // Store the site charset as a static to avoid multiple calls to wp_load_alloptions().
        if (!$charset) {
            static $_charset = null;
            if (!isset($_charset)) {
                $_charset = 'UTF-8';
            }
            $charset = $_charset;
        }

        if (in_array($charset, array('utf8', 'utf-8', 'UTF8'), true)) {
            $charset = 'UTF-8';
        }

        $_quote_style = $quote_style;

        if ('double' === $quote_style) {
            $quote_style = 2;
            $_quote_style = 2;
        } elseif ('single' === $quote_style) {
            $quote_style = 0;
        }

        // if ( ! $double_encode ) {
        // 	// Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
        // 	// This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
        // 	$string = wp_kses_normalize_entities( $string, ( $quote_style & 16 ) ? 'xml' : 'html' );
        // }

        $string = htmlspecialchars($string, $quote_style, $charset, $double_encode);

        // Back-compat.
        if ('single' === $_quote_style) {
            $string = str_replace("'", '&#039;', $string);
        }

        return $string;
    }
}

if (!function_exists('rest_is_ip_address')) {
    function rest_is_ip_address($ip)
    {
        $ipv4_pattern = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/';

        if (!preg_match($ipv4_pattern, $ip) && !IPv6::check_ipv6($ip)) {
            return false;
        }

        return $ip;
    }
}

if (!function_exists('get_curl_call')) {
    function get_curl_call($url, array $get = null, array $options = array())
    {
        $defaults = array(
            CURLOPT_URL => $url,
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 4
        );

        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if (!$result = curl_exec($ch)) {
            trigger_error(curl_error($ch));
        }
        curl_close($ch);
        if (json_decode($result, true)) {
            return json_decode($result, true);
        } else {
            return $result;
        }
        // return json_decode($result, true);
    }
}

if (!function_exists('getBrowserIcon')) {
    function getBrowserIcon($browser = "")
    {
        return Browser::get_browser_icon_url($browser);
    }
}

if (!function_exists('getDeviceIcon')) {
    function getDeviceIcon($device = '')
    {
        return Browser::get_device_icon_url($device);
    }
}

if (!function_exists('uploadFileToStorage')) {
    function uploadFileToStorage($file_obj, $file_with_path, $thumb_path = null, $width = null, $height = null)
    {
        $obj = Image::make($file_obj);
        if (!empty($width) || !empty($height)) {
            $obj->resize($width, $height, function ($constraint) {
                // $constraint->aspectRatio();
            });
        }
        Storage::put($file_with_path, (string) $obj->encode());

        if (!empty($thumb_path)) {
            Storage::put($thumb_path, (string) $obj->encode());
        }
    }
}
if (!function_exists('userProfileUploade')) {
    function userProfileUploade($logo, $file_with_path, $file_obj)
    {
        // dd($logo,$file_obj, $file_with_path);
        $logo->storeAs($file_with_path, $file_obj);
        // $path = $mp3_audio->storeAs($path, $mp3filename);
    }
}

if(!function_exists('svgTOpngStoreFile')){
    function svgTOpngStoreFile($file,$path,$fileName){
        $svgs = new Imagick();
        $svgs->setBackgroundColor(new ImagickPixel('transparent'));
        $svg = file_get_contents($file);
        $svgs->readImageBlob($svg);
        $svgs->setImageFormat("png32");
        Storage::put($path."/".$fileName, $svgs);
    }
}

if (!function_exists('mp3FileToStorage')) {
    function mp3FileToStorage($mp3_audio, $path, $mp3filename)
    {
        $path = $mp3_audio->storeAs($path, $mp3filename);
        // Storage::put($file_with_path, $file_obj);
    }
}

if (!function_exists('wavFileToStorage')) {
    function wavFileToStorage($wav_audio, $path, $wavfilename)
    {
        $path = $wav_audio->storeAs($path, $wavfilename);
        //Storage::put($file_with_path, (string) $file_obj);
    }
}


if (!function_exists('removeFileFromStorage')) {
    function removeFileFromStorage($file_with_path, $thumb = null)
    {
        if (Storage::exists($file_with_path)) {
            Storage::delete($file_with_path);
        }
        if (!empty($thumb) && Storage::exists($thumb)) {
            Storage::delete($thumb);
        }
    }
}

if (!function_exists('getFileFromStorage')) {
    function getFileFromStorage($file_with_path)
    {
        if (Storage::exists($file_with_path)) {
            return asset('storage/' . $file_with_path);
        }
        return "";
    }
}

if (!function_exists('removeDirectoryFromStorage')) {
    function removeDirectoryFromStorage($directory)
    {
        Storage::deleteDirectory($directory);
    }
}

if (!function_exists('getFileSizeInBytes')) {
    function getFileSizeInBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = substr($val, 0, -1);
        switch ($last) {
            case 'g':
                $val *= (1024 * 1024 * 1024); //1073741824
                break;
            case 'm':
                $val *= (1024 * 1024); //1048576
                break;
            case 'k':
                $val *= 1024;
                break;
        }

        return $val;
    }
}
if (!function_exists('getFileSizeInReadable')) {
    function getFileSizeInReadable($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val) - 1]);
        $val = substr($val, 0, -1);
        switch ($last) {
            case 'g':
                $val .= " GB";
                break;
            case 'm':
                $val .= " MB";
                break;
            case 'k':
                $val .= " KB";
                break;
        }

        return $val;
    }
}
if (!function_exists('removeDirectoryFromStorage')) {
    function removeDirectoryFromStorage($directory)
    {
        Storage::deleteDirectory($directory);
    }
}

if (!function_exists('allowFeedback')) {
    function allowFeedback($id, $pass_key = '')
    {
        $_emailGroup = EmailGroup::Where('pass_key', '=', $pass_key)->first();
        if (isset(Auth::user()->id)) {
            $authDetails = Auth::user();
        }
        // $authDetails = Auth::user();
        $_reviewFeedbackMapping = ReviewMapping::where('campaign_id', '=', $id);
        if (isset($authDetails)) {
            $_reviewFeedbackMapping->where('user_id', '=', $authDetails->id);
        } elseif (isset($_emailGroup)) {
            $_reviewFeedbackMapping->where('email', '=', $_emailGroup->email);
        }
        $reviewFeedbackMapping = $_reviewFeedbackMapping->first();
        if (isset($authDetails) && $authDetails->can_submit_feedbacks == 0) {
            if ($reviewFeedbackMapping && $reviewFeedbackMapping->feedback == 1) {
                return true;
            } elseif ($reviewFeedbackMapping && $reviewFeedbackMapping->feedback == 0) {
                return false;
            } else {
                return true;
            }
        } else {
            if ($reviewFeedbackMapping && $reviewFeedbackMapping->feedback == 0) {
                return false;
            } else {
                return true;
            }
        }
        //return true;
    }
}
if(!function_exists('setSMTP')) {
    function setSMTP($user_id = null)
    {
        $userCheck = DB::table('users')->where([
                ['id', '=', $user_id],
                ['dropthis_mail_or_own_mail','=',1]
            ])->first();
        $_user = DB::table('users')->orderBy('id');
        
        if(is_null($userCheck)) {
            $_user->where('user_role', '=', USER_ROLE_ADMIN);
        }else{
            $_user->where('id', '=', $user_id);
        }
            
        $user = $_user->first();
        
        $mail = DB::table('smtp_credentials')->where('user_id', '=', $user->id)->first();
        
        if($mail) {
            $config = array(
                'transport'  => $mail->mail_mailer,
                'host'       => $mail->mail_host,
                'port'       => $mail->mail_port,
                'encryption' => $mail->mail_encryption,
                'username'   => $mail->mail_username,
                'password'   => $mail->mail_password,
            );
            Config::set('mail.mailers.smtp', $config);
            Config::set('mail.from', array('address' => $mail->mail_from_address, 'name' => $mail->mail_from_name));
        }
    }
}
