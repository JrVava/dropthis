<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(){
        $authDetails = Auth::user();
        $path = User::$userProfilePath.$authDetails->id.'/';
        return view('pages.user.profile',['authDetails'=>$authDetails,'path'=>$path]);
    }

    public function save(Request $request){
        $authDetails = Auth::user();
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;

        $validatedData = $request->validate([
		    'name' => 'required',
		    'website' => ['required','url'],
		    'logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:'.$max,
            'bg_image' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:'.$max
		],[
		    'name.required' => 'Name is required',
		    'website.required' => 'Website URL is required',
		    'website.url' => 'Website URL is not valid',
            'logo.image' => 'Logo must be image',
            'logo.mimes' => 'Logo extension must be .jped, .png, .jpg',
            'logo.max' => 'The Logo must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'bg_image.image' => 'Background should an image',
            'bg_image.mimes' => 'Background extension must be .jped, .png, .jpg',
            'bg_image.max' => 'The Background must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
		]);
        
        $logofilename = $request->logo == '' ? $request->old_logo : null;
        $bg_imagefilename = $request->bg_image == '' ? $request->old_bg_image : null;
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logofilename = Str::random(20).".".$logo->getClientOriginalExtension();
            userProfileUploade($logo, User::$userProfilePath.$authDetails->id."/",$logofilename);
            removeFileFromStorage(User::$userProfilePath.$authDetails->id."/".$request->old_logo);
        }
        if ($request->hasFile('bg_image')) {
            $bg_image = $request->file('bg_image');
            $bg_imagefilename = Str::random(20).".".$bg_image->getClientOriginalExtension();
            userProfileUploade($bg_image, User::$userProfilePath.$authDetails->id."/",$bg_imagefilename);
            removeFileFromStorage(User::$userProfilePath.$authDetails->id."/".$request->old_bg_image);
        }
        User::where('id','=',$authDetails->id)->update([
            'name' => $request->name,
            'website' => $request->website,
            'bg_color' => $request->bg_color,
            'logo' => $logofilename,
            'bg_image' => $bg_imagefilename
        ]);
        return redirect()->route('general-settings')->with('status', 'Profile Updated Sucessfully..!');
    }
}
