<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CampaignStatusController extends Controller
{
    public function status(Request $request){
        $campaign = Campaign::find($request->id);
        $user = User::find($campaign->user_id);
        // dd(isNewUser($user->id));

        if(isNewUser($user->id)){
            // show error message to admin and not allow to send it on true
            $msg = 'This is a new user and not buy any credit yet.';
            $status = 'error';
        }else{
            if($request->status == CAMPAIGN_STATUS_READY){
                $campaignStatus = CAMPAIGN_STATUS_READY;
            }       

            if($request->status == CAMPAIGN_STATUS_ATTENTION){
                $campaignStatus = CAMPAIGN_STATUS_ATTENTION;
            }
            $route = route('campaigns.review',['id'=>$request->id,'status'=>'changed']);
            Campaign::where('id','=',$request->id)->update(['campaign_status'=>$campaignStatus]);
            $msg = "Hello, ".$user->name." your campaign status has been changed to ".$campaignStatus;

            
            // Mail Sending Array
            // $details = [
                //     'msg'=>$msg,
            //     'route'=>$route,
            //     'subject' => 'Campaign Status'
            // ];
            // // Mail Sending
            // Mail::to($user->email)->send(new \App\Mail\CampaignMail($details));
            $msg = 'Campaign status changed Sucessfully..!';
            $status = 'status';
        }
        return redirect()->route('campaigns')->with($status, $msg);
    }
}
