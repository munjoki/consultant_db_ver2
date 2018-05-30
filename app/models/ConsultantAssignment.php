<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles registering of consultancies to a consultant
*/
class ConsultantAssignment extends Eloquent{
		
	protected $fillable = array(
		'consultant_id','title_of_consultancy',
		'start_date','end_date',
		'akdn_manager_name','akdn_manager_email',
		'assignment_id','ratings','future_repeat','comments'
	);	

	public function setAkdnManagerNameAttribute($value){
		
		$this->attributes['akdn_manager_name'] = ucwords($value);
	}

	public function setAkdnManagerEmailAttribute($value){
		
		$this->attributes['akdn_manager_email'] = strtolower($value);
	}

	public function setStartDateAttribute($value){
		
		$this->attributes['start_date'] = date('Y-m-d', strtotime($value));
	}

	public function setEndDateAttribute($value){
		
		$this->attributes['end_date'] = date('Y-m-d', strtotime($value));
	}

	public static $rules = array(
		'consultant_id' => 'required',
		'title_of_consultancy' => 'required',
		'start_date' => 'required|date|date_format:d-m-Y',
		'end_date' => 'required|date|date_format:d-m-Y',
		'akdn_manager_name' => 'required',
		'akdn_manager_email' => 'required|email'
	);

	public static function getAddRules(){

		$rules = self::$rules;

		if( Input::has('start_date') ){

			$start_date = strtotime(Input::get('start_date'));
			$start_date -= ( 3600 * 24 );
			$start_date = date('d-m-Y', $start_date);
			//echo $start_date;exit;
			$rules['end_date'] .= "|after:" . $start_date;
		}

		return $rules;
	}

	public static $messages = array(
		'consultant_id.required' => 'Invalid consultant',
		'title_of_consultancy.required' => 'Please enter project title',
		'start_date.required' => 'Please enter start date',
		'start_date.date' => 'Please enter a valid start date',
		'start_date.date_format' => 'Enter start date in dd-mm-yyyy format',
		'end_date.required' => 'Please enter end date',
		'end_date.date' => 'Please enter a valid end date',
		'end_date.date_format' => 'Enter end date in dd-mn-yyyy format',
		'end_date.after' => 'End date must be equal or greater than start date',
		'akdn_manager_name.required' => 'Please enter AKDN contact name',
		'akdn_manager_email.required' => 'Please enter AKDN contact email address',
		'akdn_manager_email.email' => 'Please enter a valid AKDN contact email address',
	);

	public static function generateReviewToken(){
		
		$token = '';
		do {

			$token = sha1(uniqid());

		} while(ConsultantAssignment::where('token', $token)->count());

		return $token;
	}
}