<!------------------------------------------------------------------------------------------------------------------------------------------
	AKDN MER Consultant Database Version 1.0
	this file handles the password reset email sent to the user
-------------------------------------------------------------------------------------------------------------------------------------------->
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">
	</head>
	<body>
		<h2>Reset Password</h2>
		
		<div>
			Please click on the following link to reset your password: {{ URL::to('password/reset', array($token)) }}.<br/>
			This link will expire in {{ Config::get('auth.reminder.expire', 60) }} minutes.
			<br/><br/>
			Sincerely,<br/>
			The AKDN MER Consultant Database Team
		</div>
	</body>
</html>
