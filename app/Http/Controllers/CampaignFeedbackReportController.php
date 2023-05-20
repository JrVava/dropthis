<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Barryvdh\DomPDF\Facade\Pdf;
use Auth;
use App\Models\Feedback;
use App\Models\Campaign;
use App\Models\CampaignClicks;
use Carbon\Carbon;
use App\Models\Country;
///use PDF;
use PDF;
use Illuminate\Support\Facades\Storage;
use App\Models\DownloadMapping;
use DB;

class CampaignFeedbackReportController extends Controller
{
    public function feedbackReport(Request $request){
    	$id = $request->id;
        $ratingChart = $request->ratingChart;
        $bestMixChart = $request->bestMixChart;
        $beakdownChart = $request->beakdownChart;
        $device = $request->device;
        $browser = $request->browser;
        $platform = $request->platform;

        $platformEmpty = $request->platformEmpty;
        $browserEmpty = $request->browserEmpty;
        $deviceEmpty = $request->deviceEmpty;

        $bestMixChartEmpty = $request->bestMixChartEmpty;
        $beakdownChartEmpty = $request->beakdownChartEmpty;
        $ratingChartEmpty = $request->ratingChartEmpty;

    	$authDetails = Auth::user();
        $campaign = Campaign::with('getTrack','userDetails')->find($id);
        if(empty($campaign)){
            return redirect()->route('campaigns')->with('error', "Sorry campaign doesn't exit");
        }
        $campaignPath = Campaign::$campaignPath;
        $coverImage = getFileFromStorage($campaignPath.$campaign->id."/".$campaign->cover_artwork);
        
        // Getting Feedbacks
        $_feedbacks = Feedback::where('campaign_id','=',$id);
        $_feedbacksSupportingYes = Feedback::where('campaign_id','=',$id);
        if(isset($request->startDate) && isset($request->endDate)){
            $_feedbacks->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59']);

        }else{
            $_feedbacks->whereMonth('created_at', Carbon::now()->month);
        }
        $feedbacks = $_feedbacks->orderBy('created_at', 'desc')->get();
        $feedbackAverage = $_feedbacks->orderBy('created_at', 'desc')->avg('rating');
        $feedbackSupportingYes = $_feedbacksSupportingYes->where('supporting','=',1)->count();
        $totalfeedbacks = $_feedbacks->count();
        
        $bestmix = $_feedbacks->whereNotNull('best_mix')->groupby('best_mix')
            ->selectRaw('count(*) as total,best_mix as bestMix')
        ->get();

       
        $_campaignClicks = CampaignClicks::where('campaign_id','=',$id);
        if(isset($request->startDate) && isset($request->endDate)){
            $_campaignClicks->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59'])->get();
        }else{
            $_campaignClicks->whereMonth('created_at', Carbon::now()->month)->orderBy('created_at', 'desc');
        }       

        $reviewButNotDownloaded = $_campaignClicks->whereNotExists( function ($query){
            $query->select(DB::raw(1))
            ->from('download_mapping')
            ->whereRaw('campaign_clicks.user_id = download_mapping.user_id OR campaign_clicks.email = download_mapping.email');
            })
        ->groupBy('user_id')
        ->groupBy('email')
        ->get();
        $_download = DownloadMapping::where('campaign_id','=',$id);
        
        if(isset($request->startDate) && isset($request->endDate)){
            $_download->whereBetween('created_at', [$request->startDate.' 00:00:00',$request->endDate.' 23:59:59'])->get();
        }else{
            $_download->whereMonth('created_at', Carbon::now()->month)->orderBy('created_at', 'desc');
        }

        $downloadedButNotLeftFeedbacks = $_download->whereNotExists( function ($query){
            $query->select(DB::raw(1))
            ->from('reviews_feedbacks')
            ->whereRaw('download_mapping.user_id = reviews_feedbacks.user_id OR download_mapping.email = reviews_feedbacks.email');
            })
        ->groupBy('user_id')
        ->groupBy('email')
        ->get();

        // echo "<pre>";
        // foreach($campaignClicks as $key => $click){
        //     $_download = DownloadMapping::where('campaign_id','=',$click->campaign_id);
        //     if($click->user_id == null ){
        //         $download = $_download->where('email','!=',$click->email)->orWhereNull('email')->get();
        //         // dd("Email", $download);
        //         foreach($download as $d){
        //             $viewedButNotDownloaded[$d->id] = $d->user_id;
        //         }
        //     }else{
        //         $download = $_download->where('user_id','!=',$click->user_id)->orWhereNull('user_id')->get();
        //         // dd("User", $download);
        //         foreach($download as $d){
        //             $viewedButNotDownloaded[$d->id] = $d->email;
        //         }
        //     }
        //     // $download = $_download->get();
        //     // $viewedButNotDownloaded[] = $download;
        // }
        // dd($viewedButNotDownloaded);

        $pdf = PDF::loadView('pages.campaigns.feedbackReport', [
            'feedbackSupportingYes' => $feedbackSupportingYes,
            'totalfeedbacks'=>$totalfeedbacks,
            'feedbackAverage'=>$feedbackAverage,
            'coverImage'=>$coverImage,
            'authDetails' => $authDetails,
            'feedbacks'=>$feedbacks,
            'campaign'=>$campaign,
            'device'=>$device,
            'browser'=>$browser,
            'platform'=>$platform,
            'bestmix' => $bestmix,
            'reviewButNotDownloaded' => $reviewButNotDownloaded,
            'downloadedButNotLeftFeedbacks' => $downloadedButNotLeftFeedbacks,
            'campaignPath' => $campaignPath,
            'ratingChart' => $ratingChart,
            'bestMixChart' => $bestMixChart,
            'beakdownChart' => $beakdownChart,
            'bestMixChartEmpty' => $bestMixChartEmpty,
            'beakdownChartEmpty' => $beakdownChartEmpty,
            'ratingChartEmpty' => $ratingChartEmpty,
            'platformEmpty' => $platformEmpty,
            'browserEmpty' => $browserEmpty,
            'deviceEmpty' => $deviceEmpty,
        ]);

        $pdf->getDomPDF()
            ->set_option("enable_php", true);
    	return $pdf->download('report.pdf');

        return view('pages.campaigns.feedbackReport',[
            'feedbackSupportingYes' => $feedbackSupportingYes,
            'totalfeedbacks'=>$totalfeedbacks,
            'feedbackAverage'=>$feedbackAverage,
            'coverImage'=>$coverImage,
            'authDetails' => $authDetails,
            'feedbacks'=>$feedbacks,
            'campaign'=>$campaign,
            'device'=>$device,
            'browser'=>$browser,
            'platform'=>$platform,
            'bestmix' => $bestmix,
            'reviewButNotDownloaded' => $reviewButNotDownloaded,
            'downloadedButNotLeftFeedbacks' => $downloadedButNotLeftFeedbacks,
            'ratingChart' => $ratingChart,
            'bestMixChart' => $bestMixChart,
            'beakdownChart' => $beakdownChart,
            'campaignPath' => $campaignPath,
            'platformEmpty' => $platformEmpty,
            'browserEmpty' => $browserEmpty,
            'deviceEmpty' => $deviceEmpty,
            'bestMixChartEmpty' => $bestMixChartEmpty,
            'beakdownChartEmpty' => $beakdownChartEmpty,
            'ratingChartEmpty' => $ratingChartEmpty,
        ]);
    }
}
