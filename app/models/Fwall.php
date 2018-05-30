<?php

class Fwall extends Eloquent
{
    protected $table = 'firewall';

    protected $primaryKey = 'id';
	
	protected $fillable = ['ip_address', 'whitelisted'];
}
