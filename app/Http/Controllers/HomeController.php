<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Domain;
use App\Models\Country;
use App\Models\Link;
use App\Models\Group;
use App\Models\Click;
use App\Models\LinksGroup;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index() {
        $dateS = Carbon::now()->startOfMonth()->subMonth(3);
        $dateE = Carbon::now()->startOfMonth();
        $authDetails = Auth::user();
        //GET LINK, GROUP AND CLICK
        $_groups = Group::whereBetween('created_at',[$dateS,$dateE]);
        if($authDetails->user_role == USER_ROLE_USER){
            $_groups->where('created_by_id','=',$authDetails->id);
        }
        $groups = $_groups->count();
        
        $_clicks = Click::whereBetween('created_at',[$dateS,$dateE])->orderBy('created_at', 'desc');
        if($authDetails->user_role == USER_ROLE_USER){
            $_clicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $clicks = $_clicks->get();
        
        // $clicks = Click::with('link')->whereBetween('created_at',[$dateS,$dateE])->orderBy('created_at', 'desc')->get();
        // dd($clicks);
        $_links = Link::whereBetween('created_at',[$dateS,$dateE]);
        if($authDetails->user_role == USER_ROLE_USER){
            $_links->where('created_by_id', $authDetails->id);
        }
        $links = $_links->count();

        // get referers
        $__referers = Click::selectRaw('referer, count(*) AS total')
        ->whereBetween('created_at',[$dateS,$dateE])
        ->whereNotNull('referer');
        if($authDetails->user_role == USER_ROLE_USER){
            $__referers->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $__referers->groupBy('referer')
        ->orderBy('total', 'desc');
        $_referers = $__referers->get();
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
        $__devices = Click::selectRaw('device, count(*) AS total')
            ->whereNotNull('device')
            ->whereBetween('created_at',[$dateS,$dateE]);
            if($authDetails->user_role == USER_ROLE_USER){
                $__devices->whereHas("link", function($q) use ($authDetails) {
                    $q->where('created_by_id', $authDetails->id);
                });
            }
        $__devices->groupBy('device')
        ->orderBy('total', 'desc');
        $_devices = $__devices->get();

        $devices = [];
        foreach($_devices as $device){
            $devices['name'][] = $device->device;
            $devices['data'][] = $device->total;
        }

        // get browsers
        $__browsers = Click::selectRaw('browser_type, count(*) AS total')
        ->whereNotNull('browser_type')
        ->whereBetween('created_at',[$dateS,$dateE]);
            if($authDetails->user_role == USER_ROLE_USER){
                $__browsers->whereHas("link", function($q) use ($authDetails) {
                    $q->where('created_by_id', $authDetails->id);
                });
            }
        $__browsers->groupBy('browser_type')
        ->orderBy('total', 'desc');
        $_browsers = $__browsers->get();

        $browsers = [];
        foreach($_browsers as $browser){
            $browsers['browser'][] = $browser->browser_type;
            $browsers['total'][] = $browser->total;
        }

        // get platforms
        $__platforms = Click::selectRaw('os, count(*) AS total')
            ->whereNotNull('os')
            ->whereBetween('created_at',[$dateS,$dateE]);
            if($authDetails->user_role == USER_ROLE_USER){
                $__platforms->whereHas("link", function($q) use ($authDetails) {
                    $q->where('created_by_id', $authDetails->id);
                });
            }
            $__platforms->groupBy('os')
            ->orderBy('total', 'desc');
        $_platforms = $__platforms->get();

        $platforms = [];
        foreach($_platforms as $platform){
            $platforms['os'][] = $platform->os;
            $platforms['total'][] = $platform->total;
        }

        // month wise total clicks
        $__month_wise_total_clicks = Click::selectRaw('count(*) as total, MONTH(created_at) month')
        ->whereBetween('created_at',[$dateS,$dateE]);
        if($authDetails->user_role == USER_ROLE_USER){
            $__month_wise_total_clicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }        
        $_month_wise_total_clicks = $__month_wise_total_clicks->groupby('month')->pluck("total", "month")->toArray();
        
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
        $__month_wise_unique_clicks = Click::where('is_first_click',1)
        ->whereBetween('created_at',[$dateS,$dateE]);
        if($authDetails->user_role == USER_ROLE_USER){
            $__month_wise_unique_clicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }  
        $_month_wise_unique_clicks = $__month_wise_unique_clicks->selectRaw('count(*) as total, MONTH(created_at) month')
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
        $_countryClicks = Click::selectRaw('country, count(*) as total')
        ->whereNotNull('country')
        ->whereBetween('created_at',[$dateS,$dateE]);
        if($authDetails->user_role == USER_ROLE_USER){
            $_countryClicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $countryClicks = $_countryClicks->groupBy('country')
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

        return view('pages.dashboard',[
            'clicks' =>$clicks,
            'links'=>$links,
            'groups'=>$groups,
            'referers'=>$referers,
            'devices'=>$devices,
            'browsers'=>$browsers,
            'platforms'=>$platforms,
            'months_wise_total'=>$months_wise_total,
            'months_wise_unique' => $months_wise_unique,
            'countries' =>$countries,
            'countryTable' =>$countryTable,
        ]);
    }

    public function dateFilter(Request $request){
        $authDetails = Auth::user();
        $from = $request->startDate;
        $to = $request->endDate;
        $ranges = $request->range;
        //dd($from,$to,$ranges);
        //$date = Carbon::createFromFormat('d/m/Y', $from);
        //GET Filtered LINK, GROUP AND CLICK
        $_groups = Group::whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59']);
        if($authDetails->user_role == USER_ROLE_USER){
            $_groups->where('created_by_id','=',$authDetails->id);
        }
        $groups = $_groups->count();

        $_clicks = Click::whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59']);
        if($authDetails->user_role == USER_ROLE_USER){
            $_clicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $clicks = $_clicks->orderBy('created_at', 'desc')
        ->get();

        $_links = Link::whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59']);
        if($authDetails->user_role == USER_ROLE_USER){
            $_links->where('created_by_id','=',$authDetails->id);   
        }
        $links = $_links->count();

        // get referers
        $__referers = Click::selectRaw('referer, count(*) AS total')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->whereNotNull('referer');
        if($authDetails->user_role == USER_ROLE_USER){
            $__referers->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $_referers = $__referers->groupBy('referer')
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
        $__devices = Click::selectRaw('device, count(*) AS total')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->whereNotNull('device');
            if($authDetails->user_role == USER_ROLE_USER){
                $__devices->whereHas("link", function($q) use ($authDetails) {
                    $q->where('created_by_id', $authDetails->id);
                });
            }
            $_devices = $__devices->groupBy('device')
            ->orderBy('total', 'desc')
            ->get();

        $devices = [];
        foreach($_devices as $device){
            $devices['name'][] = $device->device;
            $devices['data'][] = $device->total;
        }

        // get browsers
        $__browsers = Click::selectRaw('browser_type, count(*) AS total')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->whereNotNull('browser_type');
            if($authDetails->user_role == USER_ROLE_USER){
                $__browsers->whereHas("link", function($q) use ($authDetails) {
                    $q->where('created_by_id', $authDetails->id);
                });
            }
            $_browsers = $__browsers->groupBy('browser_type')
            ->orderBy('total', 'desc')
            ->get();
        $browsers = [];
        foreach($_browsers as $browser){
            $browsers['browser'][] = $browser->browser_type;
            $browsers['total'][] = $browser->total;
        }

        // get platforms
        $__platforms = Click::selectRaw('os, count(*) AS total')
            ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
            ->whereNotNull('os');
            if($authDetails->user_role == USER_ROLE_USER){
                $__platforms->whereHas("link", function($q) use ($authDetails) {
                    $q->where('created_by_id', $authDetails->id);
                });
            }
            $_platforms = $__platforms->groupBy('os')
            ->orderBy('total', 'desc')
            ->get();
        $platforms = [];
        foreach($_platforms as $platform){
            $platforms['os'][] = $platform->os;
            $platforms['total'][] = $platform->total;
        }

        // month wise total clicks
        $__month_wise_total_clicks = Click::selectRaw('count(*) as total, MONTH(created_at) month')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59']);
        if($authDetails->user_role == USER_ROLE_USER){
            $__month_wise_total_clicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $_month_wise_total_clicks = $__month_wise_total_clicks->groupby('month')
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
        $__month_wise_unique_clicks = Click::where('is_first_click',1)
        ->selectRaw('count(*) as total, MONTH(created_at) month')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59']);
        if($authDetails->user_role == USER_ROLE_USER){
            $__month_wise_unique_clicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $_month_wise_unique_clicks = $__month_wise_unique_clicks->groupby('month')
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
        $_countryClicks = Click::selectRaw('country, count(*) as total')
        ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
        ->whereNotNull('country');
        if($authDetails->user_role == USER_ROLE_USER){
            $_countryClicks->whereHas("link", function($q) use ($authDetails) {
                $q->where('created_by_id', $authDetails->id);
            });
        }
        $countryClicks = $_countryClicks->groupBy('country')
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
        //dd($countryClicks);
        return view('pages.dashboard',[
            'clicks' =>$clicks,
            'links'=>$links,
            'groups'=>$groups,
            'referers'=>$referers,
            'devices'=>$devices,
            'browsers'=>$browsers,
            'platforms'=>$platforms,
            'months_wise_total'=>$months_wise_total,
            'months_wise_unique' => $months_wise_unique,
            'countries' =>$countries,
            'from' => $from,
            'to' => $to,
            'countryTable' =>$countryTable,
            'ranges' => $ranges
        ]);
    }
}
