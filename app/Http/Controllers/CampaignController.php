<?php

namespace App\Http\Controllers;

use App\Models\LabelSetting;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignTracks;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use File;
use Illuminate\Support\Facades\Storage;
use Auth;
use App\Models\Feedback;
use App\Models\User;
use App\Models\ReviewMapping;
use Owenoj\LaravelGetId3\GetId3;

use App\Models\CampaignClicks;
use App\Models\Browser;
use App\Models\GeoLocation;
use Carbon\Carbon;
use App\Models\Country;
use App\Models\DownloadMapping;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailGroup;

class CampaignController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    
    protected function errorBag() {
        return $this->validatesRequestErrorBag ?: 'default';
    }
    protected $validatesRequestErrorBag;

    public function index(){        
        // dd(Carbon::now()->format('Y-m-d h:i:s'));
        $authDetails = Auth::user();
        $campaignsData = Campaign::with('getTrack','userDetails');
        
        if($authDetails->user_role == USER_ROLE_USER){
            $campaignsData->where('user_id','=',$authDetails->id);
        }
        $campaigns = $campaignsData->get();
        $campaignPath = Campaign::$campaignPath;
        return view('pages.campaigns.list',['campaigns'=>$campaigns,'campaignPath'=>$campaignPath,'campaignTracks' => []]);
    }

    public function show(){
        $authDetails = Auth::user();
        if($authDetails->credits > 0){
            $flag = true;
        }elseif(($authDetails->new_user == 1 && $authDetails->credits == 0) || $authDetails->user_role == USER_ROLE_ADMIN){
            $flag = true;
        }else{
            $flag = false;
        }
        $groups = EmailGroup::groupby('group')->select('group','id')->get();
        $labelSettings = LabelSetting::where('user_id','=',$authDetails->id)->get();
        if($flag){
            return view('pages.campaigns.form',['groups'=>$groups,'authDetails'=>$authDetails,'labelSettings' => $labelSettings]);
        }else{
            return redirect()->route('billing');
        }
    }

    public function save(Request $request){
        $todayDate = date('m/d/Y');
        $authDetails = Auth::user();
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        // dd($request->all(),$request->mp3_audio[0]->getClientOriginalExtension());
        $validatedData = $request->validate([
		    'label' => 'required',
		    'website' => ['required','url'],
		    'release_number' => 'required',
            'cover_artwork' => 'required|image|mimes:jpeg,png,jpg',            
            'track.*' => 'required',
            'track_genre.*' => 'required',
            // 'mp3_audio.*' => 'nullable|mimes:application/octet-stream,mp3|max:'.$max,
            'mp3.*' =>  'required',
            // 'wav_audio.*' => 'nullable|mimes:application/octet-stream,wav|max:'.$max,
            'wav.*' => 'required',
            'release_date' =>  'required',
            'promo_sendout' =>  'required',
		],[
		    'label.required' => 'Label is required',
		    'website.required' => 'Website URL is required',
		    'target_url.url' => 'Website URL is not valid',
		    'release_number' => 'Release number is required',
            'cover_artwork.required' => 'Cover artwork is required',
            'cover_artwork.image' => 'Cover artwork image',
            'cover_artwork.mimes' => 'Cover artwork extension must be .jped, .png, .jpg',
            'mp3.*.required' => 'MP3 is reqiured',
            'wav.*.required' => 'WAV is required',
            'mp3_audio.*.required' => 'MP3 file is required',
            // 'mp3_audio.*.mimes' => 'File must be mp3',
            'mp3_audio.*.max' => 'The mp3 file must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'wav_audio.*.mimes' => 'File must be wav',
            'wav_audio.*.max' =>  'The wav file must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'wav_audio.*.required' => 'WAV file is required',
            'track.*.required' => "Track is required",
            'track_genre.*.required' => 'Track genre is required',
            'release_date.required' => "Release date is required",
            'promo_sendout.required' => "Promo sendout date is required"
		]);
        $tracks = $request->track;
        $track_genres = $request->track_genre;
        $mp3_peak = $request->peak_mp3_audio;
        $wav_peak = $request->peak_wav_audio;
        $mp3_audios = $request->file('mp3_audio');
        $wav_audios = $request->file('wav_audio');
        $errors = array();
        if(!empty($tracks)){
            for($i = 0; $i < count($tracks); $i++){
                if(isset($mp3_audios[$i])){
                    if($mp3_audios[$i]->getClientOriginalExtension() != 'mp3'){
                        $errors['mp3_audio.'.$i] = "File must be mp3";
                    }
                    $mp3Track = new GetId3($mp3_audios[$i]);
                    $bitRate = 0;
                    if(isset($mp3Track->extractInfo()['bitrate'])){
                        $bitRate = floor($mp3Track->extractInfo()['bitrate']/1000);
                    }
                    if($bitRate != 320){
                        $errors['mp3_audio.'.$i] = "Please Upload 320kbit";
                    }
                }
                if(isset($wav_audios[$i])){
                    if($wav_audios[$i]->getClientOriginalExtension() != 'wav'){
                        $errors['wav_audio.'.$i] = "File must be wav";
                    }
                    $wavTrack = new GetId3($wav_audios[$i]);
                    $bit = $wavTrack->extractInfo()['audio']['bits_per_sample'];
                    $khz = $wavTrack->extractInfo()['audio']['sample_rate']/1000;

                    $bitStatic = 16;
                    $khzStatic = 44.1;
                    if($bit != $bitStatic){
                        $errors['wav_audio.'.$i] = "Please Upload 16bit 44.1KHz WAV file";
                    }elseif($khz != $khzStatic){
                        $errors['wav_audio.'.$i] = "Please Upload 16bit 44.1KHz WAV file";
                    }
                }
            }
            if(!empty($errors)){
                return redirect()->back()->withInput()->withErrors( $errors, $this->errorBag());
            }
        }
        $campaign = new Campaign();
        
        $campaign->label = $request->label;
        $campaign->website = $request->website;
        $campaign->release_number = $request->release_number;
        $campaign->description = $request->description;
        $campaign->leave_rating_and_comment = !empty($request->leave_rating_and_comment) ? 1 : 0;

        $releaseDate = date_create($request->release_date);
        $campaign->release_date = date_format($releaseDate,"Y-m-d");
        $promoSendout = date_create($request->promo_sendout);
        $campaign->promo_sendout = date_format($promoSendout,"Y-m-d");
        $campaign->expire_link_once_downloaded = !empty($request->expire_link_once_downloaded) ? 1 : 0;
        $campaign->status = !empty($request->status) ? 1 : 0;
        $campaign->user_id = $authDetails->id;
        $campaign->email_group = $request->email_group;
        $campaign->label_id = $request->label_id;
        // if ($cover_artwork = $request->file('cover_artwork')) {
        if ($request->hasFile('cover_artwork')) {
            $cover_artwork = $request->file('cover_artwork');
            $artWorkfilename = Str::random(20).".".$cover_artwork->getClientOriginalExtension();
            //uploadFileToStorage($cover_artwork, Campaign::$artwork."/".$artWorkfilename, null, 600, 600);

            // $destinationPath = 'artwork/';
            // $artworkFileName = date('YmdHis') . "." . $cover_artwork->getClientOriginalExtension();
            // $cover_artwork->move($destinationPath, $artworkFileName);
            $campaign->cover_artwork= $artWorkfilename;
         }
         $campaign->campaign_status = CAMPAIGN_STATUS_REVIEW;
        $campaign->save();
        uploadFileToStorage($cover_artwork, Campaign::$campaignPath.$campaign->id."/".$artWorkfilename, null, 600, 600);
        
        if(!empty($tracks)){
            for($i = 0; $i < count($tracks); $i++){
                $campaignTracks = new CampaignTracks();

                $campaignTracks->track = $tracks[$i];
                $campaignTracks->track_genre = $track_genres[$i]; 
                $campaignTracks->mp3_peak = $mp3_peak[$i]; 
                $campaignTracks->wav_peak = $wav_peak[$i]; 
                $campaignTracks->campaign_id = $campaign->id;
                $fileName = str_replace(" ","-",$tracks[$i])."[".str_replace(" ","-",$campaign->label)."]";
                
                if(isset($mp3_audios[$i])){
                    $mp3_audio = $mp3_audios[$i];
                    $mp3filename = $fileName.".".$mp3_audio->getClientOriginalExtension();
                    $mp3Track = new GetId3($mp3_audio);
                    $campaignTracks->mp3_time = $mp3Track->extractInfo()['playtime_string'];
                    // mp3FileToStorage($mp3_audio, CampaignTracks::$mp3Audio."/".$mp3filename);
                    mp3FileToStorage($mp3_audio, Campaign::$campaignPath.$campaign->id."/",$mp3filename);
                    $campaignTracks->mp3_audio= $mp3filename;
                }
                
                if(isset($wav_audios[$i])){
                    $wav_audio = $wav_audios[$i];
                    $wavfilename = $fileName.".".$wav_audio->getClientOriginalExtension();
                    $wavTrack = new GetId3($wav_audio);
                    $campaignTracks->wav_time = $wavTrack->extractInfo()['playtime_string'];
                    // wavFileToStorage($wav_audio, CampaignTracks::$wavAudio."/".$wavfilename);
                    wavFileToStorage($wav_audio, Campaign::$campaignPath.$campaign->id."/",$wavfilename);
                    $campaignTracks->wav_audio= $wavfilename;
                }
                $campaignTracks->save();
            }
        }
        if(isset($authDetails->credits) && $authDetails->credits > 0){
            $credits = $authDetails->credits - 1;
            User::where('id','=',$authDetails->id)->update(['credits'=>$credits,'new_user'=>0]);
        }elseif($authDetails->new_user == 1){
            User::where('id','=',$authDetails->id)->update(['new_user'=>0]);
        }
        return redirect()->route('campaigns')->with('status', 'Promo Campaign Saved Sucessfully..!'); 
    }

    public function delete($id){
        $authDetails = Auth::user();
        $campaigns = Campaign::where('id',$id)->first();
        if(empty($campaigns)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($campaigns->user_id != $authDetails->id && $authDetails->user_role == USER_ROLE_USER){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }
        $campaignPath = Campaign::$campaignPath.$id;        
        $campaignTracks = CampaignTracks::where('campaign_id',$id)->get();
        removeDirectoryFromStorage($campaignPath);
        $campaignTracks = CampaignTracks::where('campaign_id',$id)->delete();
        $reviewMapping = ReviewMapping::where('campaign_id',$id)->delete();
        $feedback = Feedback::where('campaign_id',$id)->delete();
        $campaignClicks = CampaignClicks::where('campaign_id','=',$id)->delete();
        DownloadMapping::where('campaign_id','=',$id)->delete();
        $campaigns = Campaign::where('id',$id)->delete();
        return redirect()->route('campaigns')->with('status', 'Promo Campaign deleted Sucessfully..!'); 
    }

    public function edit($id){
        $authDetails = Auth::user();
        $campaigns = Campaign::where('id',$id)->first();
        $groups = EmailGroup::groupby('group')->select('group','id')->get();
        $labelSettings = LabelSetting::where('user_id','=',$authDetails->id)->get();
        if(empty($campaigns)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($campaigns->user_id != $authDetails->id && $authDetails->user_role == USER_ROLE_USER){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }
        $campaignTracks = CampaignTracks::where('campaign_id',$id)->get();
        $campaignPath = Campaign::$campaignPath.$campaigns->id;
        return view('pages.campaigns.form',[
            'campaigns'=>$campaigns,
            'campaignTracks'=>$campaignTracks,
            'campaignPath'=>$campaignPath,
            'groups'=>$groups,
            'labelSettings' => $labelSettings
        ]);
    }

    public function update(Request $request){
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        //dd($request->all());
        $validatedData = $request->validate([
		    'label' => 'required',
		    'website' => ['required','url'],
		    'release_number' => 'required',
            'cover_artwork' => 'nullable|image|mimes:jpeg,png,jpg',
            // 'mp3_audio.*' => 'nullable|mimes:application/octet-stream,mp3|max:'.$max,
            // 'wav_audio.*' => 'nullable|mimes:application/octet-stream,wav|max:'.$max,
            'mp3.*' =>  'required',
            'wav.*' => 'required',
            'track.*' => 'required',
            'track_genre.*' => 'required',
            'release_date' =>  'required',
            'promo_sendout' =>  'required',
		],[
		    'label.required' => 'Label is required',
		    'website.required' => 'Website URL is required',
		    'target_url.url' => 'Website URL is not valid',
		    'release_number' => 'Release number is required',
            'cover_artwork.required' => 'Cover artwork is required',
            'cover_artwork.image' => 'Cover artwork image',
            'cover_artwork.mimes' => 'Cover artwork extension must be .jped, .png, .jpg',
            'mp3.*.required' => 'MP3 is reqiured',
            'wav.*.required' => 'WAV is required',
            'mp3_audio.*.mimes' => 'File must be mp3',
            'wav_audio.*.mimes' => 'File must be wav',
            'mp3_audio.*.max' => 'The mp3 file must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'wav_audio.*.max' =>  'The wav file must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'track.*.required' => "Track is required",
            'track_genre.*.required' => 'Track genre is required',
            'release_date.required' => "Release date is required",
            'promo_sendout.required' => "Promo sendout date is required"
		]);
        $tracks = $request->track;
        $track_genres = $request->track_genre;
        $mp3_audios = $request->file('mp3_audio');
        $wav_audios = $request->file('wav_audio');
        $mp3_peak = $request->peak_mp3_audio;
        $wav_peak = $request->peak_wav_audio;
        $old_mp3_peak = $request->old_mp3_peak;
        $old_wav_peak = $request->old_wav_peak;
        $old_mp3_time = $request->old_mp3_time;
        $old_wav_time = $request->old_wav_time;
        $trackid = $request->trackid;
        $oldWavAudio = $request->oldWavAudio;
        $oldMp3Audio = $request->oldMp3Audio;
        
        $errors = array();
        if(!empty($tracks)){
            foreach($tracks as $key => $track){
                if(isset($mp3_audios[$key]) && !empty($mp3_audios[$key])){
                    if($mp3_audios[$key]->getClientOriginalExtension() != 'mp3'){
                        $errors['mp3_audio.'.$key] = "File must be mp3";
                    }
                    $mp3Track = new GetId3($mp3_audios[$key]);
                    $bitRate = floor($mp3Track->extractInfo()['bitrate']/1000);
                    if($bitRate != 320){
                       echo $errors['mp3_audio.'.$key] = "Please Upload 320kbit";
                    }
                }
                if(isset($wav_audios[$key]) && !empty($wav_audios[$key])){

                    if($wav_audios[$key]->getClientOriginalExtension() != 'wav'){
                        $errors['wav_audio.'.$key] = "File must be wav";
                    }

                    $wavTrack = new GetId3($wav_audios[$key]);
                    $bit = $wavTrack->extractInfo()['audio']['bits_per_sample'];
                    $khz = $wavTrack->extractInfo()['audio']['sample_rate']/1000;

                    $bitStatic = 16;
                    $khzStatic = 44.1;

                    if($bit != $bitStatic){
                        $errors['wav_audio.'.$key] = "Please Upload 16bit 44.1KHz WAV file";
                    }elseif($khz != $khzStatic){
                        $errors['wav_audio.'.$key] = "Please Upload 16bit 44.1KHz WAV file";
                    }
                }
            }
            if(!empty($errors)){
                return redirect()->back()->withInput()->withErrors( $errors, $this->errorBag());
            }
        }       
        if(empty($tracks)){
            echo $errors['track'] = "Track is reqired";
            echo $errors['track_genre'] = "Track Genre is reqired";
            echo $errors['mp3_audio'] = "MP3 is reqired";
            echo $errors['wav_audio'] = "WAV is reqired";
            if(!empty($errors)){
                return redirect()->back()->withInput()->withErrors( $errors, $this->errorBag());
            }
        }
        $todayDate = date('m/d/Y');
        $id = $request->id;

        $releaseDate = date_create($request->release_date);
        $promoSendout = date_create($request->promo_sendout);
        $campaignPath = Campaign::$campaignPath.$request->id."/";
        if(isset($request->cover_artwork)){
            if ($request->hasFile('cover_artwork')) {
                $cover_artwork = $request->file('cover_artwork');
                $artWorkfilename = Str::random(20).".".$cover_artwork->getClientOriginalExtension();
                uploadFileToStorage($cover_artwork,  $campaignPath."/".$artWorkfilename, null, 600, 600);
                $cover_artwork= $artWorkfilename;
                removeFileFromStorage($campaignPath.$request->oldArtwork);
            }
        }else{
            $cover_artwork = $request->oldArtwork;
        }
        $authDetails = Auth::user();
        //echo !empty($request->expire_link_once_downloaded) ? 1 : 0; exit;
            Campaign::where('id','=',$request->id)->update([
                'label' => $request->label,
                'website' => $request->website,
                'release_number' => $request->release_number,
                'description' => $request->description,
                'release_date' => date_format($releaseDate,"Y-m-d"),
                'promo_sendout' => date_format($promoSendout,"Y-m-d"),
                'leave_rating_and_comment' => !empty($request->leave_rating_and_comment) ? 1 : 0,
                'status' => !empty($request->status) ? 1 : 0,
                'cover_artwork' => $cover_artwork,
                'expire_link_once_downloaded' => !empty($request->expire_link_once_downloaded) ? 1 : 0,
                'campaign_status' => CAMPAIGN_STATUS_REVIEW,
                'email_group' => $request->email_group,
                'label_id' => $request->label_id,
                //'user_id' => $authDetails->id
            ]);
        // dd($tracks);
        if(!empty($tracks)){
            foreach($tracks as $key => $track){
                $track = $tracks[$key];
                $track_g = $track_genres[$key];               
                
                $fileName = str_replace(" ","-",$track)."[".str_replace(" ","-",$request->label)."]".Str::random(1);
                if(!empty($trackid[$key])){
                    if(isset($mp3_audios[$key]) && !empty($mp3_audios[$key])){
                        if(isset($oldMp3Audio[$key])){
                            removeFileFromStorage($campaignPath.$oldMp3Audio[$key]);
                        }                        
                        $mp3_audio = $mp3_audios[$key];
                        $mp3filename = $fileName.".".$mp3_audio->getClientOriginalExtension();
                        $mp3Track = new GetId3($mp3_audio);
                        $mp3_time = $mp3Track->extractInfo()['playtime_string'];
                        mp3FileToStorage($mp3_audio, $campaignPath,$mp3filename);
                        $mp3_peaks = $mp3_peak[$key];
                        $mp3AudioFileName = $mp3filename;
                    }else{
                        $mp3_peaks = $old_mp3_peak[$key];
                        $mp3_time = $old_mp3_time[$key];
                        $mp3AudioFileName = $oldMp3Audio[$key];
                    }
                    if(isset($wav_audios[$key]) && !empty($wav_audios[$key])){
                        if(isset($oldWavAudio[$key])){
                            removeFileFromStorage($campaignPath.$oldWavAudio[$key]);
                        }
                        $wav_audio = $wav_audios[$key];
                        $wavfilename = $fileName.".".$wav_audio->getClientOriginalExtension();
                        $wavTrack = new GetId3($wav_audio);
                        $wav_time = $wavTrack->extractInfo()['playtime_string'];
                        $wav_peaks = $wav_peak[$key];
                        wavFileToStorage($wav_audio, $campaignPath."/",$wavfilename);
                        $wavAudioFileName= $wavfilename;
                        
                    }else{
                        $wav_peaks = $old_wav_peak[$key];
                        $wav_time = $old_wav_time[$key];
                        $wavAudioFileName = $oldWavAudio[$key];
                    }
                    CampaignTracks::where('id','=',$trackid[$key])->update([
                        'track' =>$track,
                        'track_genre' => $track_g,
                        'mp3_audio' => $mp3AudioFileName,
                        'wav_audio' => $wavAudioFileName,
                        'mp3_peak' =>$mp3_peaks,
                        'wav_peak' => $wav_peaks,
                        'mp3_time' => $mp3_time,
                        'wav_time' => $wav_time
                    ]);
                }else{
                    $campaignTracks = new CampaignTracks();
                    $campaignTracks->track = $track;
                    $campaignTracks->track_genre = $track_g; 
                    $campaignTracks->campaign_id = $id;
                    // $campaignTracks->mp3_peak = $mp3_peaks;
                    // $campaignTracks->wav_peak = $wav_peaks;
                    if(isset($mp3_audios[$key]) && !empty($mp3_audios[$key])){
                        $mp3_audio = $mp3_audios[$key];
                        $mp3filename = $fileName.".".$mp3_audio->getClientOriginalExtension();
                        mp3FileToStorage($mp3_audio, $campaignPath."/",$mp3filename);
                        $mp3Track = new GetId3($mp3_audio);
                        $campaignTracks->mp3_time = $mp3Track->extractInfo()['playtime_string'];
                    
                        $campaignTracks->mp3_audio= $mp3filename;
                        $campaignTracks->mp3_peak = $mp3_peak[$key];
                    }
                    if(isset($wav_audios[$key]) && !empty($wav_audios[$key])){
                        $wav_audio = $wav_audios[$key];
                        $wavfilename = $fileName.".".$wav_audio->getClientOriginalExtension();
                        wavFileToStorage($wav_audio,$campaignPath."/",$wavfilename);
                        $wavTrack = new GetId3($wav_audio);
                        $campaignTracks->wav_time = $wavTrack->extractInfo()['playtime_string'];
                        //wavFileToStorage($wav_audio, CampaignTracks::$wavAudio."/".$wavfilename);                  
                        $campaignTracks->wav_audio= $wavfilename;
                        $campaignTracks->wav_peak = $wav_peak[$key];
                    }
                    $campaignTracks->save();
                }
            }
        }
        // $route = route('campaigns.review',['id'=>$id]);
        // $msg = "Hello, Campaign has been updated Please Check";
        // // Mail Sending Array
        // $details = [
        //     'msg'=>$msg,
        //     'route'=>$route,
        //     'subject' => 'Campaign Updated'
        // ];
        // // Mail Sending
        // Mail::to('ashishp.brainerhub@gmail.com')->send(new \App\Mail\CampaignMail($details));
        return redirect()->route('campaigns')->with('status', 'Promo Campaign updated Sucessfully..!');
    }
    public function deleteTrack(Request $request){
        $campaignTracks = CampaignTracks::where('id',$request->id)->first();
        $campaignPath = Campaign::$campaignPath.$campaignTracks->campaign_id."/";
        
        $mp3_audio = $campaignTracks->mp3_audio;
        $wav_audio =  $campaignTracks->wav_audio;

        removeFileFromStorage($campaignPath.$mp3_audio);
        removeFileFromStorage($campaignPath.$wav_audio);
        $campaignTracks = CampaignTracks::where('id',$request->id)->delete();
        return "Campaign track deleted successfully";
    }

    public function statistics($id){
        $authDetails = Auth::user();
        $campaign = Campaign::find($id);
        if(empty($campaign)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($campaign->user_id != $authDetails->id && $authDetails->user_role == USER_ROLE_USER){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }
        $campaignPath = Campaign::$campaignPath;
        $coverImage = getFileFromStorage($campaignPath.$campaign->id."/".$campaign->cover_artwork);
        // Getting Campaign Count

        // Getting Campaign Clicks Details
        $campaignClicks = CampaignClicks::where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->get();

        // Getting Track Counts
        $tracks = CampaignTracks::where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->count();

        // Getting Feedbacks
        $feedbacks = Feedback::where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->get();
        // Getting Referers
        $_referers = CampaignClicks::selectRaw('referer, count(*) AS total')
        ->where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->whereNotNull('referer')
        ->groupBy('referer')
        ->orderBy('total', 'desc')
        ->get();

        if($_referers->count() > 16){
            $index = 0;
            $referers = [];
            foreach ($_referers as $referer) {
                if($index < 15){
                    $referers[$index]['referer'] = $referer->referer;
                    $referers[$index++]['total'] = $referer->total;
                } else {
                    if(empty($referers[$index])){
                        $referers[$index]['total'] = 0;
                    }
                    $referers[$index]['referer'] = 'Others';
                    $referers[$index]['total'] += $referer->total;
                }
            }
        } else {
            $referers = $_referers->toArray();
        }

        // get devices
        $_devices = CampaignClicks::selectRaw('device, count(*) AS total')
            ->where('campaign_id','=',$id)
            ->whereNotNull('device')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();

        $devices = [];
        foreach($_devices as $device){
            $devices['name'][] = $device->device;
            $devices['data'][] = $device->total;
        }

        // get browsers
        $_browsers = CampaignClicks::selectRaw('browser_type, count(*) AS total')
            ->where('campaign_id','=',$id)
            ->whereNotNull('browser_type')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();

        $browsers = [];
        foreach($_browsers as $browser){
            $browsers['browser'][] = $browser->browser_type;
            $browsers['total'][] = $browser->total;
        }

        // get platforms
        $_platforms = CampaignClicks::selectRaw('os, count(*) AS total')
            ->where('campaign_id','=',$id)
            ->whereNotNull('os')
            // ->whereMonth('created_at', Carbon::now()->month)
            ->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();

        $platforms = [];
        foreach($_platforms as $platform){
            $platforms['os'][] = $platform->os;
            $platforms['total'][] = $platform->total;
        }

        // month wise total clicks
        $_month_wise_total_clicks = CampaignClicks::selectRaw('count(*) as total, MONTH(created_at) month')
        ->where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();

        $months_wise_total = [];
        for($i = 1; $i <= 12; $i++){
            if(!empty($_month_wise_total_clicks[$i])){
                $months_wise_total[] = $_month_wise_total_clicks[$i];
            } else {
                $months_wise_total[] = 0;
            }
        }

        // month wise unique clicks...
        $months_wise_unique = [];
        $_month_wise_unique_clicks = CampaignClicks::where('is_first_click',1)
        ->where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();

        for($i = 1; $i <= 12; $i++){
            if(!empty($_month_wise_unique_clicks[$i])){
                $months_wise_unique[] = $_month_wise_unique_clicks[$i];
            } else {
                $months_wise_unique[] = 0;
            }
        }

        $countries = [];
        $index = 0;
        $countryTable = [];
        $countryClicks = CampaignClicks::selectRaw('country, count(*) as total')
        ->where('campaign_id','=',$id)
        ->whereNotNull('country')
        // ->whereMonth('created_at', Carbon::now()->month)
        ->groupBy('country')
        ->orderBy('total', 'desc')
        ->get();

        foreach ($countryClicks as $key => $click) {
            $_countries = Country::where('short_code', $click->country)->get();
            foreach ($_countries as $key => $country) {
                $countries[] = array(
                        'latLng' =>array($country->latitude,$country->longitude),
                        'name'=>$country->country,
                        'total' => $click->total
                    );
                if($index < 5){
                    $countryTable[$index]['name'] = $country->country;
                    $countryTable[$index++]['total'] = $click->total;
                } else {
                    if(empty($countryTable[$index])){
                        $countryTable[$index]['total'] = 0;
                    }
                    $countryTable[$index]['name'] = 'Others';
                    $countryTable[$index]['total'] += $click->total;
                }
            }
        }
        
        // Best Mix 
        $bestMixCount =  Feedback::where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->get();

        $_bestMix = Feedback::where('campaign_id','=',$id)->orderBy('created_at', 'desc')
        // ->whereMonth('created_at', Carbon::now()->month)
        ->selectRaw('count(*) as total,best_mix as bestMix')
        ->groupBy('best_mix')
        ->get();
        $bestMixArray = [];
        foreach($_bestMix as $key => $bestMix){
            $bestMixArray['best_mix'][] =  $bestMix->bestMix." ".number_format($bestMix->total/count($bestMixCount) * 100 ,2) ."%";
            $bestMixArray['percentage'][] = !empty($bestMix) ? $bestMix->total/count($bestMixCount) * 100 : 0;
        }
        if(!empty($_bestMix->toArray())){
            $bestMixArray['percentage'];
        }else{
            $bestMixArray['best_mix'][] = 0;
            $bestMixArray['percentage'][] = 0;
        }
        array_push($bestMixArray['percentage'],100);
        // Average
        $feedbackAverage = Feedback::where('campaign_id','=',$id)
        // ->whereMonth('created_at', Carbon::now()->month)
        ->orderBy('created_at', 'desc')
        ->avg('rating');

        // BreakDown
        $_breakdownRatingCount = Feedback::where([['campaign_id','=',$id],['rating','>=',6]])
        // ->whereMonth('created_at', Carbon::now()->month)
        ->count();
        $_breakdownRatings = Feedback::where([['campaign_id','=',$id],['rating','>=',6]])->selectRaw('rating, count(*) AS total')
        // ->whereMonth('created_at', Carbon::now()->month)
        ->get();
        $_breakdownRatingCount = count($_breakdownRatings);
        $breakdownRatingCount = [];
        foreach($_breakdownRatings as $breakdownRating){
            $breakdownRatingCount['rated'][] = $breakdownRating->rating." Rated"." ".number_format($breakdownRating->total / $_breakdownRatingCount * 100 ,2) ."%";
            $breakdownRatingCount['percentage'][] = $breakdownRating->total / $_breakdownRatingCount * 100;
        }
        return view('pages.campaigns.statistics',[
            'campaignClicks' =>$campaignClicks,
            'campaign' =>$campaign,
            'tracks' =>$tracks,
            'referers'=>$referers,
            'devices'=>$devices,
            'browsers'=>$browsers,
            'platforms'=>$platforms,
            'months_wise_total'=>$months_wise_total,
            'months_wise_unique' => $months_wise_unique,
            'countries' =>$countries,
            'countryTable' =>$countryTable,
            'feedbacks'=>$feedbacks,
            'coverImage' => $coverImage,
            'bestMixArray' => $bestMixArray,
            'feedbackAverage' =>$feedbackAverage,
            'breakdownRatingCount' => $breakdownRatingCount
        ]);
    }

    public function dateFilter($id,Request $request){
        $from = $request->startDate;
        $to = $request->endDate;
        $ranges = $request->range;
        //dd($from,$to,$ranges);
        $authDetails = Auth::user();
        $campaign = Campaign::find($id);
        if(empty($campaign)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }elseif($campaign->user_id != $authDetails->id && $authDetails->user_role == USER_ROLE_USER){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }
        $campaignPath = Campaign::$campaignPath;
        $coverImage = getFileFromStorage($campaignPath.$campaign->id."/".$campaign->cover_artwork);
        $campaigns = Campaign::where('id','=',$id)
        ->count();

        $campaignClicks = CampaignClicks::where('campaign_id','=',$id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->orderBy('created_at', 'desc')
        ->get();

        $tracks = CampaignTracks::where('campaign_id','=',$id)
        ->count();

        // Getting Feedbacks
        $feedbacks = Feedback::where('campaign_id','=',$id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->orderBy('created_at', 'desc')
        ->get();

        $_referers = CampaignClicks::selectRaw('referer, count(*) AS total')
        ->where('campaign_id','=',$id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->whereNotNull('referer')
        ->groupBy('referer')
        ->orderBy('total', 'desc')
        ->get();
        
        if($_referers->count() > 16){
            $index = 0;
            $referers = [];
            foreach ($_referers as $referer) {
                if($index < 15){
                    $referers[$index]['referer'] = $referer->referer;
                    $referers[$index++]['total'] = $referer->total;
                } else {
                    if(empty($referers[$index])){
                        $referers[$index]['total'] = 0;
                    }
                    $referers[$index]['referer'] = 'Others';
                    $referers[$index]['total'] += $referer->total;
                }
            }
        } else {
            $referers = $_referers->toArray();
        }
        
        // get devices
        $_devices = CampaignClicks::selectRaw('device, count(*) AS total')
            ->where('campaign_id','=',$id)
            ->whereNotNull('device')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();
        

        $devices = [];
        foreach($_devices as $device){
            $devices['name'][] = $device->device;
            $devices['data'][] = $device->total;
        }
        
        // get browsers
        $_browsers = CampaignClicks::selectRaw('browser_type, count(*) AS total')
            ->where('campaign_id','=',$id)
            ->whereNotNull('browser_type')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();

        $browsers = [];
        foreach($_browsers as $browser){
            $browsers['browser'][] = $browser->browser_type;
            $browsers['total'][] = $browser->total;
        }

        // get platforms
        $_platforms = CampaignClicks::selectRaw('os, count(*) AS total')
            ->where('campaign_id','=',$id)
            ->whereNotNull('os')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();

        $platforms = [];
        foreach($_platforms as $platform){
            $platforms['os'][] = $platform->os;
            $platforms['total'][] = $platform->total;
        }

        // month wise total clicks
        $_month_wise_total_clicks = CampaignClicks::selectRaw('count(*) as total, MONTH(created_at) month')
        ->where('campaign_id','=',$id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();

        

        $months_wise_total = [];
        for($i = 1; $i <= 12; $i++){
            if(!empty($_month_wise_total_clicks[$i])){
                $months_wise_total[] = $_month_wise_total_clicks[$i];
            } else {
                $months_wise_total[] = 0;
            }
        }

        // month wise unique clicks...
        $months_wise_unique = [];
        $_month_wise_unique_clicks = CampaignClicks::where('is_first_click',1)
        ->where('campaign_id','=',$id)
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->groupby('month')
        ->pluck("total", "month")
        ->toArray();

        
        for($i = 1; $i <= 12; $i++){
            if(!empty($_month_wise_unique_clicks[$i])){
                $months_wise_unique[] = $_month_wise_unique_clicks[$i];
            } else {
                $months_wise_unique[] = 0;
            }
        }

        $countries = [];
        $index = 0;
        $countryTable = [];
        $countryClicks = CampaignClicks::selectRaw('country, count(*) as total')
        ->where('campaign_id','=',$id)
        ->whereNotNull('country')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->groupBy('country')
        ->orderBy('total', 'desc')
        ->get();
        
        
        foreach ($countryClicks as $key => $click) {
            $_countries = Country::where('short_code', $click->country)->get();
            foreach ($_countries as $key => $country) {
                $countries[] = array(
                        'latLng' =>array($country->latitude,$country->longitude),
                        'name'=>$country->country,
                        'total' => $click->total
                    );
                if($index < 5){
                    $countryTable[$index]['name'] = $country->country;
                    $countryTable[$index++]['total'] = $click->total;
                } else {
                    if(empty($countryTable[$index])){
                        $countryTable[$index]['total'] = 0;
                    }
                    $countryTable[$index]['name'] = 'Others';
                    $countryTable[$index]['total'] += $click->total;
                }
            }
        }
        $_feedbacks = Feedback::where('campaign_id','=',$id);

        // Best Mix

        $_bestMixFeedback = Feedback::where('campaign_id','=',$id);

        if(isset($request->startDate) && isset($request->endDate)){
            $_feedbacks->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59']);
            $_bestMixFeedback->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59']);
        }
        $_bestMix = $_bestMixFeedback->orderBy('created_at', 'desc')->selectRaw('count(*) as total,best_mix as bestMix')->groupBy('best_mix')->get();
        $_bestMixCount =  Feedback::where('campaign_id','=',$id);
        if(isset($request->startDate) && isset($request->endDate)){
            $_bestMixCount->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59']);
        }
        $bestMixCount = $_bestMixCount->get();

        // BreakDown

        $_breakdownRatingCount = Feedback::where([['campaign_id','=',$id],['rating','>=',6]]);
        $_breakdownRating = Feedback::where([['campaign_id','=',$id],['rating','>=',6]])
            ->selectRaw('rating, count(*) AS total');
        if(isset($request->startDate) && isset($request->endDate)){
            $_breakdownRating->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59']);
            $_breakdownRatingCount->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59']);
        }
        $_breakdownRatingCount = $_breakdownRatingCount->count();
        $_breakdownRatings = $_breakdownRating->groupby('rating')->get();
        $breakdownRatingCount = [];
        foreach($_breakdownRatings as $breakdownRating){            
            $breakdownRatingCount['rated'][] = $breakdownRating->rating." Rated"." ".number_format($breakdownRating->total / $_breakdownRatingCount * 100 ,2) ."%";
            $breakdownRatingCount['percentage'][] = $breakdownRating->total / $_breakdownRatingCount * 100;
        }
        $bestMixArray = [];
        foreach($_bestMix as $key => $bestMix){
            $bestMixArray['best_mix'][] =  $bestMix->bestMix." ".number_format($bestMix->total/count($bestMixCount) * 100 ,2) ."%";
            $bestMixArray['percentage'][] = $bestMix->total/count($bestMixCount) * 100;
        }
        if(!empty($_bestMix->toArray())){
            $bestMixArray['percentage'];
        }else{
            $bestMixArray['best_mix'][] = 0;
            $bestMixArray['percentage'][] = 0;
        }
        array_push($bestMixArray['percentage'],100);
        //$feedbackAverage = $_feedbacks->orderBy('created_at', 'desc')->get();

        // Average
        $feedbackAverage = $_feedbacks->orderBy('created_at', 'desc')->avg('rating');
        return view('pages.campaigns.statistics',[
            'campaignClicks' =>$campaignClicks,
            'campaigns'=>$campaigns,
            'campaign' =>$campaign,
            'tracks' =>$tracks,
            'referers'=>$referers,
            'devices'=>$devices,
            'browsers'=>$browsers,
            'platforms'=>$platforms,
            'months_wise_total'=>$months_wise_total,
            'months_wise_unique' => $months_wise_unique,
            'countries' =>$countries,
            'from'=>$from,
    		'to'=>$to,
            'countryTable' =>$countryTable,
            'feedbacks'=>$feedbacks,
            'coverImage' => $coverImage,
            'feedbackAverage' => $feedbackAverage,
            'bestMixArray' => $bestMixArray,
            'breakdownRatingCount' => $breakdownRatingCount,
            'ranges' => $ranges
        ]);
    }

    public function sendTestEmail($id){
        $today = Carbon::now()->format('Y-m-d');
        $response = '';
        $status = '';
        $campaign = Campaign::with('getEmailGroup','getTrack')->where([
            ['id','=',$id]
            ])->first();
            setSMTP($campaign->user_id);
            if(empty($campaign)){
                $response = 'Sorry campaign not found';
                $status = 'error';
            }
            $getlabel = LabelSetting::where('id','=',$campaign->label_id)->first();
            if(empty($getlabel)){
                $response = 'Sorry Theme not found';
                $status = 'error';
            }
            
            $campaignPath = Campaign::$campaignPath;
                foreach($campaign->getEmailGroup as $emailgroup){
                    if($emailgroup->status == 1 && $emailgroup->unsubscription != 1){
                        $campaignImage = getFileFromStorage($campaignPath.$campaign->id."/".$campaign->cover_artwork);
                        $user = User::where('id',$campaign->user_id)->first();
                        if(isset($user->logo) && $user->logo != null){
                            $pathUser = User::$userProfilePath.$user->id.'/';
                            $userImage = getFileFromStorage($pathUser.$user->logo);
                        }else{
                            $userImage = getFileFromStorage('uploads/dummy/dummy-prod-1.jpg');
                        }
                        
                        
                        if(isset($getlabel->light_version_logo) && $getlabel->light_version_logo != null){
                            $labelSettingsPath = LabelSetting::$labelSettingPath;
                            $labelLogoPath = getFileFromStorage($labelSettingsPath .$getlabel->user_id.'/'. $getlabel->light_version_logo);
                        }else{
                            $userImage = getFileFromStorage('uploads/dummy/dummy-prod-1.jpg');
                        }
                        $details = [
                            'route'=> $route = route('campaigns.review',['id'=>$campaign->id,'pass_key'=>$emailgroup->pass_key]),
                            'msg' => 'A new campaign is create by an artist please give your valuable feedback',
                            'subject' => $campaign->getTrack->first()->track." [".$campaign->label."]",
                            'path' =>$campaignImage,
                            'description' => $campaign->description,
                            'campaign' =>$campaign,
                            'release_date'=>Carbon::parse($campaign->release_date)->format('F jS, Y'),
                            'userImage' => $userImage,
                            'user' =>$user,
                            'unsubscription' =>route('unsubscription',['pass_key'=>$emailgroup->pass_key])
                        ];

                        
                        if(!empty($getlabel)){
                            
                            $details['whiteImage'] = $labelLogoPath;
                            $details['theme_id'] = $campaign->theme_id;
                            $details['address'] = $getlabel->full_company_address;
                        }
                        $response = 'Promo Campaign Email Sent Sucessfully..!';
                        $status = 'status';
                        // $mailable = new \App\Mail\CampaignMail($details);
                        // return $mailable->render();
                        Mail::to($emailgroup->email)->send(new \App\Mail\CampaignMail($details));
                        EmailGroup::where('id','=',$emailgroup->id)->update(['last_send'=>$today]);
                    }
                }
                if($response != '' && $status != ''){
                    return redirect()->route('campaigns')->with($status, $response);
                }else{
                    return redirect()->route('campaigns')->with('error','Sorry mail is not send.');
                }
    }

    public function mailPreview($id) {
        $authDetails = Auth::user();
        $selectedCampaignTheme = Campaign::where('id','=',$id)->first();
        $today = Carbon::now()->format('Y-m-d');
        $response = '';
        $status = '';
        $campaign = Campaign::with('getEmailGroup','getTrack')->where([
            ['id','=',$id]
            ])->first();
            if(empty($campaign)){
                return ['status' => 204];
            }            
            $campaignPath = Campaign::$campaignPath;
            $emailgroup = $campaign->getEmailGroup->first();
            $campaignImage = getFileFromStorage($campaignPath.$campaign->id."/".$campaign->cover_artwork);
            $user = User::where('id',$campaign->user_id)->first();
            
            if(isset($user->logo) && $user->logo != null){
                $pathUser = User::$userProfilePath.$user->id.'/';
                $userImage = getFileFromStorage($pathUser.$user->logo);
            }else{
                $userImage = getFileFromStorage('uploads/dummy/dummy-prod-1.jpg');
            }
            $getlabel = LabelSetting::where('id','=',$campaign->label_id)->first();
            $labelSettingsPath = LabelSetting::$labelSettingPath.$getlabel->user_id.'/';
            $labelLogoPath = getFileFromStorage($labelSettingsPath . $getlabel->light_version_logo);

            $designArray1 = [
                'route'=> $route = route('campaigns.review',['id'=>$campaign->id]),
                'msg' => 'A new campaign is create by an artist please give your valuable feedback',
                'subject' => $campaign->getTrack->first()->track." [".$campaign->label."]",
                'path' =>$campaignImage,
                'description' => $campaign->description,
                'campaign' =>$campaign,
                'release_date'=>Carbon::parse($campaign->release_date)->format('F jS, Y'),
                'userImage' => $userImage,
                'user' =>$user,
                'address' => $getlabel->full_company_address,
                'white_logo' => $labelLogoPath,
                // 'unsubscription' => route('unsubscription',['pass_key' => 'asd'])
            ];

            
            
            // $designArray2 = [
            //     'white_logo' => $labelLogoPath,
            //     'campaign' =>$campaign,
            //     'description' => $campaign->description,
            //     'path' =>$campaignImage,
                
            //     'userImage' => $userImage,
            //     'user' =>$user,
            // ];
            $emailTemplatePages = [
                'default' => view('pages.email-templates.default',compact('designArray1'))->render(),
                'selectedCampaignTheme' => $selectedCampaignTheme->theme_id,
                'id' => $selectedCampaignTheme->id,
                'status' => 200,
                'key' => ['default']
            ];

            $colorCodes = [
                'organ' => '#ec7130',
                'blue' => '#46b4b3',
            ];
            // dd($designArray2,$designArray1);
            foreach($colorCodes as $key => $colorCode){
                $emailTemplatePages[$key] = view('pages.email-templates.email-theme-design',compact('designArray1','colorCode'))->render();

                array_push($emailTemplatePages['key'],$key);
            }
            return  $emailTemplatePages;
    }

    public function addPreviewTheme(Request $request){
        Campaign::where('id','=',$request->id)->update(['theme_id' =>$request->theme_id]);
        return response()->json(['status' => 200, 'message' => 'Theme has been updated']);
        
    }
}
