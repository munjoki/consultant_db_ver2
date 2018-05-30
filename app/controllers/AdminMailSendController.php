<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file send mail
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file send mail

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AdminMailSendController extends \BaseController {

	/**
	 * Admin Send Mail create form 
	 * 
	 * @method GET 
	 * @return HTML -> Send Mail Create Form  
	 */
	public function create()
	{
		$akdnuser = Akdn::select(DB::raw('concat(other_name," ",surname) As fullname'),'id')->lists('fullname','id');
		
		$adminuser = Admin::lists('name','id');
	
		$consultant = Consultant::select(DB::raw('concat(other_names," ",surname) As fullname'),'id')->lists('fullname','id');
		
		return View::make('admin.mails.create',compact('akdnuser','consultant','adminuser'));
	}
	
	/**
	 * Admin Send mail   validates and sends email
	 * 
	 * @method POST 
	 * @return if failed -> redirect back with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function store()
	{
		$data = Input::all();
		
		$rules = [
					'subject'=>'required',
					'message'=>'required',
					// 'akdn'=>'required_without_all:consultant,user',
					// 'consultant'=>'required_without_all:akdn,user',
					// 'user'=>'required_without_all:akdn,consultant'
				];
		$messages = [
					'subject.required'=>'Subject field required',
					'message.required'=>'message field required',
					// 'akdn.required_without_all'=>'akdn filed required',
					// 'consultant.required_without_all'=>'consultant filed required',
					// 'user.required_without_all'=>'user filed required'
					];

		$validator = Validator::make($data,$rules,$messages);

		$consultants = Input::get('consultant');
		
		$akdns = Input::get('akdn');
		$adminusers = Input::get('user');

		$subject = Input::get('subject');
		$message = Input::get('message');
		$emails =[];

		$akdnAll = Input::get('akdnselect');
		$consultantAll = Input::get('consultantselect');
		$userAll = Input::get('adminuserselect');
		
		if ($validator->fails()) {
			return Redirect::back()->withInput()
							   ->withErrors($validator->errors())
							   ->with('message', 'Something Went Wrong')
							   ->with('message_type', 'danger');
		}

		if(isset($akdnAll))
		{
			$akdn_All_email = Akdn::lists('email');		
			$emails = array_merge($emails,$akdn_All_email);
		}

		if(isset($consultantAll))
		{
			$consultant_All_email = Consultant::lists('email');		
			$emails = array_merge($emails,$consultant_All_email);
		}

		if(isset($userAll))
		{
			$user_All_email = Admin::lists('email');		
			$emails = array_merge($emails,$user_All_email);
		}

		if(!empty($consultants))
		{
			foreach ($consultants as $consultant) 
			{
				$consultant_email = Consultant::where('id',$consultant)->lists('email');
				$emails = array_merge($emails,$consultant_email);
			}
		}

		if(!empty($akdns))
		{
			foreach ($akdns as $akdn) 
			{
				$akdn_email = Akdn::where('id',$akdn)->lists('email');
				$emails = array_merge($emails,$akdn_email);
			}
		}

		if(!empty($adminusers))
		{
			foreach ($adminusers as $adminuser) 
			{
				$adminuser_email = Admin::where('id',$adminuser)->lists('email');
				$emails = array_merge($emails,$adminuser_email);
			}
		}
			
		if (!empty($emails)) 
		{
			foreach ($emails as $email) 
			{
				Mail::send('admin.mails.message',['msg'=>$message],function ($m) use ($subject,$email) {
			      	$m->subject($subject)
			        ->to($email);
	    		});
			}	
			return Redirect::back()->with('message', 'Your mail has been send')
						   				->with('message_type', 'success');
		}
		return Redirect::back()->with('message', 'Something wrong with mail config. Could not send email(s).')
						   			->with('message_type', 'danger');
	}
}
