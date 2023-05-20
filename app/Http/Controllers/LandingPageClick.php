<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReleaseClick;
use App\Models\Release;
use App\Models\Browser;
use App\Models\ReleasePlatform;
use App\Models\Store;
use App\Models\GeoLocation;
use Auth;
use File;
use Illuminate\Support\Facades\Redirect;

class LandingPageClick extends Controller {
    
    public function LandingPage($key = ''){
        $release = Release::with('platform.getStore')->where('slug',$key)->first();
        
        if(empty($release)){
            $error = "Invalid url detected";
            return response(view('errors.404', compact('error')), 404);
        }
        $path = public_path('music-label');
        $files = File::allFiles($path);
        
        return view('pages.release.landing-page',['release'=>$release,'files'=>$files]);
    }

    public function click($id,$platform_id,$musicPlatForm,$slug){
        $release = Release::where(['slug' => $slug,'id'=>$id])->first();
        $platFormUrl = ReleasePlatform::where(['id' =>$platform_id])->first();
        
        if(empty($release) || $release == null){
            $error = "Sorry landing doesn't exist";
			return response(view('errors.404', compact('error')), 404);
        }
        $releaseClick = new ReleaseClick();
        $releaseClick->release_id = $release->id;
        $browser = new Browser();

        $releaseClick->is_first_click = 0;
	    $releaseClick->ip = get_ip();

        try {
            $geo_data = GeoLocation::geolocate_ip( $releaseClick->ip, true );
            $country = $geo_data['country'];

        } catch ( \Exception $e ) {
            $country = null;
        }
        $releaseClick->country = $country;

		$releaseClick->referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
		$releaseClick->uri     = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
        $releaseClick->user_agent = $browser->getUserAgent();
        $releaseClick->browser_type    = $browser->getBrowser();
        $releaseClick->browser_version = $browser->getVersion();
        $releaseClick->host            = gethostbyaddr( $releaseClick->ip );
        $releaseClick->is_robot = $browser->isRobot();
		$releaseClick->os       = $browser->getPlatform();
        $device = 'Desktop';
        if ( $browser->isMobile() ) {
            $device = 'Mobile';
        } elseif ( $browser->isTablet() ) {
            $device = 'Tablet';
        }
        $releaseClick->device = $device;

        // Set First Click
        $cookie_name        = 'dropthis_release_click_' . $releaseClick->id;
        $cookie_expire_time = time() + 60 * 60 * 24 * 30; // Expire in 30 days
        if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
            setcookie( $cookie_name, $slug, $cookie_expire_time, '/' );
            $releaseClick->is_first_click = 1;
        }
        // Set Visitor Cookie
        $visitor_cookie             = 'dropclick_release_visitor';
        $visitor_cookie_expire_time = time() + 60 * 60 * 24 * 365; // Expire in 1 year
        if ( ! isset( $_COOKIE[ $visitor_cookie ] ) ) {
            $releaseClick->visitor_id = uniqid();
            setcookie( $visitor_cookie, $releaseClick->visitor_id, $visitor_cookie_expire_time, '/' );
        } else {
            $releaseClick->visitor_id = $_COOKIE[ $visitor_cookie ];
        }
        if(isset(Auth::user()->id)){
            $authDetails = Auth::user();
            $releaseClick->user_id  = $authDetails->id;
        }
        
        $path = public_path('music-label');
        $files = File::allFiles($path);

        $store = Store::where('id',$musicPlatForm)->first();
        if(!empty($platFormUrl->url)){
            $url = $platFormUrl->url;
        }elseif(!empty($platFormUrl->track_id)){
            $url = $store->base_url.'/'.$platFormUrl->track_id;
        }
        
        $releaseClick->music_platform = $store->dark_logo;
        $releaseClick->platform_id = $platform_id;
        $releaseClick->save();
        
        return redirect($url);
    }
}
