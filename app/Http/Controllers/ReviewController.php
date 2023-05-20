<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use App\Models\Campaign;
use App\Models\CampaignTracks;
use App\Models\EmailGroup;
use App\Models\DownloadMapping;
use App\Models\User;
use App\Models\ReviewMapping;
use App\Models\CampaignClicks;
use App\Models\Browser;
use App\Models\GeoLocation;
use App\Models\Feedback;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index($id,$pass_key='',Request $request){
        $_emailGroup = EmailGroup::Where('pass_key','=',$pass_key)->first();
        $emailGroup = isset($_emailGroup) && $_emailGroup->pass_key ? $_emailGroup->pass_key : "not Found";
        $userPath = User::$userProfilePath;
        $_campaigns = Campaign::with('getTrack','userDetails')->where('id','=',$id);
        if(isset($_emailGroup)){
            $_campaigns->where('email_group','=',$_emailGroup->group);
        }
        $campaigns = $_campaigns->first();

        
        if(isset(Auth::user()->id)){
            $authDetails = Auth::user();
        }
        $feedbackAlreadyGave = true;
        
        $_reviewFeedbackMapping = ReviewMapping::where('campaign_id','=',$campaigns->id);
        $_feedback = Feedback::where('campaign_id','=',$campaigns->id);
        
        if(isset($authDetails)){
            $_reviewFeedbackMapping->where('user_id','=',$authDetails->id);
            $_feedback->where('user_id','=',$authDetails->id);
        }elseif(isset($_emailGroup)){
            $_reviewFeedbackMapping->where('email','=',$_emailGroup->email);
            $_feedback->where('email','=',$_emailGroup->email);
        }
        
        $feedback = $_feedback->first();
        $reviewFeedbackMapping = $_reviewFeedbackMapping->first();
        
        if(empty($reviewFeedbackMapping) && allowFeedback($campaigns->id,$pass_key)){
            $feedbackAlreadyGave = true;
        }else{
            $feedbackAlreadyGave = false;
        }
        
        // if(isset($authDetails)){
        //     $_campaigns
        // }elseif(isset($emailGroup)){
        //     $_campaigns->where('email_group','=',$_emailGroup->group);
        // }
        
        //$campaigns = $_campaigns->first();
        
        $authIdValidate = false;
        $authRoleValidate = false;
        $authDetails = null;
        $authAdminRoleValidate = false;
        
        if(isset(Auth::user()->id)){
            $authDetails = Auth::user();
            if(empty($campaigns)){
                return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
            }
            $authIdValidate = $campaigns->user_id != $authDetails->id;
            $authRoleValidate = $authDetails->user_role == USER_ROLE_USER;
            $authAdminRoleValidate = $authDetails->user_role != USER_ROLE_ADMIN;
        }
        
        if(empty($campaigns)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($authIdValidate && $authRoleValidate){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($pass_key != $emailGroup && !isset($authDetails)){
            return redirect()->route('login')->with('error', "Sorry campaign doesn't exit");
        }
        
        $_download = DownloadMapping::where('campaign_id','=',$id);
        if(isset($authDetails)){
            $_download->where('user_id','=',$authDetails->id);
        }elseif(isset($emailGroup)){
            $_download->where('email','=',$_emailGroup->email);
        }
        $downloaded = $_download->first();

        // $downloaded = DownloadMapping::where([
        //     ['user_id','=',$authDetails->id],
        //     ['campaign_id','=',$id]
        //     ])->first();
        if(isset($campaigns->expire_link_once_downloaded) && $campaigns->expire_link_once_downloaded == 1 && $authAdminRoleValidate){
            if(!empty($downloaded)){                
                $error = "Link is expired";
                return response(view('errors.404', compact('error')), 404);
            }
        }
        
        $track = CampaignTracks::where('campaign_id','=',$id)->first();
        $tranceLinks = CampaignTracks::where('campaign_id','=',$id)->get();
        $campaignPath = Campaign::$campaignPath.$campaigns->id;
        $userProfilePath = isset($campaigns->userDetails->bg_image) ? User::$userProfilePath.$campaigns->userDetails->id."/".$campaigns->userDetails->bg_image : null;
        $userBgColor = isset($campaigns->userDetails->bg_color) ? $campaigns->userDetails->bg_color : null;
        $_reviewFeedbackMapping = ReviewMapping::where('campaign_id','=',$id);
        if(isset($authDetails)){
            $_reviewFeedbackMapping->where('user_id','=',$authDetails->id);
        }elseif(isset($emailGroup)){
            $_reviewFeedbackMapping->where('email','=',$_emailGroup->email);
        }
        $reviewFeedbackMapping = $_reviewFeedbackMapping->first();
        // $reviewFeedbackMapping = ReviewMapping::where([
        //     ['campaign_id','=',$id],
        //     ['user_id','=',$authDetails->id]
        //     ])->first();
        if(isset($_GET['feedback'])){
            $campaignStatus = "complete";
        }elseif(isset($_GET['status'])){
            $campaignStatus = "changed";
        }

        if(!isset($campaignStatus)){
            // Campaign Click Records
            $campaignClick = new CampaignClicks();
            $campaignClick->campaign_id = $id;
            $campaignClick->user_id = isset($authDetails) ? $authDetails->id : null;
            $campaignClick->email = isset($_emailGroup) ? $_emailGroup->email : null;
            $browser = new Browser();

            $campaignClick->is_first_click = 0;

            $campaignClick->ip = get_ip();
            try {
                $geo_data = GeoLocation::geolocate_ip( $campaignClick->ip, true );
                $country = $geo_data['country'];

            } catch ( \Exception $e ) {
                $country = null;
            }

            $campaignClick->country = $country;

            $campaignClick->referer = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
            $campaignClick->uri     = !empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null;
            $campaignClick->user_agent = $browser->getUserAgent();

            $campaignClick->browser_type    = $browser->getBrowser();
            $campaignClick->browser_version = $browser->getVersion();
            $campaignClick->host            = gethostbyaddr( $campaignClick->ip );

            $campaignClick->is_robot = $browser->isRobot();
            $campaignClick->os       = $browser->getPlatform();

            $device = 'Desktop';
            if ( $browser->isMobile() ) {
                $device = 'Mobile';
            } elseif ( $browser->isTablet() ) {
                $device = 'Tablet';
            }

            $campaignClick->device = $device;
            $slug = route('campaigns.review',['id'=>$id]);
            $cookie_name        = 'campaign_click_' . $id;
            $cookie_expire_time = time() + 60 * 60 * 24 * 30; // Expire in 30 days
            if ( ! isset( $_COOKIE[ $cookie_name ] ) ) {
                setcookie( $cookie_name, $slug, $cookie_expire_time, '/' );
                $campaignClick->is_first_click = 1;
            }

            // Set Visitor Cookie
            $visitor_cookie             = 'dropclick_campaign_visitor';
            $visitor_cookie_expire_time = time() + 60 * 60 * 24 * 365; // Expire in 1 year
            if ( ! isset( $_COOKIE[ $visitor_cookie ] ) ) {
                $campaignClick->visitor_id = uniqid();
                setcookie( $visitor_cookie, $campaignClick->visitor_id, $visitor_cookie_expire_time, '/' );
            } else {
                $campaignClick->visitor_id = $_COOKIE[ $visitor_cookie ];
            }
            $campaignClick->save();
        }
        if(empty($track)){
            $error = "Sorry artise not uploaded audio";
            return response(view('errors.404', compact('error')), 404);
        }
        return view('pages.campaigns.review',[
            'campaigns'=>$campaigns,
            'campaignPath' => $campaignPath,
            'track' =>$track,
            'tranceLinks' => $tranceLinks,
            'authDetails' => $authDetails,
            'reviewFeedbackMapping' => $reviewFeedbackMapping,
            'userProfilePath' => $userProfilePath,
            'pass_key' => $pass_key,
            'emailGroup' =>$_emailGroup,
            'userBgColor' => $userBgColor,
            'userPath' => $userPath,
            'feedbackAlreadyGave' => $feedbackAlreadyGave,
            'feedback' => $feedback
        ]);
    }

    public function feedback(Request $request){
        $_emailGroup = EmailGroup::Where('pass_key','=',$request->pass_key)->first();
        
        if(isset(Auth::user()->id)){
            $authDetails = Auth::user();
        }
        
        $feedbackMapping =  new ReviewMapping();

        $_reviewFeedbackMapping = ReviewMapping::where('campaign_id','=',$request->campaignId);
        if(isset($authDetails)){
            $_reviewFeedbackMapping->where('user_id','=',$authDetails->id);
        }elseif(isset($_emailGroup)){
            $_reviewFeedbackMapping->where('email','=',$_emailGroup->email);
        }
        $reviewFeedbackMapping = $_reviewFeedbackMapping->first();
       // If allowFeedback true and Review feedback is empty then it will allow to submit feedback
        if(empty($reviewFeedbackMapping) && allowFeedback($request->campaignId,$request->pass_key)){
            // checking the button request if send feedback then it will store true else false
            if($request->sendFeedback == 'Send Feedback'){
                $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email',
                'supporting' => 'required',
                'dj_quote' => 'required',
                //'best_mix' => 'required',
                'rating' => 'required',
                ],[
                'name.required' => 'Name is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email is not valid',
                'supporting.required' => 'Supporting is required',
                'dj_quote.required' => 'DJ Quote is required',
                //'best_mix.required' => 'Best Mix is required',
                'rating.image' => 'Rating is required',
                ]);
                $ip = get_ip();
                try {
                    $geo_data = GeoLocation::geolocate_ip($ip, true );
                    $country = $geo_data['country'];

                } catch ( \Exception $e ) {
                    $country = null;
                }
                $feedback = new Feedback();
                $feedback->user_id = isset($authDetails) ? $authDetails->id : null;
                $feedback->campaign_id = $request->campaignId;
                $feedback->name = $request->name;
                $feedback->email = $request->email;
                $feedback->supporting = $request->supporting;
                $feedback->dj_quote = $request->dj_quote;
                $feedback->best_mix = $request->best_mix;
                $feedback->rating = $request->rating;
                $feedback->ip = $ip;
                $feedback->country = $country;
                $feedback->save();     

                Campaign::where('id','=',$request->campaignId)->update(['campaign_status'=>CAMPAIGN_STATUS_VIEW_FEEDBACK]);

                $feedbackMapping->campaign_id  =  $request->campaignId;
                $feedbackMapping->user_id =  isset($authDetails) ? $authDetails->id : null;
                $feedbackMapping->email =  $request->email;
                $feedbackMapping->feedback =  1;
                $feedbackMapping->save();
                return redirect()->route('campaigns.review',['id'=>$request->campaignId,'pass_key'=>$request->pass_key,'feedback'=>'complete'])->with('status', 'Review submited sucessfully..!');

            } else if($request->notForMe == "Not For Me"){            
                if(isset($authDetails)){
                    User::where('id','=',$authDetails->id)->update([
                        'can_submit_feedbacks' => 0
                    ]);
                }
                $ip = get_ip();
                try {
                    $geo_data = GeoLocation::geolocate_ip($ip, true );
                    $country = $geo_data['country'];

                } catch ( \Exception $e ) {
                    $country = null;
                }
                $feedback = new Feedback();
                $feedback->user_id = isset($authDetails) ? $authDetails->id : null;
                $feedback->campaign_id = $request->campaignId;
                $feedback->name = $request->name;
                $feedback->email = $request->email;
                $feedback->supporting = 0;
                $feedback->dj_quote = "Not For Me";
                $feedback->best_mix = null;
                $feedback->rating = null;
                $feedback->ip = $ip;
                $feedback->country = $country;
                $feedback->save(); 

                $feedbackMapping->campaign_id =  $request->campaignId;
                $feedbackMapping->user_id =  isset($authDetails) ? $authDetails->id : null;
                $feedbackMapping->email =  $request->email;
                $feedbackMapping->feedback = 0;
                $feedbackMapping->save();
                return redirect()->back()->with('status', 'Review submited successfully..!');
            } else {
                return redirect()->back()->with('error', 'Sorry, you cannot give your feedback.');
            }

        }
        // else user already gave the feedback not allow to again give feedback
        else{
            return redirect()->back()->with('error', 'Sorry, you already gave your feedback.');
        }
    }

    public function zip($id,$pass_key=''){
        $_emailGroup = EmailGroup::Where('pass_key','=',$pass_key)->first();
        $tracks = CampaignTracks::where('campaign_id','=',$id)->first();
        $campaign = Campaign::where('id','=',$id)->first();
       
        
        $emailGroup = isset($_emailGroup) && $_emailGroup->pass_key ? $_emailGroup->pass_key : "not Found";
        $authIdValidate = false;
        $authRoleValidate = false;
        $authAdminRoleValidate = false;
        if(isset(Auth::user()->id)){
            $authDetails = Auth::user();
            $authIdValidate = $campaign->user_id != $authDetails->id;
            $authRoleValidate = $authDetails->user_role == USER_ROLE_USER;
            $authAdminRoleValidate = $authDetails->user_role != USER_ROLE_ADMIN;
        }
        $_reviewFeedbackMapping = ReviewMapping::where('campaign_id','=',$tracks->campaign_id);
        if(isset($authDetails)){
            $_reviewFeedbackMapping->where('user_id','=',$authDetails->id);
        }elseif(isset($_emailGroup)){
            $_reviewFeedbackMapping->where('email','=',$_emailGroup->email);
        }
        $reviewFeedbackMapping = $_reviewFeedbackMapping->first();
        // $reviewFeedbackMapping = ReviewMapping::where([
        //     ['campaign_id','=',$tracks->campaign_id],
        //     ['user_id','=',$authDetails->id]
        //     ])->first();
        

        if(empty($campaign)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($authIdValidate && $authRoleValidate){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($pass_key != $emailGroup && !isset($authDetails)){
            return redirect()->route('login')->with('error', "Sorry campaign doesn't exit");
        }
        $_downloaded = DownloadMapping::where('campaign_id','=',$id);
        if(isset($authDetails)){
            $_downloaded->where('user_id','=',$authDetails->id);
        }elseif(isset($_emailGroup)){
            $_downloaded->where('email','=',$_emailGroup->email);
        }
        $downloaded = $_downloaded->first();
        $download = new DownloadMapping();
        if(isset($tracks->id) && allowFeedback($tracks->campaign_id,$pass_key) && $reviewFeedbackMapping  || $campaign->leave_rating_and_comment == 0){
            if($campaign->expire_link_once_downloaded == 1){
                if(!empty($downloaded)){
                    $error = "Link is expired";
                    return response(view('errors.404', compact('error')), 404);
                }else{                
                    $download->campaign_id = $id;
                    $download->user_id = isset($authDetails) ? $authDetails->id : null;
                    $download->email = isset($_emailGroup) ? $_emailGroup->email : null;
                    $download->save();                
                }
            }

            $zip = new ZipArchive;
            $campaignPath = Campaign::$campaignPath.$tracks->campaign_id;
            $fileName = str_replace(" ","-",$tracks->track).'['.str_replace(" ","-",$campaign->label).'].zip';

            $filePath = storage_path('app/public/uploads/campaigns/'.$tracks->campaign_id.'/'.$fileName);
            if (!file_exists(storage_path('app/public/uploads/campaigns/'.$tracks->campaign_id))) {
                return redirect()->back()->with('error', "Sorry path doesn't exist.");
            }
            if ($zip->open($filePath, ZipArchive::CREATE) === TRUE){
                $campaignIdWiseFolder = File::files(storage_path('app/public/uploads/campaigns/'.$tracks->campaign_id));

                foreach ($campaignIdWiseFolder as $key => $value) {
                    $relativeNameInZipFile = basename($value);
                    $zip->addFile($value, $relativeNameInZipFile);
                }
                $zip->close();
            }
            return response()->download($filePath)->deleteFileAfterSend(true);
        }
        return redirect()->back()->with('error', "Sorry you Can't download zip file. First Submit the feedback form than try to download.");
    }

    public function downloadAudioFile($id,$audioExtension,$pass_key=''){
        $_emailGroup = EmailGroup::Where('pass_key','=',$pass_key)->first();
        if(isset(Auth::user()->id)){
            $authDetails = Auth::user();
        }
        $tracks = CampaignTracks::where('id','=',$id)->first();
        $_reviewFeedbackMapping = ReviewMapping::where('campaign_id','=',$tracks->campaign_id);
        if(isset($authDetails)){
            $_reviewFeedbackMapping->where('user_id','=',$authDetails->id);
        }elseif(isset($_emailGroup)){
            $_reviewFeedbackMapping->where('email','=',$_emailGroup->email);
        }
        $reviewFeedbackMapping = $_reviewFeedbackMapping->first();
        if(allowFeedback($tracks->campaign_id,$pass_key) && $reviewFeedbackMapping){
            if(!empty($tracks->mp3_audio)){
                $audioExtension .= "_audio";
                $fileName = $tracks->$audioExtension;
                $filePath = storage_path('app/public/uploads/campaigns/'.$tracks->campaign_id.'/'.$fileName);
                if (!file_exists($filePath)) {
                    return redirect()->back()->with('error', "Sorry path doesn't exist.");
                }
            }else{
                return redirect()->back()->with('error', "Sorry audio file doesn't exist.");
            }
        }else{
            return redirect()->back()->with('error', "Sorry you Can't download audio file. First Submit the feedback form than try to download.");
        }
        return response()->download($filePath);
    }

    public function unsubscription($pass_key){
        EmailGroup::where('pass_key','=',$pass_key)->update(['unsubscription'=>1]);
        return redirect()->back();
    }
}
