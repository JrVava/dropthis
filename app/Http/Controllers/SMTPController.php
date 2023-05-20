<?php

namespace App\Http\Controllers;

use App\Models\SMTP;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class SMTPController extends Controller
{
    // public function index(){
    //     $smtp = SMTP::first();
    //     return view('pages.smtp.index',compact('smtp'));
    // }
    public function store(Request $request)
    {
        $rules = [
            'mail_mailer' => 'required',
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_encryption' => 'required',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required',
        ];
        $messages = [
            'mail_mailer.required' => 'Mailer is required',
            'mail_host.required' => 'Host is required',
            'mail_port.required' => 'Port is required',
            'mail_username.required' => 'Username is required',
            'mail_password.required' => 'Password is required',
            'mail_encryption.required' => 'Encryption is required',
            'mail_from_address.required' => 'Sender Address is required',
            'mail_from_address.email' => 'Sender EMail is not valid',
            'mail_from_name.required' => 'Sender Name is required',
        ];
        $validatedData = $request->validate($rules, $messages);
        $authDetails = FacadesAuth::user();
        
        $data = [
            'mail_mailer' => $request->mail_mailer,
            'mail_host' => $request->mail_host,
            'mail_port' => $request->mail_port,
            'mail_username' => $request->mail_username,
            'mail_password' => $request->mail_password,
            'mail_encryption' => $request->mail_encryption,
            'mail_from_address' => $request->mail_from_address,
            'mail_from_name' => $request->mail_from_name,
            'user_id' => $authDetails->id
        ];
        if(isset($request->id) && !empty($request->id)) {
            SMTP::where('id', '=', $request->id)->update($data);
        } else {
            SMTP::create($data);
        }
        $dropthis_mail_or_own_mail = isset($request->dropthis_mail_or_own_mail) && $request->dropthis_mail_or_own_mail == 'on' ? 1 : 0;
        User::where([
                ['id', '=', $authDetails->id],
                ['user_role','!=',USER_ROLE_ADMIN]
            ])->update(['dropthis_mail_or_own_mail'=>$dropthis_mail_or_own_mail]);
        return redirect()->route('general-settings')->with('status', 'SMTP Saved Sucessfully..!');
    }
}
