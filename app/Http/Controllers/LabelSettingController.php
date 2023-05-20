<?php

namespace App\Http\Controllers;

use App\Models\LabelSetting;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;

class LabelSettingController extends Controller
{
    public function store(Request $request){
        $authDetails = FacadesAuth::user();
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $rules = [
            'label_name.*.label_name' => 'required',
            'url.*.url' => 'required|url',
            'email_address.*.email_address' => 'required|email',
            'full_company_address.*.full_company_address' => 'required',
            // 'light_version_logo.*.light_version_logo' => 'nullable|image|mimes:png,jpg,svg,jpeg|max:'.$max,
            'dark_version_logo.*.dark_version_logo' => 'nullable|image|mimes:png,jpg,svg,jpeg|max:'.$max,
            'backgroung_image.*.backgroung_image' => 'nullable|image|mimes:png,jpg,svg,jpeg|max:'.$max,
		];
        
        foreach($request->label_name as $key => $value){
            if(!isset($request->old_light_version_logo[$key]['old_light_version_logo']) || $request->old_light_version_logo[$key]['old_light_version_logo'] == null){
                $rules['light_version_logo.'.$key.'.light_version_logo'] = 'required|image|mimes:jpeg,png,jpg,svg|max:'.$max;
            }
        }
        
        $messages = [
            'label_name.*.label_name.required' => 'Name is required',
            'url.*.url.required' => 'URL is required',
            'url.*.url.url' => 'URL is invalid',
            'email_address.*.email_address.required' => 'Email Address is required',
            'email_address.*.email_address.email' => 'Email Address is invalid',
            'full_company_address.*.full_company_address.required' => 'Full Company Address is required',
            'light_version_logo.*.light_version_logo.required' => 'Light Logo is required',
            'light_version_logo.*.light_version_logo.image' => 'Light Logo must be image',
            'light_version_logo.*.light_version_logo.mimes' => 'Light Logo extension must be .jpeg, .png, .jpg, .svg',
            'light_version_logo.*.light_version_logo.max' => 'The Light Logo must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'dark_version_logo.*.dark_version_logo.required' => 'Dark Logo is required',
            'dark_version_logo.*.dark_version_logo.image' => 'Dark Logo must be image',
            'dark_version_logo.*.dark_version_logo.mimes' => 'Dark Logo extension must be .jpeg, .png, .jpg, .svg',
            'dark_version_logo.*.dark_version_logo.max' => 'The Dark Logo must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'backgroung_image.*.backgroung_image.required' => 'Background Image is required',
            'backgroung_image.*.backgroung_image.image' => 'Background Image must be image',
            'backgroung_image.*.backgroung_image.mimes' => 'Background Image extension must be .jpeg, .png, .jpg, .svg',
            'backgroung_image.*.backgroung_image.max' => 'The Background Image must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
		];
        
        
       
        // dd($rules);
        $validatedData = $request->validate($rules, $messages);

        $labelSettingData = [];
        $labelSettingData['token'] = $request->_token;
        $labelSettingData['user_id'] = $authDetails->id;
        foreach($request->label_name as $key => $value){

            $labelSettingData['label_name'] = $value['label_name'];
            $labelSettingData['url'] = $request['url'][$key]['url'];
            $labelSettingData['email_address'] = $request['email_address'][$key]['email_address'];
            $labelSettingData['full_company_address'] = $request['full_company_address'][$key]['full_company_address'];
            $labelSettingData['theme_mode'] = isset($request['theme_mode'][$key]['theme_mode']) ? 1 : 0 ;

            if (isset($request['light_version_logo'][$key]['light_version_logo'])) {
                $light_version_logo = $request['light_version_logo'][$key]['light_version_logo'];
                // $light_version_logofilename = Str::random(20).".".$light_version_logo->getClientOriginalExtension();
                $light_version_logofilename = Str::random(20).".png";
                svgTOpngStoreFile($light_version_logo, LabelSetting::$labelSettingPath.$authDetails->id."/",$light_version_logofilename);
                // userProfileUploade($light_version_logo, LabelSetting::$labelSettingPath.$authDetails->id."/",$light_version_logofilename);
                if(isset($request->old_light_version_logo[$key]['old_light_version_logo'])){
                    removeFileFromStorage(LabelSetting::$labelSettingPath.$authDetails->id."/".$request->old_light_version_logo[$key]['old_light_version_logo']);
                }
                $labelSettingData['light_version_logo'] = $light_version_logofilename;
                unset($labelSettingData['old_light_version_logo']);
            }else{
                $labelSettingData['light_version_logo'] = isset($request->old_light_version_logo[$key]['old_light_version_logo']) ? $request->old_light_version_logo[$key]['old_light_version_logo'] : null;
                unset($labelSettingData['old_light_version_logo']);
            }

            if (isset($request['dark_version_logo'][$key]['dark_version_logo'])) {
                $dark_version_logo = $request['dark_version_logo'][$key]['dark_version_logo'];
                // $dark_version_logofilename = Str::random(20).".".$dark_version_logo->getClientOriginalExtension();
                $dark_version_logofilename = Str::random(20).".png";
                svgTOpngStoreFile($dark_version_logo, LabelSetting::$labelSettingPath.$authDetails->id."/",$dark_version_logofilename);
                // userProfileUploade($dark_version_logo, LabelSetting::$labelSettingPath.$authDetails->id."/",$dark_version_logofilename);
                if(isset($request->old_dark_version_logo[$key]['old_dark_version_logo'])){
                    removeFileFromStorage(LabelSetting::$labelSettingPath.$authDetails->id."/".$request->old_dark_version_logo[$key]['old_dark_version_logo']);
                }
                $labelSettingData['dark_version_logo'] = $dark_version_logofilename;
                unset($labelSettingData['old_dark_version_logo']);
            }else{
                $labelSettingData['dark_version_logo'] = isset($request->old_dark_version_logo[$key]['old_dark_version_logo']) ?  $request->old_dark_version_logo[$key]['old_dark_version_logo'] : null;
                unset($labelSettingData['old_dark_version_logo']);
            }

            if (isset($request['backgroung_image'][$key]['backgroung_image'])) {
                $backgroung_image = $request['backgroung_image'][$key]['backgroung_image'];
                // $backgroung_imagefilename = Str::random(20).".".$backgroung_image->getClientOriginalExtension();
                $backgroung_imagefilename = Str::random(20).".png";
                // userProfileUploade($backgroung_image, LabelSetting::$labelSettingPath.$authDetails->id."/",$backgroung_imagefilename);
                svgTOpngStoreFile($backgroung_image, LabelSetting::$labelSettingPath.$authDetails->id."/",$backgroung_imagefilename);
                if(isset($request->old_backgroung_image[$key]['old_backgroung_image'])){
                    removeFileFromStorage(LabelSetting::$labelSettingPath.$authDetails->id."/".$request->old_backgroung_image[$key]['old_backgroung_image']);
                }
                $labelSettingData['backgroung_image'] = $backgroung_imagefilename;
                unset($labelSettingData['old_backgroung_image']);
            }else{
                $labelSettingData['backgroung_image'] = isset($request->old_backgroung_image[$key]['old_backgroung_image']) ? $request->old_backgroung_image[$key]['old_backgroung_image'] : null;
                unset($labelSettingData['old_backgroung_image']);
            }

            if(isset($request->id[$key]['id'])){
                $this->dataUpdate($request->id[$key]['id'],$labelSettingData);
            }else{
                $this->dataAdd($labelSettingData);
            }
        }
        return redirect()->route('general-settings')->with('status', 'Label Setting Saved Sucessfully..!');
    }

    public function dataAdd($labelSettingData){
        $saveLabelSetting = new LabelSetting();
        $saveLabelSetting->fill($labelSettingData);
        $saveLabelSetting->save();
    }

    public function dataUpdate($id,$labelSettingData){
        unset($labelSettingData['token']);
        LabelSetting::where('id', '=', $id)->update($labelSettingData);
    }

    public function delete(Request $request){
        $authDetails = FacadesAuth::user();
        $getLabelSetting = LabelSetting::where('id', $request->id)->first();
        removeFileFromStorage(LabelSetting::$labelSettingPath.$authDetails->id."/".$getLabelSetting->light_version_logo);
        removeFileFromStorage(LabelSetting::$labelSettingPath.$authDetails->id."/".$getLabelSetting->dark_version_logo);
        removeFileFromStorage(LabelSetting::$labelSettingPath.$authDetails->id."/".$getLabelSetting->backgroung_image);

        $labelSettingDelete = LabelSetting::where('id', $request->id)->delete();
        return "Label Setting deleted successfully";
    }
}
