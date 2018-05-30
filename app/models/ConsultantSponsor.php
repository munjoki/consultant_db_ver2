<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles sponsoring of a consultant to register with the database
*/
class ConsultantSponsor extends Eloquent{

	protected $fillable = array('name','email','message_by','invited_on_behalf');	

	public $table = 'consultant_sponsors';

	public static $rules = array(
		'name' => 'required',
		'email' => 'required|email|unique:users,email|unique:consultant_sponsors,email'
	);

	public static $messages = array(
		'name.required' => 'Please enter consultant name',
		'email.required' => 'Please enter consultant email address',
		'email.email' => 'Please enter a valid consultant email',
		'email.unique' => 'A consultant with this email address already exists in the database or has already been invited'
	);

	public function consultantSpecialization(){

		return $this->hasMany('ConsultantSponsoresSpecialization', 'consultant_sponsor_id', 'id');
	}

	public function getNameAttribute($value)
    {
        //$this->attributes['name'] = ucwords(strtolower($value));
        return ucwords(strtolower($value));
    }
}