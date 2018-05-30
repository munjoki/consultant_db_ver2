<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles creating access list groups
*/
class AclGroup extends Eloquent
{
    protected $table = 'acl_groups';

    protected $primaryKey = 'id';
	
	protected $fillable = ['name', 'description'];
	
	public $timestamps = false;
	
	public function users()
	{
	    return $this->belongsToMany('Admin', 'acl_user_groups');
	}
	
	public function permissions()
	{
	    return $this->belongsToMany('AclPermission', 'acl_group_permissions', 'group_id', 'permission_id');
	}
}
