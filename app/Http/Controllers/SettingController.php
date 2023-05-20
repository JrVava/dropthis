<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    //
    public function index(){
    	$setting = Setting::first();
    	return view('pages.setting.setting',['setting'=>$setting]);
    }
    public function update(Request $request){
    	$setting = Setting::all();
    	if(count($setting) == 0){
    		$settings = new Setting();
			$settings->redirect_type = $request->redirection;
			$settings->nofollow  = !empty($request->follow) ? 1 : 0;
			$settings->track_me  = !empty($request->tracking) ? 1 : 0;
			$settings->sponsored  = !empty($request->sponsored) ? 1 : 0;
			$settings->params_forwarding  = !empty($request->parameter_forward) ? 1 : 0;
			$settings->save();
    	}else{
			Setting::where('id',$request->id)->update([
				'redirect_type'=>$request->redirection,
				'nofollow' =>!empty($request->follow) ? 1 : 0,
				'track_me' =>!empty($request->tracking) ? 1 : 0,
				'sponsored' => !empty($request->sponsored) ? 1 : 0,
				'params_forwarding' => !empty($request->parameter_forward) ? 1 : 0
			]);	
    	}
		
		return redirect()->route('settings')->with('status', 'Settings Updated Sucessfully..!');
    }
}
