<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Order;
use DataTables;

class orderHistoryController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index(Request $request){
       $authDetails = Auth::user();
       $visible_user_column = true;
       if($authDetails->user_role == USER_ROLE_USER){
            $visible_user_column = false;
        }
       if ($request->ajax()) {  
            $_histories = Order::with('plan','user');
                if($authDetails->user_role == USER_ROLE_USER){
                    $_histories->where('user_id','=',$authDetails->id);
                }
            $histories = $_histories->get();
            $dt = Datatables::of($histories);
            $dt->addIndexColumn(); // Add Index is call Index Column
            $dt->editColumn('transaction_create_time', function($create) {
                if($create->payment_method == "coupon"){
                    return $create->created_at != null ? $create->created_at->format('F d,Y') : "-";
                }else{
                    return $create->transaction_create_time != null ? $create->transaction_create_time->format('F d,Y') : "-";
                }
            })->editColumn('plan',function($plan){
                return $plan->plan == null ? "-" : $plan->plan->plan_name;
            })->editColumn('amount',function($amount){
                return $amount->amount == null ? "-" : $amount->amount;
            })->editColumn('currency',function($currency){
                return $currency->currency == null ? "-" : $currency->currency;
            })->editColumn('payment_method',function($payment_method){
                return strtoupper($payment_method->payment_method);
            })->editColumn('status',function($status){
                return strtoupper($status->status);
            });

            $dt->editColumn('user',function($users){
                return $users->user->name;
            });
            return $dt->make(true);
       }
       return view('pages.order-history.index', compact('visible_user_column'));
    }
}
