<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Domain;
class DomainController extends Controller
{
    //
    public function index(){
    	$domain = Domain::all();
    	return view('pages.domain.list',['domain'=>$domain]);
    }

    public function show(){
    	return view('pages.domain.form');	
    }

    public function save(Request $request){
    	$validatedData = $request->validate([
		    'host' => ['required','url'],
		], [
		    'host.required' => 'Host URL is required',
		    'host.url' => 'Host URL is not valid',
		]);
		$authId =  auth()->id();
		$domains = new Domain();
		$domains->host = $request->host;
		$domains->status = isset($request->status) ? $request->status : 0;
		$domains->created_by_id  = $authId;
		$domains->updated_by_id  = $authId;
		$domains->save();
		return redirect()->route('domains')->with('status', 'Domain Saved Sucessfully..!');
    }

    public function edit($id){
    	$domain = Domain::find($id);
    	return view('pages.domain.form',['domain'=>$domain,'actionType'=>'edit']);
    }

    public function update(Request $request){
    	$validatedData = $request->validate([
		    'host' => ['required','url'],
		], [
		    'host.required' => 'Host URL is required',
		    'host.url' => 'Host URL is not valid',
		]);
		$authId =  auth()->id();
    	$id = $request->id;
		$host = $request->host;
		$status = $request->status;

		Domain::where('id','=',$request->id)->update([
    		'host' => $request->host,
    		'status' => isset($request->status) ? $request->status : 0,
			'created_by_id' => $authId,
			'updated_by_id' => $authId
    	]);
    	return redirect()->route('domains')->with('status', 'Domain Updated Sucessfully..!');
    }

	public function delete($id){
		Domain::where('id','=',$id)->delete();
		return  redirect()->route('domains')->with('status', 'Domain Deleted Sucessfully..!');
	}
}
