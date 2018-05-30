<?php
/*----------------------------------------------------------------------------------------------------------------------------------------
	AKDN Consultant Database Version 1.0
	this controller file handles the admin login and session
------------------------------------------------------------------------------------------------------------------------------------------*/
/*----------------------------------------------------------------------------------------------------------------------------------------
	Admin Module: 
        This controller file handles the admin login and session

    * @version    AKDN Consultant Database Version 1.0
------------------------------------------------------------------------------------------------------------------------------------------*/ 

class AdminController extends BaseController {

	/**
	 * Admin Dashboard 
	 * 
	 * @method GET 
	 * @return HTML -> dashboard 
	 */
	public function dashboard(){

		$awardeds = ConsultantAssignment::select('title_of_consultancy','consultants.other_names')
										->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
										->orderBy('consultant_assignments.id','desc')->take(5)->get();

		$male_consultants = Count(Consultant::select('gender')->where('gender',1)->get());
		$female_consultants = Count(Consultant::select('gender')->where('gender',2)->get());
		
		$total = $male_consultants + $female_consultants;

		$male_consultants_percentage = ceil(($male_consultants * 100) / $total);
		$female_consultants_percentage = ceil(($female_consultants * 100) / $total);

		$consultant = Consultant::select('id')->count();
		$language = Language::select('id')->count();
		$akdn = Akdn::select('id')->count();
		$agencies = Agency::select('id')->count();
		$skills = Skill::select('id')->count();
		$specialization = Specialization::select('id')->count();

		$registered_consultancies = ConsultantAssignment::select('title_of_consultancy','consultants.other_names')
										->leftJoin('consultants','consultants.id','=','consultant_assignments.consultant_id')
										->orderBy('consultant_assignments.id','desc')->count();								

		return View::make('admin.dashboard',compact('consultant','language','agencies','skills','akdn','registered_consultancies','specialization','awardeds','male_consultants_percentage','female_consultants_percentage'));
	}

	/**
	 * Admin Login form 
	 * 
	 * @method GET 
	 * @return HTML -> Login 
	 */
	public function login(){

		return View::make('admin.login');
	}

	/**
	 * Admin user validates and login
	 * 
	 * @method POST 
	 * @return if failed -> redirect back to admin login form with error messages 
	 * @return if successful -> redirect dasboard with successful confirmation 
	 */
	public function doLogin()
	{
		$rules = array(
				'email'    => 'required|email', // make sure the email is an actual email
				'password' => 'required' // password can only be alphanumeric and has to be greater than 3 characters
		);

		$messages = [];

		if(Input::exists('captcha'))
		{
			$rules['captcha'] = 'required|captcha';
			$messages['captcha.captcha'] = 'captcha not match try again';
		}

		$validator = Validator::make(Input::all(), $rules, $messages);

		$login_failed_count = (Session::has('login_failed_count')) ? Session::get('login_failed_count') : 0;
		$ip = Request::getClientIp(true);

		if ($validator->fails()) {

			return Redirect::back()->withErrors($validator)
								   ->withInput(Input::only('email')) 
								   ->with('message', trans('Please enter valid details'))
								   ->with('message_type', 'danger');

		} else {
			// create our user data for the authentication

			$userdata = array(
				'email' => Input::get('email'),
				'password' =>Input::get('password')
			);

			$remember = Input::has('remember') ? true : false;

			if ( Auth::admin()->validate($userdata, $remember) ) {

				$user = Admin::select('id')->where('email', $userdata['email'])->first();
				$user_id = $user->id;

				$session = SessionModel::where('user_type', 'admin')
									   ->where('user_id', $user_id)
									   ->where('last_activity', '>', time() - ( Config::get('session.lifetime') * 60 ) )
									   ->where('id', '<>', Session::getId())
									   ->orderBy('last_activity', 'desc')
									   ->first();

				if ($session) {
					return Redirect::route('remotelogin.get');
				}

				Auth::admin()->loginUsingId($user_id, $remember);

				Session::forget('login_failed_count');
				Firewall::whitelist($ip);

				$location = Location::get($ip);

				if($location) {
					$loginLog = new LoginLog();
					$loginLog->user_id = $user_id;
					$loginLog->ip_address = $location->ip;
					$loginLog->country = $location->countryName;
					$loginLog->type = 'admin';
					$loginLog->login_time = Carbon\Carbon::now();
					$loginLog->save();
				}

				return Redirect::route('admin.session');
		
			} else {

				Session::put('login_failed_count', ++$login_failed_count);

				if($login_failed_count >= 6)
				{
					Firewall::blacklist($ip, true);
				}

				return Redirect::back()->with('message', trans('Invalid user login'))
									   ->with('message_type','danger');
			}
		}
	}

	public function show(){
		echo "I am getting called";
	}

	/**
	 * Admin logout 
	 * 
	 * @method GET 
	 * @return HTML -> redirect to login page with successful confirmation  
	 */
	public function getLogout()
	{
		$id = Auth::admin()->id();
		$sessionId = Session::getId();

		$getUserLoginLog = LoginLog::selectRaw('MAX(id) as id')->where(['user_id' => $id,'type' => 'admin'])->first();

		if ($getUserLoginLog) {
			$loginLog = LoginLog::find($getUserLoginLog['id']);
			$loginLog->logout_time = Carbon\Carbon::now();
			$loginLog->save();
		}

		Auth::admin()->logout();

		$session = SessionModel::where('user_type', 'admin')->where('id', $sessionId)->delete();

		return Redirect::route('admin.login')
					   ->with('msg', 'You have successfully logged out')
					   ->with('msg_type','success')
					   ->with('msg_title','Successful!');
	}


	 /**
	 * Admin Dowload resume
	 * 
	 * @method GET 
	 * @param $filename => resume file name
	 * @return JSON -> File
	 */
	public function Download($filename)
	{    
        $blog_path = public_path() . '/upload/resume/';
        $file = $blog_path. $filename;
        //$extension = $file->getClientOriginalExtension();
        
        if(file_exists($file)){
           return Response::download($file); 
        }
        else{
            return "No files to download";
        }
	}
	
	/**
	 * Admin user  single / multiple user delete
	 * 
	 * @method POST 
	 * @return JSON
	 */
	public function destroy()
	{
		/* Delete Single Record */
		if(Input::get('name') == 'destroy')
		{
			$user = User::where('id','=',Input::get('id'))->first();
			$email = $user->email;
			$user->delete();

			Consultant::where('id','=',Input::get('id'))->delete();
			ConsultantSponsor::where('email', $email)->delete();
		}
		/* Delete Multiple Records */
		else
		{
			$ids = Input::get('id');
			$users = User::whereIn('id',$ids)->get();

			$emails = array();
			foreach ($users as $user) {
				$emails[] = $user->email;
			}
			
			User::whereIn('id',$ids)->forceDelete();

			Consultant::whereIn('id',$ids)->forceDelete();
			ConsultantSponsor::whereIn('email',$emails)->forceDelete();
		}

		return Response::json(array('msg' => 'User deleted permanently','success'=>true), 200);
	}

	 /**
	 * Admin Denied form 
	 * 
	 * @method GET 
	 * @return HTML -> Admin Denied Form  
	 */
	public function getDenied()
	{
		return View::make('admin.denied');
	}

	 /**
	 * Admin locked form 
	 * 
	 * @method GET 
	 * @return HTML -> Admin Locked Form  
	 */
	public function getlocked(){

        if (Auth::admin()->check()) {

        	$id = Auth::admin()->id();

			$getUserLoginLog = LoginLog::selectRaw('MAX(id) as id')->where(['user_id' => $id,'type' => 'admin'])->first();

			if ($getUserLoginLog) {
				$loginLog = LoginLog::find($getUserLoginLog['id']);
				$loginLog->logout_time = Carbon\Carbon::now();
				$loginLog->save();
			}

            $user = Auth::admin()->get();

            Session::put('admin',['username' => $user['name'], 'email' => $user['email']]);
            Auth::admin()->logout();

			return View::make('admin.locked');

        } elseif(Session::has('admin')) {

			return View::make('admin.locked');
        } else {

	    	return Redirect::route('admin.login');
	    }
    }
    
	public function loginSession()
	{
		$user_id = Auth::admin()->id();
		$session_id = Auth::admin()->getSession()->getId();

		$session = SessionModel::find($session_id);

		$session->user_type = 'admin';
		$session->user_id = $user_id;
		$session->save();

		return Redirect::route('admin.dashboard');
	}

}