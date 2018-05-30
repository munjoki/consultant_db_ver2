<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this controller file handles the AKDN user registration
------------------------------------------------------------------------------------------------------------------------------------------*/ 
/*----------------------------------------------------------------------------------------------------------------------------------------
	Akdn Module: 
        This controller file handles the AKDN user registration

    * @author     thinkTANKER
    * @version    AKDN MER Consultant Database Version 1.0
    * @email      hello@thinktanker.in
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AkdnController extends BaseController{
	
	 /**
	 * Akdn registration form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn registration form 
	 */
	public function register(){

		$country        = Country::orderBy('country_des','asc')->lists('country_des','id');

		$agencies 		= Agency::lists('acronym','id');

		return View::make('akdn.register',compact('country','agencies'));
	}

		/**
	 * Akdn user validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @return if failed -> redirect back with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */

	public function store(){

		$akdn = Input::all();

		$nationalities = Input::get('nationality');

		$agencies = Input::get('consultant_agencies',array());

		$validation = Validator::make($akdn, Akdn::$rules,Akdn::$messages);

		if ($validation->passes()){

			$akdn = new Akdn();
			
			$email 	   = Input::get('email');
			$user_name = Input::get('other_name');
			$raw_password = Str::random(8);
			$token  = Str::random(80);
			$surname = Input::get('surname');
			$akdn->surname = $surname;
			$akdn->other_name = $user_name;
			$akdn->email = $email ;
			$akdn->password = Hash::make($raw_password);
			$akdn->remember_token = $token;
			$user_name = $user_name.' '.$surname;
			$userinfo  = array(
				'email' 	=> $email,
				'user_name' => $user_name,
				'password'  => $raw_password,
				'token'     => $token,
			);

			Mail::send('emails.akdn.registration', $userinfo, function($message) use ($email, $user_name){
				$message->to($email, $user_name)
						->subject("Welcome to AKDN's MER Consultant Database");
			});

			$akdn->save();

			if (!empty($agencies)) {
				$nationality_list = array();

				foreach ($nationalities as $nationality_id) {
					$nationality['country_id'] = $nationality_id;
					array_push($nationality_list, $nationality);
				}

				$agency_list = array();

				foreach ($agencies as $agency_id) {
					$agency_list['agency_id'] = $agency_id;
				}

				$akdn->Akdnnationality()->attach($nationality_list);
				$akdn->Agency()->attach($agency_list);
			}

			return Redirect::back()->with('message', 'Thank you for registering with our consultant database. A confirmation has been sent to the primary email address you provided. Please click on the link contained in this email in order to complete your registration.')
								   ->with('message_type', 'success');
		 } else {
				return Redirect::back()
				->withInput()
				->withErrors($validation)
				->with('message', 'Some required field(s) have not been filled. Please correct the error(s)!')
				->with('message_type', 'danger');
			
		}
	}
}