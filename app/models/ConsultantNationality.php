<?php
/*
AKDN MER Consultant Database Version 1.0
This model file handles a consultant countries of nationality
*/
class ConsultantNationality extends Eloquent {

	protected $table = 'consultant_nationalities';

	protected $fillable = ['country_id'];
}
