<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles registration of a consultant
*/
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

Validator::extend('extension', function($attribte, $value, $paramter){

	$extension = $value->getClientOriginalExtension();
	return in_array($extension, $paramter);
});

// Validator::extend('at_least_one', function($attribte, $value, $paramter){

// 	$validation = true;

// 	$linkedin_url=Input::get('linkedin_url');
// 	$website_url=Input::get('website_url');
// 	$resume=Input::get('resume');	
	

// 		if(($linkedin_url=="") && ($website_url=="")){
// 			$validation = false;
// 			break;
// 		}elseif (($linkedin_url=="") && ($resume=="")) {
// 			$validation = false;
// 			break;
// 		}elseif (($website_url=="") && ($resume=="")) {
// 			$validation = false;
// 			break;
// 		}
// 		else{
// 			return $validation;
// 		}		
// });


Validator::extend('unique_languages', function($attribte, $value, $paramter){

	$validation = true;

	$languages = Input::get('languages.languages');
	$selected_langs = array_fetch($languages, 'language');
	$selected_langs_count = array_count_values($selected_langs);
		
	foreach ($selected_langs_count as $key => $value) {
		if($value > 1){
			$validation = false;
			break;
		}
	}

	return $validation;
});

class Consultant extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $fillable = array('surname','other_names','address','city','gender','consultant_type','company_name','telno','alternate_telno','email','alternate_email','pub_profile','resume', 'linkedin_url','website_url');

	protected $hidden = array('password', 'remember_token');

	public $table = 'consultants';
	
	public static $rules = array(

		'surname'     			=> 'required',
		'email'       			=> 'required|email|unique:users,email',
		'alternate_email'       => 'email',
		'other_names' 			=> 'required',	
		// 'title'       			=> 'required',
		'telno'       			=> 'numeric|regex:/[0-9]{8,15}/',
		'alternate_telno'		=> 'numeric|regex:/[0-9]{8,15}/',
		'nationality' 			=> 'required',
		'gender'      			=> 'required',
		'resume'	  			=> 'mimes:doc,docx,txt,pdf|max:1024|extension:txt,doc,docx,pdf|required_without_all:linkedin_url,website_url',
		'linkedin_url'			=> 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|required_without_all:resume,website_url',
		'website_url'			=> 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/|required_without_all:resume,linkedin_url',
		'terms_conditions' 		=> 'required',
		'languages'				=> 'unique_languages',
		'consultant_type'		=> 'required',
		'company_name'			=> 'required_if:consultant_type,company,Both',
		'consultant_agencies' => 'agency_none'
   	);

   	public static $messages = array(
		'email.required' => 'Please enter your email address',
		'email.email' => 'Please enter a valid email',
		'email.unique' => 'This email address is already registered',
		'alternate_email.email'=> 'Please enter a valid alternate email address',
		'other_names.required' => 'Please enter your given name(s)',
		'title.required' => 'Please enter your title',
		'gender.required' => 'Please select your gender',
		'consultant_type.required' => 'Please select consultant type',
		'company_name.required_if' => 'Please provide company name',
		'telno.numeric' => 'Please enter a valid telephone number',
		'telno.regex' => 'Please enter a valid telephone number',
		'alternate_telno.numeric' => 'Please enter a valid telephone number',
		'alternate_telno.regex' => 'Please enter a valid telephone number',
		'nationality.required' => 'Please select your nationality',
		'surname.required' => 'Please enter your surname',
		'resume.extension' => 'Only doc,docx and pdf files are allowed',
		'resume.required_without_all' => 'You must provide either a LinkedIn URL, website/blog URL or resume',
		'linkedin_url.regex' => 'Enter valid url',
		'website_url.regex' => 'Enter valid url',
		'linkedin_url.required_without_all' => 'You must provide either a LinkedIn URL, website/blog URL or resume',
		'website_url.required_without_all' => 'You must provide either a LinkedIn URL, website/blog URL or resume',
		'terms_conditions.required' => 'Tick the check box to agree',
		'languages.unique_languages' => 'You have selected a similar language multiple times. Please delete or make correction',
		'consultant_agencies.agency_none' => 'Can\'t choose other agencies with NONE'
	);

   	public static function editRules(){

   		$rules = self::$rules;
   		unset($rules['email']);
   		unset($rules['terms_conditions']);
   		unset($rules['linkedin_url']);
   		unset($rules['resume']);
   		unset($rules['website_url']);
   		
   		return $rules;
   	}

   	public static $ratings_scale = array(
		'1' => 'Very poor', 
		'2' => 'Poor', 
		'3' => 'Average', 
		'4' => 'Good', 
		'5' => 'Excellent'
	);

	public function getRatingLabel(){

		$ratings = $this->ratings;
		
		return array_key_exists($ratings, self::$ratings_scale) ? self::$ratings_scale[$ratings] : '#NA';
	}
    
}
