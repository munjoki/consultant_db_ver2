<?php
/*
AKDN Consultant Database Version 1.0
this model file handles sending the consultant details to the users table
*/
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	protected $fillable = array('id','email','password','level');
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public static $login_rules= array(

   		'email'                 => 'required|email|unique:users,email',
   		'password'              => 'required',

   	);

   	public static $messages = array(
		'email.required'     => 'Please enter email address',
		'email.email'        => 'Please provide a valid email address',
		'email.unique'       => 'This email address already exists',
		'password'           => 'Please enter password',
	);

   public static $update_rules = array(
		'email'                 => 'required|email|unique:users,email',
		'name'                  => 'required|between:4,16',
		
	);

   	public function consultantNationality(){
		return $this->hasMany('ConsultantNationality', 'consultant_id', 'id');
	}

	public function nationalities(){
		$nationalities = array();
		if($this->consultantNationality){
			$nationalities = $this->consultantNationality->toArray();
			$nationalities = array_fetch($nationalities, 'country_id');

			if(count($nationalities) > 0){
				$nationalities = Country::select('country_des')->whereIn('id', $nationalities)->lists('country_des');
			}
		}

		return $nationalities;
	}

	public function consultantSpecialization(){
		return $this->hasMany('ConsultantSpecialization', 'consultant_id', 'id');
	}

	public function specialization(){
		$specialization = array();
		if($this->consultantSpecialization){
			$specialization = $this->consultantSpecialization->toArray();
			$specialization = array_fetch($specialization, 'specialization_id');

			if(count($specialization) > 0){
				$specialization = Specialization::select('spec_des')->whereIn('id', $specialization)->lists('spec_des');
			}
		}

		return $specialization;
	}

	public function consultantWorkedCountry(){
		return $this->hasMany('ConsultantWorkedCountry', 'consultant_id', 'id');
	}

	public function workedcountries(){
		$workedcountries = array();
		if($this->consultantWorkedCountry){
			$workedcountries = $this->consultantWorkedCountry->toArray();
			$workedcountries = array_fetch($workedcountries, 'country_id');

			if(count($workedcountries) > 0){
				$workedcountries = Country::select('country_des')->whereIn('id', $workedcountries)->lists('country_des');
			}
		}

		return $workedcountries;
	}

	public function consultantSkill(){
		return $this->hasMany('ConsultantSkill', 'consultant_id', 'id');
	}

	public function skills(){
		$skills = array();
		if($this->consultantSkill){
			$skills = $this->consultantSkill->toArray();
			$skills = array_fetch($skills, 'skill_id');

			if(count($skills) > 0){
				$skills = Skill::select('skills_des')->whereIn('id', $skills)->lists('skills_des');
			}
		}

		return $skills;
	}

	public function consultantLanguage(){
		return $this->hasMany('ConsultantLanguage', 'consultant_id', 'id');
	}
	
	public function languages(){
		$languages = array();

		if($this->consultantLanguage){
			$languages = $this->consultantLanguage->toArray();
			$languages = array_fetch($languages, 'language_id');

			$languages = ConsultantLanguage::select('consultant_languages.speaking_level','consultant_languages.reading_level','consultant_languages.writing_level','consultant_languages.understanding_level','languages.lang_acr','languages.lang_des')
				->leftJoin('languages','languages.id','=','consultant_languages.language_id')
				->whereIn('languages.id', $languages)
				->where('consultant_languages.consultant_id', $this->id)
				->get()->toArray();

		}
		return $languages;
	}

	public function consultant(){
		return $this->hasOne('Consultant','id','id');
	}

	public function consultantAgencies(){
		return $this->hasMany('ConsultantAgency', 'consultant_id', 'id');
	}

	public function agencies(){
		$agencies = array();
		if($this->consultantAgencies){
			$agencies = $this->consultantAgencies->toArray();
			$agencies = array_fetch($agencies, 'agency_id');

			if(count($agencies) > 0){
				$agencies = Agency::select('fullname')->whereIn('id', $agencies)->lists('fullname');
			}
		}

		return $agencies;
	}


	public function getWelcomeName(){
		return ucwords($this->consultant->title . " " . $this->consultant->surname);
	}
}
