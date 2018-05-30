<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles adding new languages to the languages table
*/
class Language extends Eloquent {

	protected $table = 'languages';	

	protected $fillable = ['lang_acr','lang_des'];

	public static $rules = [

		'lang_acr' => 'required|unique:languages,lang_acr',
		'lang_des' => 'required|unique:languages,lang_des',

	];


	public static $messages = [

		'lang_acr.required' => 'Please provide the language',
		'lang_des.required' => 'Please provide the language description',
		'lang_acr.unique'   => 'The language provided already exists',
		'lang_des.unique'	=> 'The language provided already exists',


	];

}
