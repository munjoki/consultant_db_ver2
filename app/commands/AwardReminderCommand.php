<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AwardReminderCommand extends Command {

	protected $name = 'consultant:award-reminder';

	
	protected $description = 'This is commands will fire mail to the akdn manager ';

	public function fire()
	{

		$today_date = date('Y-m-d');

		$akdn_reminder = Akdn::select(DB::raw('datediff(last_activity,last_award_activity)as diff '),
									'email','name','id')->get();

		// dd($akdn_reminder->diff);  
		foreach ($akdn_reminder as $key => $reminder) {
			
			if($reminder->diff >= 7){

				$email = $reminder->email;
				$name  = $reminder->name;

				$mail_data = array(  
		        	'email' => $email,
		        	'name' => $name
				);

				Mail::send('emails.user.akdnreminder', $mail_data, function($message) use($email, $mail_data) {
			            
						$message->to($email)
			            		->subject("AKDN's Award Reminder");
			    });

				$data = array(
					'last_award_activity' => null,
				);

			    Akdn::where('id', $reminder->id)->update($data);

			}
		}
		
	}	
}
