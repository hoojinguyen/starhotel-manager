<?php

namespace Romi\Controller\Client;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class HomePageController extends BaseController {

	public function view(Request $request, Response $response) {


		return $this->view->render($response, '/client/homepage.html', [
	
		]);
	}
}
