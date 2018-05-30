<?php

class LoginLog extends Eloquent
{
    protected $table = 'login_logs';

    protected $primaryKey = 'id';
	
	protected $fillable = [];
	
	public $timestamps = false;

	public static function findOrCreate($id)
	{
	    $obj = static::find($id);
	    return $obj ?: new static;
	}

}
