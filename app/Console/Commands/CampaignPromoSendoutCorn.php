<?php

namespace App\Console\Commands;

use App\Models\LabelSetting;
use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\EmailGroup;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
class CampaignPromoSendoutCorn extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaignpromosendout:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        \Log::info("Cron is working fine! ".date('Y-m-d H:i:s'));
        $today = Carbon::now()->format('Y-m-d');
        $campaigns = Campaign::with('getEmailGroup','getTrack')->where([
            ['campaign_status','=',CAMPAIGN_STATUS_READY],
            ['promo_sendout','=',$today]
            ])->get();
            
        $campaignPath = Campaign::$campaignPath;
        foreach($campaigns as $campaign){
            $getlabel = LabelSetting::where('id','=',$campaign->label_id)->first();

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
                        $labelSettingsPath = LabelSetting::$labelSettingPath.$getlabel->user_id.'/';
                        $labelLogoPath = getFileFromStorage($labelSettingsPath . $getlabel->light_version_logo);
                        
                        $details['white_logo'] = $labelLogoPath;
                        $details['theme_id'] = $campaign->theme_id;
                        $details['address'] = $getlabel->full_company_address;
                    }

                    setSMTP($campaign->user_id);
                    Mail::to($emailgroup->email)->send(new \App\Mail\CampaignMail($details));
                   Campaign::where('id','=',$campaign->id)->update(['campaign_status'=>CAMPAIGN_STATUS_SENT]);
                    EmailGroup::where('id','=',$emailgroup->id)->update(['last_send'=>$today]);
                   \Log::info("Mail Sent to: ".$emailgroup->email);
                }
            }
        }
    }
}
