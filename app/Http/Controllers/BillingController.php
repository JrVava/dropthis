<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plan;
use App\Models\Coupon;
use App\Models\UserCoupon;
use Illuminate\Http\JsonResponse;
use Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Campaign;
use Carbon\Carbon;



use Validator;
use URL;
use Illuminate\Support\Facades\Session;
use Redirect;
use Input;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

use Stripe;


class BillingController extends Controller {

    private $_api_context;

    public function __construct() {
        $paypal_configuration = \Config::get('paypal');
        // dd(\Config::get('paypal'),$paypal_configuration['client_id'],$paypal_configuration['secret'],$paypal_configuration['settings']);

        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_configuration['client_id'], $paypal_configuration['secret']));
        $this->_api_context->setConfig($paypal_configuration['settings']);
        $this->middleware('auth');
    }

    public function index(){
        
        $plans = Plan::where('status','=',1)->get();
        return view('pages.billing.index',['plans'=>$plans]);
    }

    // Coupon
    public function verifyCoupon(Request $request){
        $authDetails = Auth::user();
        $dt = date("Y-m-d");

        $coupon = Coupon::with('userCoupon')
        ->where([
            ['code','=',$request->code],
            ['status','=',1],
            ['start_date','<=',$dt],
            ['expiry_date','>=',$dt]
        ])
        ->first();
        $success = false;
        $message = 'Coupon code not verified';
        $status = 400;
        $coupon_code = $request->code;
        if($coupon){
            if(empty($coupon->userCoupon->toArray())){
                $success = true;
                $message = 'Coupon code verified';
                $status = 200;
                $coupon_code = $request->code;
            } else {
                foreach($coupon->userCoupon as $userCoupon){
                    if($coupon->one_time_use == 1){  // one time use only 1 equal to one time use
                        $success = false;
                        $message = 'Coupon code already used!';
                    }elseif($userCoupon->user_id == $authDetails->id){ // check login user id exit in user_coupon table
                        $success = false;
                        $message = 'Sorry you already used this Coupon code!';
                    }else{
                        $success = true;
                        $message = 'Coupon code verified';
                        $status = 200;
                    }
                }
            } 
        }else{
            $success = false;
            $message = 'Coupon code not found';
        }
        $url = '';
        if($status == 200){
            $coupon = Coupon::where('code',$request->code)->first();
            if($coupon){
                $total_credit = $authDetails->credits + $coupon->no_of_credits;
                User::where('id','=',$authDetails->id)->update(['credits'=>$total_credit]);
                $userCoupon = new UserCoupon();
                $userCoupon->user_id = $authDetails->id;
                $userCoupon->coupon_id  = $coupon->id;
                $userCoupon->save();

                $order = new Order();
                $order->user_id = $authDetails->id;
                $order->coupon_id = $coupon->id;
                $order->payment_method = 'coupon';
                $order->status = 'success';
                $order->no_of_credits = $coupon->no_of_credits;
                $order->save();

                $success = true;
                $message = 'Coupon Applied! '. $coupon->no_of_credits .' credits added to your account!';
                $status = 200;
                //$url = route('campaigns');
                $url = route('campaigns');
                session()->flash('status','Coupon code applied');
            }else{
                $success = false;
                $message = 'Coupon not found!';
                $status = 400;
                $url = '';
            }
        }
        return response()->json([
            'success' => $success,
            'message' =>$message,
            'status' => $status,
            'url' => $url
        ]);
    }
    // Paypal
    public function getPaymentMethod(Request $request){
        $authDetails = Auth::user();
        $plan = Plan::find($request->plan_id);
        $success = false;
        $status = 400;
        $url = '';
        if($plan){
            $method = $request->method;
            // Paypal Code Start Here            
            if($method == "paypal"){
                $payer = new Payer();
                $payer->setPaymentMethod('paypal');
                $item_1 = new Item();
                $item_1->setName($plan->plan_name)
                ->setCurrency($plan->currency)
                ->setQuantity(1)
                ->setPrice($plan->price);
                $item_list = new ItemList();
                $item_list->setItems(array($item_1));

                $amount = new Amount();
                $amount->setCurrency($plan->currency)
                    ->setTotal($plan->price);

                $transaction = new Transaction();
                $transaction->setAmount($amount)
                    ->setItemList($item_list)
                    ->setDescription('Enter Your transaction description');

                $redirect_urls = new RedirectUrls();
                $redirect_urls->setReturnUrl(URL::route('status'))
                    ->setCancelUrl(URL::route('status'));

                $payment = new Payment();
                $payment->setIntent('Sale')
                    ->setPayer($payer)
                    ->setRedirectUrls($redirect_urls)
                    ->setTransactions(array($transaction));

                try {
                    $payment->create($this->_api_context);
                } catch (\PayPal\Exception\PPConnectionException $ex) {
                    if (\Config::get('app.debug')) {
                        //\Session::put('error','Connection timeout');
                        $url = URL::route('billing');
                        //return Redirect::route('billing');                
                    } else {
                        \Session::put('error','Some error occur, sorry for inconvenient');
                        $url = URL::route('billing');
                        //return Redirect::route('billing');                
                    }
                } 
                foreach($payment->getLinks() as $link) {
                    if($link->getRel() == 'approval_url') {
                        $redirect_url = $link->getHref();
                        break;
                    }
                }                
                Session::put('paypal_payment_id', $payment->getId());
                Session::put('plan_id', $request->plan_id);
                if(isset($redirect_url)) { 
                    $url = $redirect_url;
                    //return Redirect::away($redirect_url);
                }
                //\Session::put('error','Unknown error occurred');
                //return Redirect::route('billing');
            }
            // Paypal Code End Here
            $success = true;
            $status = 200;
        }
        return response()->json([
            'success' => $success,
            'status' => $status,
            'url' => $url
        ]);
    }

    // Paypal Response
    public function getPaymentStatus(Request $request) {

       // Get Current Log user Details
       $authDetails = Auth::user();
       // Get Plan Id using session
       $plan_id = Session::get('plan_id');
       // Get Paypal Payment Id using session
       $payment_id = Session::get('paypal_payment_id');
       // Get plan using plan id
       $plan = Plan::find($plan_id);
       // if user press return back button then below condition apply
       if (empty($request->input('PayerID')) || empty($request->input('token'))) {
           \Session::put('error','Payment failed');
           return Redirect::route('billing');
       }
       // Get payment details from paypal
       $payment = Payment::get($payment_id, $this->_api_context);
       $execution = new PaymentExecution();
       $execution->setPayerId($request->input('PayerID'));        
       $result = $payment->execute($execution, $this->_api_context);

       $transactions = $payment->getTransactions();
       $relatedResources = $transactions[0]->getRelatedResources();
       $sale = $relatedResources[0]->getSale();
       $saleId = $sale->getId();
       // If payment approved then below condtion apply
       if ($result->getState() == 'approved') {
           $getstatus = $sale->getState();
           $payal_response = json_encode($result->toArray());

           $orderStatus= 'success';
            $creditResponse = true;
       }else{
            $creditResponse = false;
           $orderStatus= 'fail';
           $getstatus = $sale->getState();
           $payal_response = json_encode($result->toArray());
       }
       $paymentMethod = 'paypal';
       $this->paymentResponse($creditResponse,$paymentMethod,$plan,$orderStatus,$payal_response,$payment_id,$result->getCreateTime(),$result->getUpdateTime());
       //exit;
       Session::forget('paypal_payment_id');
       Session::forget('plan_id');
       //return Redirect::route('billing');
       return redirect()->route('campaigns')->with('status', 'Payment done sucessfully..!');

    }

    // Stripe
    public function stripePayment(Request $request){
        $authDetails = Auth::user();
        $plan = Plan::find($request->plan_id);
        

        // Strip call for payment
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET_'.env('STRIPE_MODE')));
        $charge = Stripe\Charge::create ([
                "amount" => $plan->price * 100,
                "currency" => $plan->currency,
                "source" =>$request->stripe_token,
                "description" => "Dropthis Test"
        ]);
        if($charge['status'] == "succeeded"){
            $orderStatus= 'success';
            $creditResponse = true;
            // Strip success Response
            $stripe_response = json_encode($charge->toArray());
        }else{
            // Strip fail Response
            $orderStatus= 'fail';
            $creditResponse = false;
            $stripe_response = json_encode($charge->toArray());
        }
        $paymentMethod = 'stripe';
        $this->paymentResponse($creditResponse,$paymentMethod,$plan,$orderStatus, $stripe_response,$charge['id'],date('m/d/Y H:i:s', $charge['created']),date('m/d/Y H:i:s', $charge['created']));
        
        //return Redirect::route('billing');
        return redirect()->route('campaigns')->with('status', 'Payment done sucessfully..!');
    }

    public function paymentResponse($creditResponse=false,$paymentMethod = '',$plan = [],$orderStatus = '', $paymentResponse = '',$paymentId = '',$paymentCreated_at = '',$paymentUpdated_at = ''){
        $authDetails = Auth::user();
        $new_user = isNewUser($authDetails->id);
        
        $order = new Order();
        $order->user_id = $authDetails->id;
        $order->plan_id = $plan->id;
        $order->amount = $plan->price;
        $order->currency = $plan->currency;
        $order->no_of_credits = $plan->no_of_credits;
        $order->payment_method = $paymentMethod;
        $order->status = $orderStatus;
        $order->transaction_id = $paymentId;
        $order->paypal_response = $paymentResponse;
        $order->paypal_status = $orderStatus;
        $order->transaction_create_time = $paymentCreated_at;
        $order->transaction_update_time = $paymentUpdated_at;
        $order->save();
        $campaign = Campaign::where('user_id','=',$authDetails->id)->count();
        if($new_user && $campaign > 0 && $creditResponse){
            $substractCredit = $plan->no_of_credits - 1;
            User::where('id','=',$authDetails->id)->update(['credits'=>$substractCredit]);
        }elseif($creditResponse){
            $total_credit = $authDetails->credits + $plan->no_of_credits;          
            User::where('id','=',$authDetails->id)->update(['credits'=>$total_credit]);
        }
    }

    public function googlePayment(){
        return view('pages.billing.gpay');
    }
}