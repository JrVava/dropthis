<?php

namespace App\Http\Controllers;

use App\Models\LabelSetting;
use App\Models\SMTP;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class SinglePageController extends Controller
{
    public function index()
    {
        $authDetails = FacadesAuth::user();
        $smtp = SMTP::where('user_id', '=', $authDetails->id)->first();
        $labelSettings = LabelSetting::where('user_id', '=', $authDetails->id)->get();
        $labelSettingsPath = LabelSetting::$labelSettingPath.$authDetails->id.'/';
        $path = User::$userProfilePath.$authDetails->id.'/';
        return view('pages.singlepage.index', compact('smtp', 'labelSettings', 'labelSettingsPath','path', 'authDetails'));
    }
}
