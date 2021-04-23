<?php

namespace Romi\Controller\User;

use Slim\Http\Request;
use Slim\Http\Response;
use Romi\Controller\BaseController;

class ProfileController extends BaseController {
	// MVC
	public function view(Request $request, Response $response) {
		$this->logger->info('view profile info');
		// way 1
		$this->view->render($response, 'admin/abc.html', [
			'name' =>  'abc' //$args['name']
		]);
		return $response;
		// way 2 
		// return $this->view->render($response, 'profile.html', [
		// 	'name' =>  'test'
		// ]);
		// way 3
		// return 'Test';
	}
}
