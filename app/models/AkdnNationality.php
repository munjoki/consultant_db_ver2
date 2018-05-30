<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles the nationalities of AKDN staff
*/
class AkdnNationality extends Eloquent {

	protected $table = 'akdn_nationalities';

	protected $fillable = ['country_id','akdn_id'];
}
