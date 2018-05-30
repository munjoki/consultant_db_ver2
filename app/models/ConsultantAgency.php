<?php 
/*
AKDN MER Consultant Database Version 1.0
this model file handles the AKDN agencies for which the consultant has worked with
*/
class ConsultantAgency extends Eloquent{
	
	protected $table = 'consultant_agencies';

	protected $fillable = ['agency_id'];
}