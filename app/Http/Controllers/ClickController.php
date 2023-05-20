<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Click;
use App\Models\Browser;
use App\Models\GeoLocation;
use Illuminate\Validation\Rule;
Use Redirect;
use DateTime;
use App\Models\Domain;
use Illuminate\Support\Facades\Hash;

class ClickController extends Controller
{

    public function redirectSlug($slug, Request $request ){
    	// get link by slug...
			
    	$link = Link::where(['slug' => $slug])->firstOrFail();

    	// Domain Reddirection
    	$currentURL = url()->current();
    	$rule = unserialize($link->rules);
    	$currentBaseurl = url('/');
    	if(!empty($rule[1]) && $rule[1] == url('/') && $currentURL == $rule[1]."/".$slug){ // home
    		$domain = true;
    	} else if(!empty($rule[1]) && $rule[1] == 'any'){
    		// check current domain is in our database or not
    		$domain = Domain::where([['host','=', $currentBaseurl],['status',1]])->first();
    		if(!$domain && $currentBaseurl == url('/')){
    			$domain = true;
    		}
    	} else {
    		// check current domain is in our database or not
    		$domain = Domain::where(['status' => 1, 'host' => $currentBaseurl])->first();
    		if(!empty($rule[1]) && $currentBaseurl != $rule[1]){
    			$domain = false;
    		}
    	}
    	// dd($domain,$currentBaseurl,$rule[1]);

    	// if(strtolower($rule[1]) != 'any'){
    	// 	// get domain by host
    	// 	$domain = Domain::where(['status' => 1, 'host' => $rule[1]])->first();
    	// 	if(($domain && $rule[1]."/".$slug == $currentURL) || $currentBaseurl == url('/')){
    	// 		$domain = true;
    	// 	}
    	// 	// $host = $rule[1]."/".$slug;
    	// 	// if($host != $currentURL){
    	// 	// 	$error = "Domain is not matched";
    	// 	// 	return response(view('errors.404', compact('error')), 404);
    	// 	// }
    	// } else if($currentBaseurl !== url('/')) {
	    // 	// check the domain in all 
	    // 	$domain = Domain::where([['host','=', $currentBaseurl],['status',1]])->first();
	    // } else {
	    // 	$domain = true;
	    // }

	    if(!$domain){
			$error = "Domain is not matched";
			return response(view('errors.404', compact('error')), 404);
    	}

    	if(empty($link->url)){
	    	$error = "Target URL is not found";
	    	return response(view('errors.404', compact('error')), 404);
    	}
		$currentDate = strtotime(date('m/d/Y'));
		$expireDate = strtotime($link->expires_at);
		
		if(isset($expireDate) && !empty($expireDate)){
			if ($currentDate > $expireDate){
				$error = "Link is expired";
		    	return response(view('errors.404', compact('error')), 404);
			}			
		}

		if($link->password != null && strtolower($request->method()) == 'get') {
    		return view('pages.password',['slug'=>$slug]);
    	} else if(strtolower($request->method()) == 'post' && isset($request->password)){
			if($request->password != $link->password){
				return redirect()->back()->with('error',"Password is wrong");
			}
		}
		if(strtolower($request->method()) == 'post'){
			$validatedData = $request->validate([
			'password' => 'required'
			], [
			'password.required' => 'Password is required',
			]);

		}


    	if($link->track_me){
	    	$click = new Click();
	    	$click->links_id = $link->id;

	    	$browser = new Browser();

	    	$click->is_first_click = 0;

	    	$click->ip = get_ip();

	    	try {
				$geo_data = GeoLocation::geolocate_ip( $click->ip, true );
				$country = $geo_data['country'];

			} catch ( \Exception $e ) {
				$country = null;
			}

			$click->country = $country;

			$click->referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
			$click->uri     = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;

			$click->user_agent = $browser->getUserAgent();

			$click->browser_type    = $browser->getBrowser();
			$click->browser_version = $browser->getVersion();
			$click->host            = gethostbyaddr( $click->ip );

			$click->is_robot = $browser->isRobot();
			$click->os       = $browser->getPlatform();

			$device = 'Desktop';
			if ( $browser->isMobile() ) {
				$device = 'Mobile';
			} elseif ( $browser->isTablet() ) {
				$device = 'Tablet';
			}

			$click->device = $device;

			// Set First Click
			$cookie_name        = 'dropthis_click_' . $click->links_id;
			$cookie_expire_time = time() + 60 * 60 * 24 * 30; // Expire in 30 days
			if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
				setcookie( $cookie_name, $slug, $cookie_expire_time, '/' );
				$click->is_first_click = 1;
			}

			// Set Visitor Cookie
			$visitor_cookie             = 'dropclick_visitor';
			$visitor_cookie_expire_time = time() + 60 * 60 * 24 * 365; // Expire in 1 year
			if ( ! isset( $_COOKIE[ $visitor_cookie ] ) ) {
				$click->visitor_id = uniqid();
				setcookie( $visitor_cookie, $click->visitor_id, $visitor_cookie_expire_time, '/' );
			} else {
				$click->visitor_id = $_COOKIE[ $visitor_cookie ];
			}

			$click->save();
		}

		// Handle Params Forwarding
    	$params = $_GET;
		if ( !empty($link->params_forwarding) && !empty($params) && is_array($params) ) {

			$param_string = '';

			$params = explode( '?', $_SERVER['REQUEST_URI'] );

			if ( isset( $params[1] ) ) {
				$param_string = ( preg_match( '#\?#', $link->url ) ? '&' : '?' ) . $params[1];
			}

			$param_string = preg_replace( array( '#%5B#i', '#%5D#i' ), array( '[', ']' ), $param_string );

			// $param_string = apply_filters( 'kc_us_redirect_params', $param_string );

			$link->url .= $param_string;
		}

		// Handle Nofollow
		if ( ! empty( $link->nofollow ) ) {
			$tags[] = 'noindex';
			$tags[] = 'nofollow';
		}

		// Handle Sponsored
		if ( ! empty( $link->sponsored ) ) {
			$tags[] = 'sponsored';
		}

		if ( ! empty( $tags ) ) {
			header( 'X-Robots-Tag: ' . implode( ', ', $tags ), true );
		}

		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );
		header( 'Expires: Mon, 10 Oct 1975 08:09:15 GMT' );


		// redirect to target url...
		return Redirect::to($link->url, $link->redirect_type);
    }
}
