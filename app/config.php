<?php

return [
	// Directories
	'MicroDir' => __DIR__ . '/../micro',
	'AppDir'   => __DIR__ ,

	// Sitename
	'company' => 'Micro',
	'slogan'  => 'simply hmvc php framework',

	// Default import dir
	'import' => [
		'extensions',
		'components',
		'widgets',
		'models',
	],

	// Print run time
	'timer' => true,
	// Language
	'lang' => 'en',

	// Setup components
	'components' => [
		// Request manager
		'request' => [
			'class' => '\Micro\web\MRequest',
			'routes' => [
				'/login'=>'/default/login',
				'/logout'=>'/default/logout',
				'/login/<num:\d+>/<type:\w+>/<arr:\d{3}>' => '/default/login',

				'/blog/post/index/<page:\d+>' => '/blog/post',
				'/blog/post/<id:\d+>' => '/blog/post/view',
			],
		],
		// Default session
		'session' => [
			'class' => '\Micro\base\MSession',
			'autoStart' => true,
		],
		// Flash messages
		'flash' => [
			'class' => '\Micro\web\helpers\MFlashMessage',
			'depends' => 'session'
		],
		// DataBase
		'db' => [
			'class'=> '\Micro\db\MDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=micro',
			'username'=>'micro',
			'password'=>'micro',
			'charset'=>'utf8'
		],
		'user'=>[
			'class'=>'\Micro\web\helpers\MUser',
			'depends'=>'session'
		]
	]
];