<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles access list permissions
*/
class AclPermission extends Eloquent
{
    protected $table = 'acl_permissions';

    protected $primaryKey = 'id';
	
	protected $fillable = ['ident', 'description'];
	
	public $timestamps = false;
	
	public function groups()
	{
	    return $this->belongsToMany('AclGroup', 'acl_group_permissions');
	}
	
	public function getKey()
	{
	    return $this->attributes['ident'];
	}
}
