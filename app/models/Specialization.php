<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles adding new areas of specialization to the specialization table
*/
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Specialization extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'specialization';

	protected $fillable = ['spec_des'];

	public $timestamps = false;

	public static $rules = [
		'spec_des' => 'required|unique:specialization,spec_des',
	];

	public static $message = [
		'spec_des.required' => 'Thematic name is required',
		'spec_des.unique'   => 'The thematic area provided already exists'
	];
	
}
