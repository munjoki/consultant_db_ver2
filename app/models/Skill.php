<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles adding new skills to the skills table
*/
class Skill extends Eloquent{

	
	protected $table = 'skills';

	protected $fillable = ['skills_des'];

	public static $rules = [

		'skills_des' => 'required|unique:skills,skills_des',
	];


	public static $messages = [

		'skills_des.required' => 'Please provide a skill',
		'skills_des.unique' => 'The skill provided already exists'

	];

	public $timestamps = false;

	
}
