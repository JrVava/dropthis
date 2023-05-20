<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

use App\Models\Coupon;
use App\Models\UserCoupon;

use App\Models\Campaign;
use App\Models\CampaignTracks;
use App\Models\Feedback;
use App\Models\ReviewMapping;
use App\Models\CampaignClicks;
use App\Models\DownloadMapping;
use App\Models\EmailGroup;

use App\Models\Link;
use App\Models\Group;
use App\Models\Click;
use App\Models\LinksGroup;
use App\Models\Domain;



class UserManagementController extends Controller{
    public function index(Request $request){
        if ($request->ajax()) {     
            $users = User::where('user_role', '!=' , USER_ROLE_ADMIN)->get();
            $dt = Datatables::of($users);
            $dt->addIndexColumn(); // Add Index is call Index Column
            $dt->addColumn('action', function($row){     
                $btn = '<div class="btn btn-group"><a title="View" href="#modalAddTask" data-bs-toggle="modal" onClick="getUserDetails('.$row->id.')" class="btn btn-outline-success"><i class="fas fa-eye"></i></a>';
                $btn .= '<a title="Edit" href="'.route("user.edit",['id'=>$row->id]).'" class="btn btn-outline-success"><i class="fas fa-edit"></i></a>';
                $btn .= '<form method="post" action="'.route('user.delete',['id'=>$row->id]).'">'.csrf_field().' '.method_field("DELETE").'</form>
                        <a title="Delete" href="javascript:;" data-url="" class="btn btn-outline-danger plan-delete-link">
                            <i class="fas fa-trash"></i>
                        </a></div>';
                return $btn;
            })->editColumn('status', function($status) {
                return ($status->status == 1) ? "Active" : 'Inactive';
            })->editColumn('created_at', function($create) {
                return $create->created_at != null ? $create->created_at->format('F d,Y') : "-";
            })->editColumn('updated_at', function($update) {
                return $update->updated_at != null ? $update->updated_at->format('F d,Y') : "-";
            })->editColumn('can_submit_feedbacks', function($canSubmitFeedbacks) {
                return $canSubmitFeedbacks->can_submit_feedbacks == 1 ? "Yes" : "No";
            });
            return $dt->make(true);
        }
        return view('pages.user-management.index');
    }

    public function show(){
        $authDetails = [];
        return view('pages.user-management.form',['authDetails'=>$authDetails]); 
    }

    public function store(Request $request){
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $request->validate([
            'name' => 'required',
            'email' => ['required','unique:users,email,'.$request->id],
            'password' => ['required', 'string', 'min:8'],
            'cpassword' => 'nullable|same:password',
            'website' => ['nullable','url'],
		    'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:'.$max,
            'bg_image' => 'nullable|image|mimes:jpeg,png,jpg|max:'.$max,
            'credits' =>'nullable|numeric'
        ],[
            'name.required' => "Name is required",
            'email.required' => "Email is required",
            'email.unique' => "Email must be unique",
            'password.required' => "Password is required",
            'cpassword.same' => "Password and Confirm Password doesn't match",
            'website.url' => 'Website URL is not valid',
            'logo.image' => 'Logo image',
            'logo.mimes' => 'Logo extension must be .jped, .png, .jpg',
            'logo.max' => 'The Logo must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'bg_image.image' => 'Background should an image',
            'bg_image.mimes' => 'Background extension must be .jped, .png, .jpg',
            'bg_image.max' => 'The Background must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'credits.numeric' =>'Credit must be number'
        ]);
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logofilename = Str::random(20).".".$logo->getClientOriginalExtension();
            uploadFileToStorage($logo, User::$userProfilePath.$request->id."/".$logofilename);
        }
        if ($request->hasFile('bg_image')) {
            $bg_image = $request->file('bg_image');
            $bg_imagefilename = Str::random(20).".".$bg_image->getClientOriginalExtension();
            uploadFileToStorage($bg_image, User::$userProfilePath.$request->id."/".$bg_imagefilename);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->website = $request->website;
        $user->logo = $logofilename;
        $user->bg_color = $request->bg_color;
        $user->bg_image = $bg_imagefilename;
        $user->can_submit_feedbacks = isset($request->can_submit_feedbacks) ? 1 : 0;
        $user->credits =$request->credits;
        $user->user_role = USER_ROLE_USER;
        $user->status =  isset($request->user_status) ? 1 : 0;;
        $user->save();
        return redirect()->route('users')->with('status', "User Saved Sucessfully..!");
    }

    public function edit($id){
        $user = User::find($id);
        $path = User::$userProfilePath.$user->id.'/';
        return view('pages.user-management.form',['user'=>$user,'path'=>$path]);
    }

    public function delete($id){
        $campaigns = Campaign::where('user_id','=',$id)->get();
        foreach($campaigns as $campaign){
            CampaignTracks::where('campaign_id','=',$campaign->id)->delete();
            DownloadMapping::where('campaign_id','=',$campaign->id)->delete();
            Feedback::where('campaign_id','=',$campaign->id)->delete();
            ReviewMapping::where('campaign_id','=',$campaign->id)->delete();
            $campaignPath = Campaign::$campaignPath.$campaign->id;
            removeDirectoryFromStorage($campaignPath);
            CampaignClicks::where('campaign_id','=',$campaign->id)->delete();
        }
        Campaign::where('user_id','=',$id)->delete();
        $links = Link::where('created_by_id','=',$id)->get();
        foreach($links as $link){
            LinksGroup::where([['created_by_id','=',$id],['link_id','=',$link->id]])->delete();
            Click::where('links_id','=',$link->id)->delete();
        }
        Link::where('created_by_id','=',$id)->delete();
        Group::where('created_by_id','=',$id)->delete();
        UserCoupon::where('user_id','=',$id)->delete();
        $user = User::find($id);
        removeDirectoryFromStorage(User::$userProfilePath.$user->id);
        User::where('id','=',$id)->delete();
        return redirect()->route('users')->with('status', 'User deleted Sucessfully..!'); 
    }
    public function update(Request $request){
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $request->validate([
            'name' => 'required',
            'email' => ['required','unique:users,email,'.$request->id],
            'password' => ['nullable', 'string', 'min:8'],
            'cpassword' => 'nullable|same:password',
            'website' => ['nullable','url'],
		    'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:'.$max,
            'bg_image' => 'nullable|image|mimes:jpeg,png,jpg|max:'.$max,
            'credits' =>'nullable|numeric'
        ],[
            'name.required' => "Name is required",
            'email.required' => "Email is required",
            'email.unique' => "Email must be unique",
            'cpassword.same' => "Password and Confirm Password doesn't match",
            'website.url' => 'Website URL is not valid',
            'logo.image' => 'Logo image',
            'logo.mimes' => 'Logo extension must be .jped, .png, .jpg',
            'logo.max' => 'The Logo must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'bg_image.image' => 'Background should an image',
            'bg_image.mimes' => 'Background extension must be .jped, .png, .jpg',
            'bg_image.max' => 'The Background must not be greater than '.getFileSizeInReadable(ini_get('upload_max_filesize')).'.',
            'credits.numeric' =>'Credit must be number'
        ]);
        $user = User::find($request->id);
        $logofilename = $request->logo == '' ? $request->old_logo : null;
        $bg_imagefilename = $request->bg_image == '' ? $request->old_bg_image : null;

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logofilename = Str::random(20).".".$logo->getClientOriginalExtension();
            uploadFileToStorage($logo, User::$userProfilePath.$request->id."/".$logofilename);
            removeFileFromStorage(User::$userProfilePath.$request->id."/".$request->old_logo);
        }
        if ($request->hasFile('bg_image')) {
            $bg_image = $request->file('bg_image');
            $bg_imagefilename = Str::random(20).".".$bg_image->getClientOriginalExtension();
            uploadFileToStorage($bg_image, User::$userProfilePath.$request->id."/".$bg_imagefilename);
            removeFileFromStorage(User::$userProfilePath.$request->id."/".$request->old_bg_image);
        }
        
        User::where('id','=',$request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => isset($request->password) && $request->password != '' ? Hash::make($request->password) : $user->password,
            'website' => $request->website,
            'logo' => $logofilename,
            'bg_image' => $bg_imagefilename,
            'bg_color' => $request->bg_color,
            'can_submit_feedbacks' => isset($request->can_submit_feedbacks) ? 1 : 0,
            'credits' =>$request->credits,
            'status' => isset($request->user_status) ? 1 : 0
        ]);
        return redirect()->route('users')->with('status', 'User updated Sucessfully..!');
    }
    public function getUserDetails(Request $request){
        $user = User::find($request->id);
        if($user->logo != null){
            $logo = getFileFromStorage(User::$userProfilePath.$user->id.'/'.$user->logo);
        }else{
            $logo = null;
        }
        if($user->bg_image != null){
            $bg_image = getFileFromStorage(User::$userProfilePath.$user->id.'/'.$user->bg_image);
        }else{
            $bg_image = null;
        }
        return response()->json([
            'user' => $user,
            'logo' => $logo,
            'bg_image' =>$bg_image
        ]); 
    }
}
