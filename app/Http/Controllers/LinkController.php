<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Link;
use App\Models\Group;
use App\Models\Click;
use App\Models\LinksGroup;
use Illuminate\Validation\Rule;
use App\Models\Domain;
use App\Models\Country;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Setting;
use Carbon\Carbon;
use Auth;

class LinkController extends Controller
{

	public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
		$authDetails = Auth::user();
		$_link = Link::with('linksGroup','click');
		if($authDetails->user_role == USER_ROLE_USER){
			$_link->where('created_by_id','=',$authDetails->id);
		}
		$link = $_link->get();
    	return view('pages.link.list',['link'=>$link]);
    }
	
	public function show(){
		$length = 4;
		$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
		$shortUrl       = '';
		$index = strlen( $characters ) - 1;
		for ( $i = 0; $i < $length; $i ++ ) {
			$shortUrl .= $characters[ mt_rand( 0, $index ) ];
		}

		$domains = Domain::where('status','=',1)->get();
    	$group = Group::all();
    	$setting = Setting::first();

    	return view('pages.link.add',[
    		'group'=>$group,
    		'shortUrl'=>$shortUrl,
    		'domains'=>$domains,
    		'setting'=>$setting
    	]);
    }


    public function save(Request $request){
    
    	$todayDate = date('m/d/Y');
    	$validatedData = $request->validate([
		    'short_url' => 'required|unique:links,slug',
		    'title' => 'required',
		    'groups' => 'required',
		    // 'target_url' => ['required','regex:/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
		    'target_url' => ['required','url'],
		    'expiration' => 'nullable|date_format:m/d/Y|after_or_equal:'.$todayDate,
		], [
		    'short_url.required' => 'Short URL is required',
		    'short_url.unique' => 'Please Enter unique Short URL',
		    'title.required' => 'Title is required',
		    'groups' => 'Group is required',
		    'target_url.required' => 'URL is required',
		    'target_url.url' => 'URL is not valid',
		    'expiration.date_format' => 'Expire date format must be M/D/Y',
		    'expiration.after_or_equal' => 'Expire date should not be previous'
		]);
    	$authId =  auth()->id();

		//$domain = $request->rules;
		$domain = ['domain',$request->rules];
		
		$link = new Link();
		$link->slug = $request->short_url;
		$link->name = $request->title;
		$link->url = $request->target_url;
		$link->description =$request->notes;
		$link->nofollow = !empty($request->follow) ? 1 : 0;
		$link->track_me = !empty($request->tracking) ? 1 : 0;
		$link->sponsored = !empty($request->sponsored) ? 1 : 0;
		$link->params_forwarding = !empty($request->parameter_forward) ? 1 : 0;
		$link->redirect_type = $request->redirection;
		$link->status = 1;
		$link->type = "direct";
		$link->password = !empty($request->password) ? $request->password : null;
		$link->expires_at = $request->expiration;
		$link->rules = serialize($domain);
		$link->created_by_id = $authId;
		$link->updated_by_id = $authId;
		$link->save();
		$linkId = $link->id;

		foreach ($request->groups as $key => $groupId) {
			$linkGroup = new LinksGroup();
			$linkGroup->link_id = $linkId;
			$linkGroup->group_id = $groupId;
			$linkGroup->created_by_id = $authId;
			$linkGroup->save();
		}
		return redirect()->route('links')->with('status', 'Link Saved Sucessfully..!');
    }

    public function delete($id){
		Click::where('links_id','=',$id)->delete();
		Link::where('id','=',$id)->delete();
    	LinksGroup::where('link_id','=',$id)->delete();
    	return  redirect()->route('links')->with('status', 'Link Deleted Sucessfully..!');
    }

    public function edit($id){
		$authDetails = Auth::user();
    	$_link = Link::with('linksGroup')->where('id','=',$id);
		if($authDetails->user_role == USER_ROLE_USER){
			$_link->where('created_by_id','=',$authDetails->id);
		}
		$link = $_link->first();
		if(empty($link)){
			$error = "Sorry wrong campaign";
			return response(view('errors.404', compact('error')), 404);
		}
    	$group = Group::all();
    	$domains = Domain::where('status','=',1)->get();
    	return view('pages.link.edit',[
    		'link'=>$link,
    		'group'=>$group,
    		'domains'=>$domains
    	]);
    }

    public function update(Request $request){
    	$todayDate = date('m/d/Y');
    	$validatedData = $request->validate([
		    'short_url' => ['required',
		    Rule::unique('links','slug')->ignore($request->id)
		],
		    'title' => 'required',
		    'groups' => 'required',
		    'target_url' => ['required','url'],
		    'expiration' => 'nullable|date_format:m/d/Y|after_or_equal:'.$todayDate,
		], [
		    'short_url.required' => 'Short URL is required',
		    'short_url.unique' => 'Please Enter unique Short URL',
		    'title.required' => 'Title is required',
		    'groups' => 'Group is required',
		    'target_url.required' => 'URL is required',
		    'target_url.regex' => 'URL is not valid',
		    'expiration.date_format' => 'Expire date format must be M/D/Y',
		    'expiration.after_or_equal' => 'Expire date should not be previous'
		]);
		$authId =  auth()->id();
		$domain = ['domain',$request->rules];
    	Link::where('id','=',$request->id)->update([
    		'slug' => $request->short_url,
    		'name' => $request->title,
    		'url' => $request->target_url,
    		'description' => $request->notes,
    		'nofollow' => !empty($request->follow) ? 1 : 0,
    		'track_me' => !empty($request->tracking) ? 1 : 0,
    		'sponsored' => !empty($request->sponsored) ? 1 : 0,
    		'params_forwarding' => !empty($request->parameter_forward) ? 1 : 0,
    		'redirect_type' => $request->redirection,
    		'status' => 1,
    		'type' => "direct",
    		'password' => !empty($request->password) ? $request->password : null,
    		'expires_at' => $request->expiration,
			'created_by_id' => $authId,
			'updated_by_id' => $authId,
			'rules' => serialize($domain)
    	]);
   		LinksGroup::where('link_id','=',$request->id)->delete();
    	foreach ($request->groups as $key => $groupId) {
    		$linkGroup = new LinksGroup();
			$linkGroup->link_id = $request->id;
			$linkGroup->group_id = $groupId;
			$linkGroup->created_by_id = $authId;
			$linkGroup->save();
		}
		return redirect()->route('links')->with('status', 'Link Updated Sucessfully..!');
    }

    public function statistics($id){
		$authDetails = Auth::user();
    	// get link by id...
    	$_links = Link::with('dateFilterLinksGroup','dateFilterclick');
		if($authDetails->user_role == USER_ROLE_USER){
			$_links->where('created_by_id','=',$authDetails->id);
		}
		$links = $_links->where('id','=',$id)->first();
		if(empty($links)){
			$error = "Sorry wrong campaign";
			return response(view('errors.404', compact('error')), 404);
		}
    	// referers data of current month
    	$_referers = Click::selectRaw('referer, count(*) AS total')
    	->where(['links_id' => $id])
    	->whereMonth('created_at', Carbon::now()->month)
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

    	// get devices data of current month
    	$_devices = Click::selectRaw('device, count(*) AS total')
	    	->where(['links_id' => $id])
	    	->whereMonth('created_at', Carbon::now()->month)
	    	->whereNotNull('device')
	    	->groupBy('device')
	    	->orderBy('total', 'desc')
	    	->get();

	    $devices = [];
	    foreach($_devices as $device){
	    	$devices['name'][] = $device->device;
	    	$devices['data'][] = $device->total;
	    }

	    // get browsers data of current month
	    $_browsers = Click::selectRaw('browser_type, count(*) AS total')
	    	->where(['links_id' => $id])
	    	->whereNotNull('browser_type')
	    	->whereMonth('created_at', Carbon::now()->month)
	    	->groupBy('browser_type')
	    	->orderBy('total', 'desc')
	    	->get();
	    $browsers = [];
	    foreach($_browsers as $browser){
	    	$browsers['browser'][] = $browser->browser_type;
	    	$browsers['total'][] = $browser->total;
	    }

	    // get platforms data of current month
		$_platforms = Click::selectRaw('os, count(*) AS total')
			->where(['links_id' => $id])
			->whereNotNull('os')
			->whereMonth('created_at', Carbon::now()->month)
			->groupBy('os')
			->orderBy('total', 'desc')
			->get();
	    $platforms = [];
	    foreach($_platforms as $platform){
	    	$platforms['os'][] = $platform->os;
	    	$platforms['total'][] = $platform->total;
	    }
	    $totalClicks = $links->click->count();
	    
	    // month wise total clicks of current month
	    $_month_wise_total_clicks = Click::where(['links_id' => $id])
	    ->selectRaw('count(*) as total, MONTH(created_at) month')
	    ->whereMonth('created_at', Carbon::now()->month)
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

	    // month wise unique clicks of current month
	    $months_wise_unique = [];
	    $_month_wise_unique_clicks = Click::where(['links_id' => $id, 'is_first_click' => 1])
	    ->selectRaw('count(*) as total, MONTH(created_at) month')
	    ->whereMonth('created_at', Carbon::now()->month)
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

	    // get countries name and long and lat data of current month
	    $countries = [];
	    $index = 0;
        $countryTable = [];
	    $countryClicks = Click::selectRaw('country, count(*) as total')
	    ->where('links_id', $id)
	    ->whereMonth('created_at', Carbon::now()->month)
	    ->whereNotNull('country')
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
    	return view('pages.link.statistics',[
    		'links'=>$links,
    		'referers'=>$referers,
    		'devices'=>$devices,
    		'browsers'=>$browsers,
    		'platforms'=>$platforms,
    		'months_wise_total'=>$months_wise_total,
    		'months_wise_unique' => $months_wise_unique,
    		'countries' =>$countries,
    		'countryTable' =>$countryTable
    	]);
    }

    public function dateFilter($id,Request $request){
		$authDetails = Auth::user();
		$from = $request->startDate;
        $to = $request->endDate;
		$ranges = $request->range;
        // get link by id...
    	$_links = Link::with('linksGroup','click');
		if($authDetails->user_role == USER_ROLE_USER){
			$_links->where('created_by_id','=',$authDetails->id);
		}
		$links = $_links->where('id','=',$id)->first();
		if(empty($links)){
			$error = "Sorry wrong campaign";
			return response(view('errors.404', compact('error')), 404);
		}
    	// get referers by start date and end date
    	$_referers = Click::selectRaw('referer, count(*) AS total')
	    	->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
	    	->where(['links_id' => $id])
	    	->whereNotNull('referer')
	    	->groupBy('referer')
	    	->orderBy('total', 'asc')
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
    	$_devices = Click::selectRaw('device, count(*) AS total')
    		->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
	    	->where(['links_id' => $id])
	    	->whereNotNull('device')
	    	->groupBy('device')
	    	->orderBy('total', 'desc')
	    	->get();

	    $devices = [];
	    foreach($_devices as $device){
	    	$devices['name'][] = $device->device;
	    	$devices['data'][] = $device->total;
	    }

	    // get browsers
	    $_browsers = Click::selectRaw('browser_type, count(*) AS total')
	    	->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
	    	->where(['links_id' => $id])
	    	->whereNotNull('browser_type')
	    	->groupBy('browser_type')
	    	->orderBy('total', 'desc')
	    	->get();
	    $browsers = [];
	    foreach($_browsers as $browser){
	    	$browsers['browser'][] = $browser->browser_type;
	    	$browsers['total'][] = $browser->total;
	    }

	     // get platforms
		$_platforms = Click::selectRaw('os, count(*) AS total')
			->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
			->where(['links_id' => $id])
			->whereNotNull('os')
			->groupBy('os')
			->orderBy('total', 'desc')
			->get();

	    $platforms = [];
	    foreach($_platforms as $platform){
	    	$platforms['os'][] = $platform->os;
	    	$platforms['total'][] = $platform->total;
	    }

	    // month wise total clicks
	    $_month_wise_total_clicks = Click::where(['links_id' => $id])
		    ->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
		    ->selectRaw('count(*) as total, MONTH(created_at) month')
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
	    $_month_wise_unique_clicks = Click::where(['links_id' => $id, 'is_first_click' => 1])
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

	     // get countries name and long and lat
	    $countries = [];
	    $index = 0;
        $countryTable = [];
	    $countryClicks = Click::selectRaw('country, count(*) as total')
	    	->whereBetween('created_at', [$from.' 00:00:00',$to.' 23:59:59'])
		    ->where('links_id', $id)
		    ->whereNotNull('country')
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
    	return view('pages.link.statistics',[
    		'links'=>$links,
    		'referers'=>$referers,
    		'devices'=>$devices,
    		'browsers'=>$browsers,
    		'platforms'=>$platforms,
    		'months_wise_total'=>$months_wise_total,
    		'months_wise_unique' => $months_wise_unique,
    		'countries' =>$countries,
    		'from'=>$from,
    		'to'=>$to,
    		'countryTable' => $countryTable,
			'ranges' => $ranges
    	]);
    }
}
