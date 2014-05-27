<?php

return array(
	// Directories
	'MicroDir' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'micro',
	'AppDir'   => __DIR__ ,

	// Sitename
	'company' => 'Micro',
	'slogan'  => 'simply hmvc php framework',

	// Default import dir
	'import' => array(
		'extensions',
		'components',
		'widgets',
		'models',
	),

	// Print run time
	'timer' => true,
	// Language
	'lang' => 'en',

	// Setup components
	'components' => array(
		// Request manager
		'request' => array(
			'class' => 'MRequest',
			'routes' => array(
				'/login'=>'/default/login',
				'/logout'=>'/default/logout',
				'/login/<num:\d+>/<type:\w+>/<arr:\d{3}>' => '/default/login',

				'/blog/post/index/<page:\d+>' => '/blog/post',
				'/blog/post/<id:\d+>' => '/blog/post/view',
			),
		),
		// Default session
		'session' => array(
			'class' => 'MSession',
			'autoStart' => true,
		),
		// Flash messages
		'flash' => array(
			'class' => 'MFlashMessage',
		),
		// DataBase
		'db' => array(
			'class'=> 'MDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=micro',
			'username'=>'micro',
			'password'=>'micro',
			'charset'=>'utf8'
		)
	)
);