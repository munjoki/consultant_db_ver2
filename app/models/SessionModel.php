<?php 

class SessionModel extends Eloquent {
	
	protected $table = "sessions";

	protected $primaryKey = 'id';
	
	public $timestamps = false;
}