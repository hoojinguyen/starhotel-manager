<?php

namespace Romi\Tests;

use Faker\Factory;
use PHPUnit\Framework\TestCase;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use Doctrine\ORM\EntityManager;

abstract class BaseTestCase extends TestCase {

	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';
	const METHOD_DELETE = 'DELETE';
	
	private $app;

	protected function setUp() {

		parent::setUp();
		$this->createApplication();

		//TODO: run migration or initial database here.
	}

	protected function tearDown() {

		//TODO: rollback migration

		unset($this->app);
		parent::tearDown();
	}

	protected function createApplication() {
		//require __DIR__ . '/../../vendor/autoload.php';
		$settings = require __DIR__ . '/../src/settings.php';

		$this->app = $app = new App($settings);
		require APP_ROOT . '/dependencies.php';
		require APP_ROOT . '/middleware.php';
		require APP_ROOT . '/routes.php';
	}

	protected function runApp($requestMethod, $requestUri, $requestData = null, $headers = []) {
		// Create a mock environment for testing with
		$environment = Environment::mock(
						array_merge([
							'REQUEST_METHOD' => $requestMethod,
							'REQUEST_URI' => $requestUri,
							'Content-Type' => 'application/json',
							'X-Requested-With' => 'XMLHttpRequest',
						], $headers));
		
		// Set up a request object based on the environment
		$request = Request::createFromEnvironment($environment);
		// Add request data, if it exists
		if (isset($requestData)) {
			$request = $request->withParsedBody($requestData);
		}
		// Set up a response object
		$response = new Response();
		// Process the application and Return the response
		return $this->app->process($request, $response);
	}

	public function getEntityManager() : EntityManager{
		return $this->app->getContainer()->get(EntityManager::class);
	}
	
	public function getLogic($name){
		return $this->app->getContainer()->get('logic')->findLogic($name);
	}
	
	public function request($requestMethod, $requestUri, $requestData = null, $headers = []) {
		return $this->runApp($requestMethod, $requestUri, $requestData, $headers);
	}

}
