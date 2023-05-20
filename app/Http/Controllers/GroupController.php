<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\LinksGroup;
use App\Models\Click;
use DB;
use App\Models\Country;
use App\Models\Link;
use Auth;

class GroupController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function show(){
    	return view('pages.group.add');
    }

    public function save(Request $request){
		$validatedData = $request->validate([
		    'group' => 'required',
		], [
		    'group.required' => 'Group Name is required',
		]);
    	$authId =  auth()->id();

		$group = new Group();
		$group->name = $request->group;
		$group->description = $request->description;
		$group->created_by_id = $authId;
		$group->updated_by_id = $authId;
		$group->save();
		return redirect()->route('groups')->with('status', 'Group Saved Sucessfully..!');
    }

    public function index(){
		$authDetails = Auth::user();
    	$_groups = Group::withCount('grpLink');
		if($authDetails->user_role == USER_ROLE_USER){
			$_groups->where('created_by_id','=',$authDetails->id);
		}
		$groups = $_groups->get();
    	//dd($groups);
    	return view('pages.group.list',['groups'=>$groups]);
    }

    public function delete($id){
		LinksGroup::where('group_id','=',$id)->delete();
    	Group::where('id','=',$id)->delete();
    	return  redirect()->route('groups')->with('status', 'Group Deleted Sucessfully..!');
    }

    public function edit($id){
		$authDetails = Auth::user();
    	$_group = Group::where('id','=',$id);
		if($authDetails->user_role == USER_ROLE_USER){
			$_group->where('created_by_id','=',$authDetails->id);
		}
		$group = $_group->first();
		if(empty($group)){
			$error = "Sorry wrong group";
			return response(view('errors.404', compact('error')), 404);
		}
    	return view('pages.group.edit',['group'=>$group]);
    }

	public function update(Request $request){
		$validatedData = $request->validate([
			'group' => 'required',
		], [
			'group.required' => 'Group Name is required',
		]);
		$authId =  auth()->id();
		
		Group::where('id','=',$request->id)->update([
			'name'=>$request->group,
			'description'=>$request->description,
			'updated_by_id'=>$authId
		]);
		return redirect()->route('groups')->with('status', 'Group Updated Sucessfully..!');
	}

	public function statistics($id){
		$authDetails = Auth::user();
		$_groups = Group::with('grpLink')->where('id','=',$id);
		if($authDetails->user_role == USER_ROLE_USER){
			$_groups->where('created_by_id','=',$authDetails->id);
		}
		$groups = $_groups->first();
		if(empty($groups)){
			$error = "Sorry wrong group";
			return response(view('errors.404', compact('error')), 404);
		}
		// get month wise Unique Clicks
		$months_wise_unique = [];
		$monthWiseUniqueClicks = $groups->groupMonthWiseUniqueClick();
		for($i = 1; $i <= 12; $i++){
	    	if(!empty($monthWiseUniqueClicks[$i])){
	    		$months_wise_unique[] = $monthWiseUniqueClicks[$i];
	    	} else {
	    		$months_wise_unique[] = 0;
	    	}
	    }
	    // get month wise Total Clicks
	    $monthWiseTotalClicks = $groups->groupMonthWiseTotalClick();
	    $months_wise_total = [];
	    for($i = 1; $i <= 12; $i++){
	    	if(!empty($monthWiseTotalClicks[$i])){
	    		$months_wise_total[] = $monthWiseTotalClicks[$i];
	    	} else {
	    		$months_wise_total[] = 0;
	    	}
	    }

	    //get country clicks
	    $countries = [];
	    $index = 0;
        $countryTable = [];
	    foreach ($groups->countryClicks() as $key => $click) {
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

	    // get groups referers
	    if($groups->groupsReferers()->count() > 16){
    		$index = 0;
    		$referers = [];
    		foreach ($groups->groupsReferers() as $referer) {
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
    		$referers = $groups->groupsReferers()->toArray();
    	}

    	// get group Link Devices
    	$devices = [];
	    foreach($groups->groupLinksDevice() as $device){
	    	$devices['name'][] = $device->device;
	    	$devices['data'][] = $device->total;
	    }

	    // get group Links Browsers
	    $browsers = [];
	    foreach($groups->groupLinksBrowser() as $browser){
	    	$browsers['browser'][] = $browser->browser_type;
	    	$browsers['total'][] = $browser->total;
	    }

	    // get group links Platforms 
	    $platforms = [];
	    foreach($groups->groupLinksPlatforms() as $platform){
	    	$platforms['os'][] = $platform->os;
	    	$platforms['total'][] = $platform->total;
	    }
	    //dd($platforms);
	    // get group Links Info
	    $linksInfo = $groups->groupLinksInfo();

	    // get group link click details
	    $groupLinksClickDetail = $groups->groupClicksDetails();
		return view('pages.group.statistics',[
			'groups'=>$groups,
			'months_wise_unique'=>$months_wise_unique,
			'months_wise_total'=>$months_wise_total,
			'countries' =>$countries,
			'referers' =>$referers,
			'devices' =>$devices,
			'browsers' => $browsers,
			'platforms' => $platforms,
			'linksInfo' => $linksInfo,
			'groupLinksClickDetail' =>$groupLinksClickDetail,
            'countryTable' =>$countryTable
		]);
	}

	public function dateFilter($id,Request $request){
		$authDetails = Auth::user();
		$from = $request->startDate;
        $to = $request->endDate;
		$ranges = $request->range;

        $_groups = Group::with('grpLink')->where('id','=',$id);
		if($authDetails->user_role == USER_ROLE_USER){
			$_groups->where('created_by_id','=',$authDetails->id);
		}
		$groups = $_groups->first();
		if(empty($groups)){
			$error = "Sorry wrong group";
			return response(view('errors.404', compact('error')), 404);
		}
        // get month wise Unique Clicks
		$months_wise_unique = [];
		$monthWiseUniqueClicks = $groups->groupFilterMonthWiseUniqueClick($from,$to);
		for($i = 1; $i <= 12; $i++){
	    	if(!empty($monthWiseUniqueClicks[$i])){
	    		$months_wise_unique[] = $monthWiseUniqueClicks[$i];
	    	} else {
	    		$months_wise_unique[] = 0;
	    	}
	    }

	    // get month wise Total Clicks
	    $monthWiseTotalClicks = $groups->groupFilterMonthWiseTotalClick($from,$to);
	    $months_wise_total = [];
	    for($i = 1; $i <= 12; $i++){
	    	if(!empty($monthWiseTotalClicks[$i])){
	    		$months_wise_total[] = $monthWiseTotalClicks[$i];
	    	} else {
	    		$months_wise_total[] = 0;
	    	}
	    }

	    //get country clicks
	    $countries = [];
	    $index = 0;
        $countryTable = [];
	    foreach ($groups->countryFilterClicks($from,$to) as $key => $click) {
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

	    // get groups referers
	    if($groups->groupsFilterReferers($from,$to)->count() > 16){
    		$index = 0;
    		$referers = [];
    		foreach ($groups->groupsFilterReferers($from,$to) as $referer) {
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
    		$referers = $groups->groupsFilterReferers($from,$to)->toArray();
    	}

    	// get group Link Devices
    	$devices = [];
	    foreach($groups->groupLinksFilterDevice($from,$to) as $device){
	    	$devices['name'][] = $device->device;
	    	$devices['data'][] = $device->total;
	    }

	    // get group Links Browsers
	    $browsers = [];
	    foreach($groups->groupLinksFilterBrowser($from,$to) as $browser){
	    	$browsers['browser'][] = $browser->browser_type;
	    	$browsers['total'][] = $browser->total;
	    }

	    // get group links Platforms 
	    $platforms = [];
	    foreach($groups->groupLinksFilterPlatforms($from,$to) as $platform){
	    	$platforms['os'][] = $platform->os;
	    	$platforms['total'][] = $platform->total;
	    }

	    // get group Links Info
	    $linksInfo = $groups->groupLinksFilterInfo($from,$to);

	    // get group link click details
	   $groupLinksClickDetail = $groups->groupClicksFilterDetails($from,$to);
       return view('pages.group.statistics',[
			'groups'=>$groups,
			'months_wise_unique'=>$months_wise_unique,
			'months_wise_total'=>$months_wise_total,
			'countries' =>$countries,
			'referers' =>$referers,
			'devices' =>$devices,
			'browsers' => $browsers,
			'platforms' => $platforms,
			'linksInfo' => $linksInfo,
			'groupLinksClickDetail' =>$groupLinksClickDetail,
			'from'=>$from,
			'to' => $to,
            'countryTable' =>$countryTable,
			'ranges' => $ranges
		]);
	}
}
