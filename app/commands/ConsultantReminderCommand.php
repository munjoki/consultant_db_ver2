<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ConsultantReminderCommand extends Command {

	protected $name = 'consultant:registration-reminder';

	
	protected $description = 'This commands will fire mail to the pending consultant';

	public function fire()
	{
		$columns = array('consultant_sponsors.id',DB::raw('CONCAT(akdn.other_name, " ", akdn.surname) AS full_name'),'name','consultant_sponsors.email','consultant_sponsors.created_at', 'users.id as user_id','message_by');
		$consultants = ConsultantSponsor::select($columns)
									->leftjoin('akdn','consultant_sponsors.akdn_id','=','akdn.id')
									->leftjoin('users','users.email','=','consultant_sponsors.email')
									->whereRaw('DATEDIFF(NOW(),consultant_sponsors.created_at) = 14')
									->get()->toArray();
		if($consultants)
		{
			foreach ($consultants as $consultant) {

				$sponsored_consultant_email = $consultant['email'];
				$sponsored_consultant_name = $consultant['name'];

				$mail_data = [
					'message_by' => $consultant['message_by'],
					'akdn_name' => $consultant['full_name'],
					'name'		=> $consultant['name']
				];
				
				Mail::send('emails.user.invitation',
				 $mail_data, function($message) use ($sponsored_consultant_email,$sponsored_consultant_name){
					$message->to( $sponsored_consultant_email, $sponsored_consultant_name )->cc('consultants@akdn.org')
					->subject("Invitation to join AKDN's Consultant Database");
				});
			}
		}
	}	
}
