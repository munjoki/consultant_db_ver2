<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles admin registration
*/
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

Validator::extend('strong_password', function($attribute, $value, $parameters) {
	$r1 = '/[A-Z]/';
	$r2 = '/[a-z]/';
	$r3 = '/[!@#$%^&*()\-_=+{};:,<.>]/';
	$r4 = '/[0-9]/';

	if(preg_match_all($r1,$value, $o)<1) return false;

	if(preg_match_all($r2,$value, $o)<1) return false;

	if(preg_match_all($r3,$value, $o)<1) return false;

	if(preg_match_all($r4,$value, $o)<1) return false;

	if(strlen($value)<8) return false;

	return true;
});

class Admin extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin';

	protected $fillable = array('name','email');

	public static $rules = [
		'name' => 'required',
		'email' => 'required|email',
		'password' => 'required|strong_password',
		'group_id' => 'required'
	];
	public static $message = [
		'name.required' => 'User Name is Required',
		'email.required' => 'User Email is Required',
		'password.required' => 'User Password is Required',
		'password.strong_password' => 'Passwords must be at least 8 characters long and contain at least one uppercase letter, one lowercase letter, one digit and one special character.',
		'group_id.required' => 'Please Select Role',
	];

	public function groups()
    {
        return $this->belongsToMany('AclGroup', 'acl_user_groups', 'user_id', 'group_id');
    }
}
