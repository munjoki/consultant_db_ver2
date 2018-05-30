<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles consultant details on consultancies registered
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Consultant Module: 
        This controller file handles consultant details on consultancies registered

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class ConsultantController extends BaseController {
	
	/**
	 * Consultant Registration form 
	 * 
	 * @method GET 
	 * @return HTML -> Consultant Registration form 
	 */
	public function register(){

		$city           = City::lists('city_des','id');
		$country        = Country::orderBy('country_des','asc')->lists('country_des','id');
		$language       = Language::lists('lang_acr','id');
		$skills         = Skill::lists('skills_des','id');
		$specialization = Specialization::lists('spec_des','id');
		$country_worked = $country;
		$agencies 		= Agency::orderBy('order_id','asc')->lists('acronym','id');
	
		return View::make('user.register',compact('city','country','language', 'skills','specialization','country_worked','agencies','token'));
		//return View::make('user.register1',compact('city','country','language', 'skills','specialization','country_worked'));
	}

	/**
	 * Consultant validates, stores record in database and sends email
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	
	public function store(){
		$consultant = Input::all();
		$nationalities = Input::get('nationality');
		$website_url = $consultant['website_url'];
		// $verifytoken = $consultant['verifytoken'];
		$skills = Input::get('skill',array());
		$specializations = Input::get('specialization', array());
		$worked_countries = Input::get('country_worked',array());
		$consultant_agencies = Input::get('consultant_agencies',array());
		$languages = $consultant['languages']['languages'];

		$validation = Validator::make($consultant,Consultant::$rules,Consultant::$messages);

		if ($validation->passes()){

			$consultant = new Consultant(Input::except('resume','terms_conditions'));
			$email 	   = $consultant->email;
			$user_surname = Input::get('surname');
			$user_name = Input::get('other_names');
			$raw_password = Str::random(8);
			$token  = Str::random(80);
			$user = new User();

			$password  = $user->password;
			$user->email = $email ;
			$user->password = Hash::make($raw_password);
			$user->remember_token = $token;
			$userinfo  = array(
				'email' 	=> $email,
				'user_name' => $user_name,
				'password'  => $raw_password,
				'token'     => $token,
			);

	        if(Input::hasFile('resume')){

	            $file = Input::file('resume');
	            $destinationPath = public_path().'/upload/resume/';
	            $extension = $file->getClientOriginalExtension();
	            $user_fullname = $user_name." ".$user_surname;
				$file_name = Str::slug($user_fullname,'_');
	            // $microtime = microtime();
	            // $search = array('.',' ');
	            // $microtime = str_replace($search, "_", $microtime);
	            $doc_name = $file_name.'.'.$extension;

	            $file->move($destinationPath,$doc_name);
	           
	            $consultant->resume = $doc_name;
	            
	        }

			Mail::send('emails.user.registration', $userinfo, function($message) use ($email, $user_name){
				$message->to($email, $user_name)
						->subject("Welcome to AKDN's MER Consultant Database");
			});

			$user->save();

			$consultant->id = $user->id;

			$consultant->save();
		    
			// ConsultantSponsor::where('token',$verifytoken)->update(['email' => $consultant['email']]);
			$consultantSponsor = ConsultantSponsor::select('akdn_id')->where('email',$consultant->email)->first();
		
			if($consultantSponsor)
			{
				Consultant::where('email',$consultant->email)->update(['invited_by'=> $consultantSponsor->akdn_id]);
				//ConsultantSponsor::where('email',$consultant->email)->update(['joined_status'=>'1']);
			}

			if($languages)
			{
				foreach ($languages as $key => $language) 
				{
					if($language['language'] != ""){

						$consultant_language = new ConsultantLanguage();
						$consultant_language->consultant_id = $user->id;
						$consultant_language->language_id = $language['language'];
						// $consultant_language->language_level = $language['lang_level'];
						$consultant_language->speaking_level = $language['speaking_level'];
						$consultant_language->reading_level = $language['reading_level'];
						$consultant_language->writing_level = $language['writing_level'];
						$consultant_language->understanding_level = $language['understanding_level'];
						$consultant_language->save();
					}
				}
			}

			$nationality_list = array();
			
			foreach ($nationalities as $nationality_id) {
				$nationality['country_id'] = $nationality_id;
				array_push($nationality_list, $nationality);
			}

			$user->consultantNationality()->createMany($nationality_list);

			$skill_list = array();
			
			foreach ($skills as $skill_id) {
				$skill['skill_id'] = $skill_id;
				array_push($skill_list, $skill);
			}

			$user->consultantSkill()->createMany($skill_list);

			$specialization_list = array();
			
			foreach ($specializations as $specialization_id) {
				$specialization['specialization_id'] = $specialization_id;
				array_push($specialization_list, $specialization);
			}

			$user->consultantSpecialization()->createMany($specialization_list);

			$worked_country_list = array();
			
			foreach ($worked_countries as $worked_country_id) {
				$worked_country['country_id'] = $worked_country_id;
				array_push($worked_country_list, $worked_country);
			}

			$user->consultantWorkedCountry()->createMany($worked_country_list);

			$consultant_agency_list = array();
			
			foreach ($consultant_agencies as $consultant_agency_id) {
				$consultant_agency['agency_id'] = $consultant_agency_id;
				array_push($consultant_agency_list, $consultant_agency);
			}

			$user->consultantAgencies()->createMany($consultant_agency_list);

			return Redirect::back()->with('message', 'Thank you for registering with our consultant database. A confirmation has been sent to the primary email address you provided. Please click on the link contained in this email in order to complete your registration.')
								   ->with('message_type', 'success');
		}else{

		 		$errors = $validation->errors()->toArray();
				return Redirect::back()
		 		->withInput(Input::all())
		 		->withErrors($validation)
		 		->with('message', 'Some required field(s) have not been filled. Please correct the error(s)!')
		 		->with('message_type', 'danger');
			
		 }
	}

	 /**
	 * Consultant download resume file 
	 * 
	 * @method GET 
	 * @return JSON =>PDF 
	 */

	public function downloadResume(){

		$user_id = Auth::user()->get()->id;
		$consultant = Consultant::find($user_id);

		$resume_file = $consultant->resume;

		$file_path = public_path('/upload/resume/' . $resume_file);

		if (File::isFile( $file_path ) ) {

			$extension = File::extension($file_path);

			$download_file_name = "Resume_" . $consultant->title . "_" . $consultant->surname . "." . $extension;
			
			return Response::download($file_path, $download_file_name);	
		}
		
		return "Invalid";
	}

	/**
	 * Review form 
	 * 
	 * @method GET 
	 * @return HTML -> Review form 
	 */
	public function getReview($token){

		$assignment = DB::table('consultant_assignments as ca')->select(array(
			'ca.id','ca.akdn_id','ca.consultant_id','ca.title_of_consultancy','ca.start_date','ca.end_date','ca.akdn_manager_name','ca.akdn_manager_email',
			'c.surname','c.title',
			'a.name as akdn_name'
		))->leftJoin('consultants as c','c.id','=','ca.consultant_id')
		  ->leftJoin('akdn as a','a.id','=','ca.akdn_id')
		  ->where('ca.token', $token)
		  ->first();

		if( $assignment ){

			return View::make('review', compact('assignment'));	
		}else{
			
			return View::make('reviewgiven');
		}
	}

	/**
	 * Consultant review validates, stores record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function postReview($token){

		$rules = array(
			'assignment_id' => 'required|integer',
			'ratings' => 'required|between:0,6',
			'future_repeat' => 'required',
		);

		$messages = array(
			'assignment_id.required' => 'Could not find assignment',
			'assignment_id.integer' => 'Something wrong',
			'ratings.required' => 'Please provide consultant ratings',
			'ratings.between' => 'Rating value must be between 1 and 5',
			'future_repeat.required' => 'Please provide your opinion for repeated work',
			'comments' => 'Please provide some comments',
		);

		$validator = Validator::make(Input::only('assignment_id','ratings','future_repeat','comments'),$rules, $messages);

		if( $validator->passes() ){

			$assignment_id = Input::get('assignment_id');

			$assignment = ConsultantAssignment::find($assignment_id);

			if( $assignment ){

				$assignment->fill(Input::only('ratings','future_repeat','comments'));
				$assignment->review_timestamp = date('Y-m-d H:i:s');
				$assignment->token = NULL;
				$assignment->save();

				return Redirect::route('consultant.review.complete')
				->with('message', 'Consultant review added successfully')
				->with('message_type', 'success');
			}
		}else{

			return Redirect::back()
				->withInput(Input::all())
				->withErrors($validator)
				->with('message', 'Please correct the following errors!')
				->with('message_type', 'danger');			
		}
	}

	 /**
	 * Consultant extend date form 
	 * 
	 * @method GET 
	 * @return HTML -> Consultant extend date form 
	 */
	public function extendDateGet($token){
		
		$assignment = DB::table('consultant_assignments as ca')->select(array(
			'ca.id','ca.akdn_id','ca.consultant_id','ca.title_of_consultancy','ca.start_date','ca.end_date','ca.akdn_manager_name','ca.akdn_manager_email',
			'c.surname','c.title',
			'a.name as akdn_name',
			'ca.title_of_consultancy',
			'ca.akdn_manager_name'
		))->leftJoin('consultants as c','c.id','=','ca.consultant_id')
		  ->leftJoin('akdn as a','a.id','=','ca.akdn_id')
		  ->where('ca.token', $token)
		  ->first();

		if( $assignment ){
			return View::make('extenddate', compact('assignment'));	
		}else{
			return View::make('reviewgiven');
		}
	}

	/**
	 * Consultant validates, stores record in database
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to with error messages 
	 * @return if successful -> redirect back with successful confirmation 
	 */
	public function extendDatePost(){

		//print_r(Input::all());exit;
		// dd(Input::get('before_date'));
		
		$rules = array(
			'assignment_id' => 'required|integer',
			'end_date' => 'date|date_format:d-m-Y|after:'.Input::get('before_date'),
		);
		
		$messages = array(
			'end_date.date'=> 'Please Enter Valid Date',
			'assignment_id.required' => 'Could not find assignment',
			'assignment_id.integer' => 'Something wrong',
		);

		$validator = Validator::make(Input::only('assignment_id','end_date'),$rules, $messages);

		if( $validator->passes() ){

			$assignment_id = Input::get('assignment_id');
			$assignment = ConsultantAssignment::find($assignment_id);

			if( $assignment ){
				if(Input::has('end_date')){	
					$date = date('Y-m-d',strtotime(Input::get('end_date')));
					$assignment->end_date = $date;
				}
				$assignment->review_timestamp = date('Y-m-d H:i:s');
				$assignment->token = NULL;
				$assignment->status = Input::get('status');
				$assignment->save();

				return Redirect::route('consultant.enddate.complete')
				->with('message', 'Consultant EndDate Extended successfully')
				->with('message_type', 'success');
			}
		}else{

			return Redirect::back()
				->withInput(Input::all())
				->withErrors($validator)
				->with('message', 'Please correct following errors!')
				->with('message_type', 'danger');			
		}
	}

	 /**
	 * Consultant extend form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn Create Form  
	 */
	public function extendComplete(){

		return View::make('enddateextend');
	}

	 /**
	 * Consultant Review complete form 
	 * 
	 * @method GET 
	 * @return HTML -> Akdn Create Form  
	 */
	public function reviewComplete(){

		return View::make("reviewadded");
	}

	/**
	 * Consultant terms form 
	 * 
	 * @method GET 
	 * @return HTML -> Consultant terms form  
	 */
	public function terms(){

		return View::make('terms');
	}
}