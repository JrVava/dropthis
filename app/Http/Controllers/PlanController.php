<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Plan;
use DataTables;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    public function index(Request $request){
        if ($request->ajax()) {     
            $plans = Plan::get();
            $dt = Datatables::of($plans);
            $dt->addIndexColumn(); // Add Index is call Index Column
            $dt->addColumn('action', function($row){     
                $btn = '<div class="btn btn-group p-0"><a title="Edit" href="'.route("plan.edit",['id'=>$row->id]).'" class="btn btn-outline-success"><i class="fas fa-edit"></i></a>';
                $btn .= '<form method="post" action="'.route('plan.delete',['id'=>$row->id]).'">'.csrf_field().' '.method_field("DELETE").'</form>
                        <a title="Delete" href="javascript:;" data-url="" class="btn btn-outline-danger plan-delete-link">
                            <i class="fas fa-trash"></i>
                        </a></div>';
                return $btn;
            })->editColumn('status', function($status) {
                return ($status->status == 1) ? "Active" : 'Inactive';
            })->editColumn('created_at', function($create) {
                return $create->created_at != null ? $create->created_at->format('F d,Y') : "-";
            });
            return $dt->make(true);
        }
        return view('pages.plans.index');
    }

    public function store(Request $request){
        $validatedData = $request->validate([
		    'plan_name' => 'required',
		    'no_of_credits' => 'required|numeric',
		    'price' => 'required|numeric',
            'description' => 'required',
            'currency' => 'required',
		], [
		    'plan_name.required' => 'Plan is required',
		    'no_of_credits.required' => 'No of credit is required',
            'no_of_credits.numeric' => 'Please enter valid No of credit',
            'price.required' => 'Please enter valid email',
            'price.numeric' => 'Please enter valid plan',
		    'description' => 'Description is required',
            'currency.required' => 'Currency is required'
		]);
        if(isset($request->id)){
            Plan::where('id','=',$request->id)->update([
                'plan_name' => $request->plan_name,
                'no_of_credits' => $request->no_of_credits,
                'price' => $request->price,
                'description' => $request->description,
                'status' => isset($request->status) ? 1 : 0,
                'currency' => $request->currency
            ]);
            $msg = "Plan updated Sucessfully..!";
        }else{
            $planSave = new Plan();
            $planSave->plan_name = $request->plan_name;
            $planSave->no_of_credits = $request->no_of_credits;
            $planSave->price = $request->price;
            $planSave->description = $request->description;
            $planSave->status = isset($request->status) ? 1 : 0;
            $planSave->currency = $request->currency;
            $planSave->save();
            $msg = "Plan Saved Sucessfully..!";
        }
        return redirect()->route('plans')->with('status', $msg);
    }

    public function show() {
        return view('pages.plans.form');
    }

    public function delete($id){
        Plan::where('id','=',$id)->delete();
    	return  redirect()->route('plans')->with('status', 'Plan Deleted Sucessfully..!');
    }

    public function edit($id){
        $plan = Plan::find($id);
        return view('pages.plans.form',['plan'=>$plan]);
    }
}
