<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles the countries where the consultant has worked through the consultant_worked_countries table
*/
class ConsultantWorkedCountry extends Eloquent {

	protected $table = 'consultant_worked_countries';

	protected $fillable = ['country_id'];
}
