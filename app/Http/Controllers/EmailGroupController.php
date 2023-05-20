<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailGroup;
use Auth;
use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EmailImport;
use Illuminate\Support\Str;
use Carbon\Carbon;


class EmailGroupController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function index(Request $request){
        $groups = EmailGroup::groupby('group')->select('group')->get();
        if ($request->ajax()) {            
            $query = EmailGroup::orderBy('created_at', 'desc');

            if (!empty($request->group)) {
                $query->where('group','=',$request->group);
            }
            
            $data = $query->get();
            $dt = Datatables::of($data);
            $dt->addIndexColumn(); // Add Index is call Index Column
            if(Auth::user()->user_role == USER_ROLE_ADMIN){
                
                $dt->addColumn('action', function($row){     
                    $btn = '<div class="btn btn-group"><a title="Edit" href="'.route("email.edit",['id'=>$row->id]).'" class="btn btn-outline-success"><i class="fas fa-edit"></i></a>';
                    if($row->status == 1){
                        $btn .= '<label title="Active" data-url="'.route('email.status',['id'=>$row->id,'status'=>0]).'" class="btn btn-outline-green email-status">
                                    <i class="fas fa-toggle-on"></i>
                                </label>';
                    }else{
                        $btn .= '<label title="Inactive" data-url="'.route('email.status',['id'=>$row->id,'status'=>1]).'" class="btn btn-outline-danger email-status">
                                <i class="fas fa-toggle-off"></i>
                            </label>';
                    }
                    $btn .= '<form method="post" action="'.route('email.delete',['id'=>$row->id]).'">'.csrf_field().' '.method_field("DELETE").'</form>
                            <a title="Delete" href="javascript:;" data-url="" class="btn btn-outline-danger delete-link">
                                <i class="fas fa-trash"></i>
                            </a></div>';
                    return $btn;
                });
            }
            $dt->editColumn('status', function($status) {
                return ($status->status == 1) ? "Active" : 'Inactive';
            })->editColumn('created_at', function($create) {
                return $create->created_at->format('F d,Y');
            })->editColumn('last_send', function($last_send) {
                return $last_send->last_send == null ? "-" : date('M d,Y', strtotime($last_send->last_send));
            });
            return $dt->make(true);
        }
        return view('pages.email.list',['groups'=>$groups]);
    }

    public function show() {
        return view('pages.email.form');
    }

    public function save(Request $request){
        $validatedData = $request->validate([
		    'artist' => 'required|unique:links,slug',
		    'email' => 'required|email',
		    'group' => 'required',
		], [
		    'artist.required' => 'Artist is required',
		    'email.required' => 'Email is required',
            'email.email' => 'Please enter valid email',
		    'group' => 'Group is required'
		]);
        $authDetails = Auth::user();
        if(isset($request->id)){
            EmailGroup::where('id','=',$request->id)->update([
                'artist' => $request->artist,
                'email' => $request->email,
                'group' => $request->group,
                'user_id' => $authDetails->id,
                'status' => !empty($request->status) ? 1 : 0,
                'pass_key' => md5($request->email.$request->group)
            ]);
        }else{
            $emailGroup = new EmailGroup();
            $emailGroup->artist = $request->artist;
            $emailGroup->email = $request->email;
            $emailGroup->group = $request->group;
            $emailGroup->user_id = $authDetails->id;
            $emailGroup->status = !empty($request->status) ? 1 : 0;
            $emailGroup->pass_key = md5($request->email.$request->group);
            $emailGroup->save();
        }       
        
        return redirect()->route('emails')->with('status', 'Email Saved Sucessfully..!');
    }

    public function edit($id){
        $emailGroup = EmailGroup::find($id);
        return view('pages.email.form',['emailGroup'=>$emailGroup]);
    }

    public function delete($id){
        EmailGroup::where('id','=',$id)->delete();
    	return  redirect()->route('emails')->with('status', 'Email Deleted Sucessfully..!');
    }

    public function status($id,$status){
        EmailGroup::where('id','=',$id)->update(['status' => $status]);
        return redirect()->route('emails')->with('status', 'Status changed Sucessfully..!');
    }
    
    public function csvUpload(Request $request){
        Excel::import(new EmailImport, $request->file('csv')->store('temp'));
        return redirect()->route('emails')->with('status', 'CSV file uploaded Sucessfully..!');
    }
}
