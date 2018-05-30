<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles inviting consultants to register with the database
------------------------------------------------------------------------------------------------------------------------------------------*/ 
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file handles inviting consultants to register with the database

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 


class ConsultantSponsorController extends BaseController{
	
	/**
	 * Admin akdn validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function store(){

		if( Request::ajax() ){
			$post_data = Input::all();
			
			$message_by = nl2br($post_data['message_by']);
			$specializations = Input::get('specialization');
			

			$validator = Validator::make(Input::all(), ConsultantSponsor::$rules, ConsultantSponsor::$messages);

			if( $validator->fails() ){

				$errors = $validator->errors()->toArray();

				return Response::json(array('success' => false, 'errors' => $errors));
			}

			$sponsored_consultant = new ConsultantSponsor(Input::except('specialization'));
		
			$sponsored_consultant_email = $sponsored_consultant->email;
			$sponsored_consultant_name = $sponsored_consultant->name;

			// $token = str_random(10);

			// $sponsored_consultant->token = $token;
			//$sponsored_consultant_message = $sponsored_consultant->message_by;

			$sponsored_consultant->akdn_id = Auth::akdn()->get()->id;

			$akdn_name = AKDN::select(\DB::raw('CONCAT(other_name, " ", surname) AS full_name'))->where('id',$sponsored_consultant->akdn_id)->pluck('full_name');
			
			if (!empty($post_data['invited_on_behalf_of'])) {
				
				$sponsored_consultant->invited_on_behalf = $post_data['invited_on_behalf_of'];
			}
			else
			{
				$sponsored_consultant->invited_on_behalf = $akdn_name;
			}
			$sponsored_consultant->message_by = $message_by;
			$sponsored_consultant->save();
			
			$specialization_list = array();
			
			if( !empty($specializations) && isset($specializations[0]) && !empty($specializations[0]) ) {

				foreach ($specializations as $specialization_id) {
					$specialization['specialization_id'] = $specialization_id;
					array_push($specialization_list, $specialization);
				}

				$sponsored_consultant->consultantSpecialization()->createMany($specialization_list);	
			}

			$sponsored_consultant = $sponsored_consultant->toArray();
			
			$akdn_name = array('akdn_name' => $akdn_name);
			$sponsored_consultant = array_merge($sponsored_consultant,$akdn_name);
			$mail_data = $sponsored_consultant;
			
			Mail::send('emails.user.invitation', $mail_data, function($message) use ($sponsored_consultant_email,$sponsored_consultant_name){
				$message->to( $sponsored_consultant_email, $sponsored_consultant_name )
						->subject("Invitation to join AKDN's Consultant Database");
			});

			return Response::json(array('success' => true));
		}


	}
	// public function verify($token){

	// $verify_token = \ConsultantSponsor::where('token',$token)->first();

	// 	if ( $verify_token != null )
	// 	{
	// 		$verify_token->confirmed = 1;
	// 		$verify_token->save();

	// 		return \Redirect::route('user.register',array('token' => $token));
	// 	}
	// 	else{

	// 		echo 'Your Verify Token is Mismatched....!';
	// 	}

	// }
	
}