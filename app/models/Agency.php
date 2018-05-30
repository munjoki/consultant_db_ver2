<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles the AKDN agencies
*/
class Agency extends Eloquent{

	
	protected $table = 'agencies';

	protected $fillable = array('acronym','fullname','order_id');

	public $timestamps = false;
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */

	public static $rules= array(

   		'acronym'        => 'required',
   		'fullname'       => 'required'

   	);

   	public static $messages = array(
		'acronym.required'     => 'Please enter acronym',
		'fullname.required'    => 'Please enter fullname',
	);

   public static $update_rules = array(
		'acronym'        => 'required',
   		'fullname'       => 'required'
		
	);
	
}