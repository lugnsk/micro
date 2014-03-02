<?php

return array(
	// Directories
	'MicroDir' => __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'micro',
	'AppDir'   => __DIR__ ,

	// Sitename
	'company' => 'Micro',
	'slogan'  => 'php hmvc framework',

	// Default import dir
	'import' => array(
		'components',
		'widgets',
		'models',
	),

	// Print runtime
	'timer' => true,

	// Setup components
	'components' => array(
		// Request manager
		'request' => array(
			'class' => 'MRequest',
			'routes' => array(
				'/login'=>'/default/login',
				'/logout'=>'/default/logout',
				'/login/<num:\d+>/<type:\w+>/<arr:\d{3}>' => '/default/login',
			),
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