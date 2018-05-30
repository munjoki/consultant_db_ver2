<?php 
/*
AKDN MER Consultant Database Version 1.0
this model file handles the areas of specialization as specified while sponsoring a consultant
*/
class ConsultantSponsoresSpecialization extends Eloquent{
	
	protected $table = 'consultant_sponsors_specializations';

	public $timestamps = false;

	protected $fillable = ['specialization_id'];
}