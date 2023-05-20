<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use DataTables;
use Carbon\Carbon;
use Auth;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {
            $authDetails = Auth::user();
            $_store = Store::orderBy('created_at', 'desc');
            if($authDetails->user_role == USER_ROLE_USER){
                $_store->where('user_id','=',$authDetails->id);
            }
            $store = $_store->get();
            $dt = Datatables::of($store);
            $dt->addIndexColumn(); // Add Index is call Index Column
            $dt->addColumn('action', function($row){                
                $btn = '<div class="btn btn-group p-0">';
                        $btn .= '<a title="Edit" href="'.route("store.edit",['id'=>$row->id]).'" class="btn btn-outline-success"><i class="fas fa-edit"></i></a>';
                        $btn .= '<form method="post" action="'.route('store.delete',['id'=>$row->id]).'">'.csrf_field().' '.method_field("DELETE").'</form>
                                <a title="Delete" href="javascript:;" data-url="" class="btn btn-outline-danger store-delete">
                                    <i class="fas fa-trash"></i>
                                </a>';
                $btn .= '</div>';
                return $btn;
            })->editColumn('created_at', function($create) {
                return $create->created_at != null ? $create->created_at->format('F d,Y') : "-";
            });
            return $dt->make(true);
        }
        return view('pages.store.index');
    }

    public function show(){
        return view('pages.store.form');
    }
    public function save(Request $request){
        $authDetails = Auth::user();
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $rules = [
            'storename' => 'required',
            'light_logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:'.$max,
            'dark_logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:'.$max,
            'base_url' => 'required|url',
		];
        $messages = [
            'storename.required' => 'Store Name is reqiured',

            'light_logo.required' => 'Light Logo is required',
            'light_logo.image' => 'Light Logo should be image',
            'light_logo.mimes' => 'Light Logo  extension must be .jped, .png, .jpg, .svg, .webp',

            'dark_logo.required' => 'Dark Logo is required',
            'dark_logo.image' => 'Dark Logo should be image',
            'dark_logo.mimes' => 'Dark Logo  extension must be .jped, .png, .jpg, .svg, .webp',

            'base_url.urls.required' => 'Base URL is required',
            'base_url.url' => 'Invalid Base URL',
		];
       //dd($request->all());
        $validatedData = $request->validate($rules, $messages);

        $store = new Store;
        if ($request->hasFile('light_logo')) {
            $light_logo = $request->file('light_logo');
            $light_logoFileName = Str::random(20).".".$light_logo->getClientOriginalExtension();
            $store->light_logo= $light_logoFileName;
        }

        if ($request->hasFile('dark_logo')) {
            $dark_logo = $request->file('dark_logo');
            $dark_logoFileName = Str::random(20).".".$dark_logo->getClientOriginalExtension();
            $store->dark_logo= $dark_logoFileName;
        }
        $store->user_id = $authDetails->id;
        
        $store->fill($request->all());
        $store->save();
        if ($request->hasFile('light_logo')) {
            uploadFileToStorage($light_logo, Store::$storePath.$store->id."/light/".$light_logoFileName, null,null,null);
            Store::where('id','=',$store->id)->update([
                'light_logo' => url('/').'/storage/'.Store::$storePath.$store->id."/light/".$light_logoFileName,
            ]);
        }
        if ($request->hasFile('dark_logo')) {
            uploadFileToStorage($dark_logo, Store::$storePath.$store->id."/dark/".$dark_logoFileName, null,null,null);
            Store::where('id','=',$store->id)->update([
                'dark_logo' => url('/').'/storage/'.Store::$storePath.$store->id."/dark/".$dark_logoFileName,
            ]);
        }
        return redirect()->route('stores')->with('status', 'Store Saved Sucessfully..!'); 
    }

    public function delete($id){
        $storePath = Store::$storePath.$id;        
        removeDirectoryFromStorage($storePath);
        Store::where('id','=',$id)->delete();
    	return  redirect()->route('stores')->with('status', 'Store Deleted Sucessfully..!');
    }

    public function edit($id){
        $authDetails = Auth::user();
        $store = Store::where('id',$id)->first();
        if(empty($store)){
            return redirect()->route('stores')->with('error', "Sorry Store doesn't exit");
        }
        $storePath = Store::$storePath.$store->id;
        return view('pages.store.form',['store'=>$store,'storePath'=>$storePath]);
    }


    public function update(Request $request){
        $max = getFileSizeInBytes(ini_get('upload_max_filesize')) / 1024;
        $rules = [
            'storename' => 'required',
            'light_logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:'.$max,
            'dark_logo' => 'required|image|mimes:jpeg,png,jpg,svg,webp|max:'.$max,
            'base_url' => 'required|url',
		];

        if($request->old_light_logo == "" || !empty($request->old_light_logo)){
            unset($rules['light_logo']);
        }
        if($request->old_dark_logo == "" || !empty($request->old_dark_logo)){
            unset($rules['dark_logo']);
        }
        
        $messages = [
            'storename.required' => 'Store Name is reqiured',

            'light_logo.required' => 'Light Logo is required',
            'light_logo.image' => 'Light Logo should be image',
            'light_logo.mimes' => 'Light Logo  extension must be .jped, .png, .jpg, .svg, .webp',

            'dark_logo.required' => 'Dark Logo is required',
            'dark_logo.image' => 'Dark Logo should be image',
            'dark_logo.mimes' => 'Dark Logo  extension must be .jped, .png, .jpg, .svg, .webp',

            'base_url.urls.required' => 'Base URL is required',
            'base_url.url' => 'Invalid Base URL',
		];
        $validatedData = $request->validate($rules, $messages);

        $authDetails = Auth::user();
        $storePath = Store::$storePath.$request->id.'/';

        if ($request->hasFile('light_logo')) {
            $light_logo = $request->file('light_logo');
            $light_logofilename = Str::random(20).".".$light_logo->getClientOriginalExtension();
            uploadFileToStorage($light_logo,  $storePath.'light/'.$light_logofilename, null,null,null);
            $light_logo= url('/').'/storage/'.$storePath."light/".$light_logofilename;

            $removeOldlight_logoString = str_replace(url('/').'/storage/uploads/stores/'.$request->id.'/light/',"",$request->old_light_logo);
            
            removeFileFromStorage($storePath.'light/'.$removeOldlight_logoString);
        }else{
            $light_logo = $request->old_light_logo;
        }

        if ($request->hasFile('dark_logo')) {
            $dark_logo = $request->file('dark_logo');
            $dark_logofilename = Str::random(20).".".$dark_logo->getClientOriginalExtension();
            uploadFileToStorage($dark_logo,  $storePath.'dark/'.$dark_logofilename, null,null,null);
            $dark_logo = url('/').'/storage/'.$storePath."dark/".$dark_logofilename;

            $removeOlddark_logoString = str_replace(url('/').'/storage/uploads/stores/'.$request->id.'/dark/',"",$request->old_dark_logo);                
            removeFileFromStorage($storePath.'dark/'.$removeOlddark_logoString);
        }else{
            $dark_logo = $request->old_dark_logo;
        }
        
        Store::where('id','=',$request->id)->update([
            'storename' => $request->storename,
            'light_logo' => $light_logo,
            'dark_logo' => $dark_logo,
            'base_url' => $request->base_url,
            'user_id' => $authDetails->id,
        ]);
        return redirect()->route('stores')->with('status', 'Store Updated Sucessfully..!'); 
    }
}
