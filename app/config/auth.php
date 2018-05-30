<?php return array(

    'multi' => array(
        'admin' => array(
            'driver' => 'eloquent',
            'model' => 'Admin'
        ),
        'user' => array(
            'driver' => 'eloquent',
            'model' => 'User'
        ),
        'akdn' => array(
            'driver' => 'eloquent',
            'model' => 'Akdn'
        )
    ),

    'reminder' => array(

        'email' => 'emails.auth.reminder',

        'table' => 'password_reminders',

        'expire' => 60,

    ),

);