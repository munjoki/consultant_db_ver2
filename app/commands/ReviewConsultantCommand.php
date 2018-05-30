<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReviewConsultantCommand extends Command {

	// /usr/local/bin/php -q /home/tkaranja/public_html/consultant/artisan consultant:review
	
	protected $name = 'consultant:review';

	
	protected $description = 'This is commands will fire mail to the akdn manager to review the consultant assigned';

	public function fire()
	{

		$today_date = date('Y-m-d');

		$consultant_assignments = DB::table('consultant_assignments as ca')->select(array(
			'ca.id','ca.akdn_id','ca.consultant_id','ca.title_of_consultancy','ca.start_date','ca.end_date','ca.akdn_manager_name','ca.akdn_manager_email',
			'c.surname','c.title',
			'a.name as akdn_name'
		))->leftJoin('consultants as c','c.id','=','ca.consultant_id')
		  ->leftJoin('akdn as a','a.id','=','ca.akdn_id')
		  ->where('ca.end_date', $today_date)
		  ->whereRaw(DB::raw('ca.review_timestamp is NULL'))
		  ->get();

		if( $consultant_assignments ){

			foreach ($consultant_assignments as $ca) {
				
				$manager_email = $ca->akdn_manager_email;
				$manager_name = $ca->akdn_manager_name;
				$consultant_name = ucwords(  $ca->title . ". " . $ca->surname );
				$start_date = date('d-m-Y', strtotime($ca->start_date));
				$end_date = date('d-m-Y', strtotime($ca->end_date));
				$token = ConsultantAssignment::generateReviewToken();
				$title_of_consultancy = $ca->title_of_consultancy;

				$mail_data = array(  
		        	'manager_name' => $manager_name,
		        	'consultant_name' => $consultant_name,
		        	'start_date' => $start_date,
		        	'end_date' => $end_date,
		        	'token' => $token,
		        	'title_of_consultancy' => $title_of_consultancy
				);

				Mail::send('emails.user.consultantreview', $mail_data, function($message) use($manager_email, $mail_data) {
			            
						$message->to($manager_email)
			            		->subject("AKDN's Consultant Review");
			    });

				$data = array(
					'token' => $token,
				);

			    ConsultantAssignment::where('id', $ca->id)->update($data);
			}
		}	
	}
}