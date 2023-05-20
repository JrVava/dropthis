<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Coupon;
use DataTables;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function index(Request $request){
        if ($request->ajax()) {     
            $coupons = Coupon::get();
            $dt = Datatables::of($coupons);
            $dt->addIndexColumn(); // Add Index is call Index Column
            $dt->addColumn('action', function($row){     
                $btn = '<div class="btn btn-group p-0"><a title="Edit" href="'.route("coupon.edit",['id'=>$row->id]).'" class="btn btn-outline-success"><i class="fas fa-edit"></i></a>';
                $btn .= '<form method="post" action="'.route('coupon.delete',['id'=>$row->id]).'">'.csrf_field().' '.method_field("DELETE").'</form>
                        <a title="Delete" href="javascript:;" data-url="" class="btn btn-outline-danger plan-delete-link">
                            <i class="fas fa-trash"></i>
                        </a></div>';
                return $btn;
            })->editColumn('start_date', function($startDate) {
                return $startDate->start_date != null ? date('M d,Y', strtotime($startDate->start_date)) : "-";
            })->editColumn('expiry_date', function($expiryDate) {
                return  $expiryDate->expiry_date != null ? date('M d,Y', strtotime($expiryDate->expiry_date)) : "-";
            })->editColumn('one_time_use', function($oneTimeUse) {
                return  $oneTimeUse->one_time_use == 0 ? "Multiple use" : "One time use";
            })->editColumn('status', function($status) {
                return ($status->status == 1) ? "Active" : 'Inactive';
            })->editColumn('created_at', function($create) {
                return $create->created_at != null ? $create->created_at->format('F d,Y') : "-";
            });
            return $dt->make(true);
        }
        return view('pages.coupon.index');
    }
    public function show() {
        return view('pages.coupon.form');
    }
    public function store(Request $request){
        $validatedData = $request->validate([
		    'code' => ['required','unique:coupons,code,'.$request->id],
		    'no_of_credits' => 'required|numeric',
		    'start_date' => 'required',
		], [
		    'code.required' => 'Coupon code is required',
            'code.unique' => 'Coupon code is already taken',
		    'no_of_credits.required' => 'No of credit is required',
            'start_date.required' => 'Start date is required',
		]);

        $createStartDate = date_create($request->start_date);
        $start_date = date_format($createStartDate,"Y-m-d");

        if(isset($request->expiry_date)){
            $createExpiryDate = date_create($request->expiry_date);
            $expiry_date = date_format($createExpiryDate,"Y-m-d");
        }else{
            $expiry_date = null;
        }
        if(isset($request->id)){
            Coupon::where('id','=',$request->id)->update([
                'code' => $request->code,
                'no_of_credits' => $request->no_of_credits,
                'start_date' => $start_date,
                'expiry_date' => $expiry_date,
                'one_time_use' => isset($request->one_time_use) ? 1 : 0,
                'status' => isset($request->status) ? 1 : 0,
            ]);
            $msg = "Coupon updated Sucessfully..!";
        }else{
            $couponSave = new Coupon();
            $couponSave->code = $request->code;
            $couponSave->no_of_credits = $request->no_of_credits;
            $couponSave->start_date = $start_date;
            $couponSave->expiry_date = $expiry_date;
            $couponSave->one_time_use = isset($request->one_time_use) ? 1 : 0;
            $couponSave->status = isset($request->status) ? 1 : 0;
            $couponSave->save();
            $msg = "Coupon Saved Sucessfully..!";
        }
        return redirect()->route('coupons')->with('status', $msg);
    }
    public function delete($id){
        Coupon::where('id','=',$id)->delete();
    	return  redirect()->route('coupons')->with('status', 'Coupon Deleted Sucessfully..!');
    }

    public function edit($id){
        $coupon = Coupon::find($id);
        return view('pages.coupon.form',['coupon'=>$coupon]);
    }
}
