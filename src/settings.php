<?php

if (!defined('APP_ROOT')){
	define('APP_ROOT', __DIR__);
}

// Load .env file
if (file_exists(APP_ROOT . '/../.env')) {
	if (isset($_ENV['environment']) && $_ENV['enviroment'] === 'test'){
		$dotenv = new Dotenv\Dotenv(APP_ROOT . '/../', '.env.test');
	}else{
		$dotenv = new Dotenv\Dotenv(APP_ROOT . '/../');
	}
	$dotenv->load();
}

return [
	'settings' => [
		'displayErrorDetails' => getenv('APP_DEBUG') === 'true' ? true : false, // set to false in production
		'addContentLengthHeader' => false, // Allow the web server to send the content-length header
		// App Settings
        'app' => [
            'name' => getenv('APP_NAME'),
            'url'  => getenv('APP_URL'),
            'env'  => getenv('APP_ENV'),
        ],
		// Renderer settings
		'renderer' => [
			'template_path' => APP_ROOT . '/../templates/',
			'template_cache' => APP_ROOT . '/../templates/cache/',
		],
		'viewer' => [
			'template_path' => APP_ROOT . '/../templates/',
			'template_cache' => getenv('APP_CACHE') === 'false' ? false : APP_ROOT . '/../templates/cache/',
		],
		'cors' => null !== getenv('CORS_ALLOWED_ORIGINS') ? getenv('CORS_ALLOWED_ORIGINS') : '*',
		// jwt settings
        'jwt'  => [
			'attribute' => 'token',
            'secret' => getenv('JWT_SECRET'),
            'secure' => false,
			'relaxed' => ["localhost", "dev.example.com"],
            'header' => "Authorization",
            'regexp' => "/Token\s+(.*)$/i",
            'passthrough' => ['OPTIONS'],
			'ignore' => ['/api/token', '/api/ping'],
			'algorithm' => ['HS256']
        ],
		// Monolog settings
		'logger' => [
			'name' => 'slim-app',
			'path' => isset($_ENV['docker']) ? 'php://stdout' : APP_ROOT . '/../logs/app.log',
			'level' => getenv('APP_LOG_LEVEL') === 'debug' ?  \Monolog\Logger::DEBUG : \Monolog\Logger::ERROR,
		],
		// Doctrine
		'doctrine' => [
			// if true, metadata caching is forcefully disabled
			'dev_mode' => true,
			// path where the compiled metadata info will be cached
			// make sure the path exists and it is writable
			'cache_dir' => APP_ROOT . '/../var/doctrine',
			// you should add any other path containing annotated entity classes
			'metadata_dirs' => [APP_ROOT . '/Domain'],
			'connection' => [
				'driver' => getenv('DB_CONNECTION'),
				'host' => getenv('DB_HOST'),
				'port' => getenv('DB_PORT'),
				'dbname' => getenv('DB_DATABASE'),
				'user' => getenv('DB_USERNAME'),
				'password' => getenv('DB_PASSWORD'),
				'charset' => 'utf8mb4',
				'collation' => 'utf8_unicode_ci',
				'prefix' => '',
			]
		],
		// FTP 
		'ftp' => [
			'ftp_server' => getenv('FTP_SERVER'),
			'ftp_port' => getenv('FTP_PORT'),
			'sftp_server' => getenv('SFTP_SERVER'),
			'sftp_port' => getenv('SFTP_PORT'),
			'sftp_enabled' =>  getenv('SFTP_ENABLED') === 'true' ? true : false,
			'ftp_username' => getenv('FTP_USERNAME'),
			'ftp_password' => getenv('FTP_PASSWORD'),
			'folder_import' => getenv('FOLDER_IMPORT'),
		]
	],
];
