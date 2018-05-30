<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles registration of AKDN staff
*/
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Akdn extends Eloquent implements UserInterface, RemindableInterface
 {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'akdn';

	protected $fillable = ['other_name','surname','email','agency'];

	protected $hidden = array('password', 'remember_token');

	public function getWelcomeName(){
		return ucwords( $this->other_name ).' '.ucwords( $this->surname );
	}

	public function updateLastActivity($id){

		$akdn = new Akdn();
		$now = new DateTime();
		$array['last_activity'] = $now;
		$akdn->where('id',$id)->update($array);
		return true;
	}
	public static $rules= array(

		'surname'     			=> 'required',
		'email'       			=> 'required|email|unique:akdn,email',
		'other_name' 			=> 'required',	
		'nationality' 			=> 'required',
		'terms_conditions' 		=> 'required',
		'consultant_agencies'	=> 'agency_none',
 		
   	);

   	public static $messages = array(

		'email.required'       => 'Please enter your email address',
		'email.email'          => 'Please enter a valid email',
		'email.unique'         => 'This email address is already registered',
		'other_name.required' => 'Please enter your given name(s)',
		'nationality.numeric' => 'Please select the country where you are based',
		'surname.required'     => 'Please enter your surname',
		'terms_conditions.required' => 'Please agree to the terms and conditions',
		'consultant_agencies.agency_none'	=> 'Can\'t choose other agencies with NONE'
	);

   	// public static function editRules(){

   	// 	$rules = self::$rules;
   	// 	unset($rules['email']);
   	// 	// $rules['email'] = 'required|email|unique:akdn,email,' . $this->id . ',id';
   	// 	unset($rules['terms_conditions']);
   		
   	// 	return $rules;
   	// }
   	public static $editRules = array(

		'surname'     			=> 'required',
		'email'       			=> 'required|email',
		'other_name' 			=> 'required',	
		'nationality' 			=> 'required'
		
   	);

   	public function Akdnnationality(){
		return $this->belongsToMany('Country', 'akdn_nationalities');
	}

	public function Agency(){
		return $this->belongsToMany('Agency', 'akdn_agencies');
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

	public function setFirstNameAttribute($value)
    {
        $this->attributes['other_name'] = ucwords(strtolower($value));
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['surname'] = ucwords(strtolower($value));
    }
}
