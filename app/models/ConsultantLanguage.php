<?php
/*
AKDN MER Consultant Database Version 1.0
this model file handles a consultant languages and various language levels
*/
class ConsultantLanguage extends Eloquent {

	protected $table = 'consultant_languages';

	protected $fillable = ['language_id', 'speaking_level','reading_level','writing_level','understanding_level'];

	public function language(){
		return $this->belongsTo('Language', 'language_id', 'id', array('id','lang_des'));
	}
}
