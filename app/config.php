<?php

define('BASEDIR', __DIR__ . DIRECTORY_SEPARATOR . '..');

return array(
	// Directories
	'MicroDir' => BASEDIR . DIRECTORY_SEPARATOR . 'micro',
	'AppDir'   => BASEDIR . DIRECTORY_SEPARATOR . 'app',

	// Sitename
	'company' => 'Micro',
	'slogan'  => 'php hmvc framework',

	// Default import dir
	'import' => array(
		'components',
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