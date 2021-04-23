<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class DashboardController extends BaseController {
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response) {
		
		 $this->view->render($response, '/admin/page.html', [
			'pageHeader' => 'Bảng Điều Khiển',
			'pageDescription' => '',
	
		]);
		
		return $response;
	}

}
