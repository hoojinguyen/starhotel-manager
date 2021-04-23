<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Domain\Config;

define('SINGLE_ROOM', 1);
define('DOUBLE_ROOM', 2);

class PriceConfigController extends BaseController
{
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response)
	{
		$price = $this->getLogic('PriceConfig')->getRoomPrice(SINGLE_ROOM);

		return $this->view->render($response, '/admin/price-config.html', [
			'pageHeader' => 'Cấu hình giá phòng',
			'pageDescription' => '',
			'price' => $price,
		]);
	}

	public function getPrice(Request $request, Response $response){
		$roomType = $request->getParam('roomType');
		$price = $price = $this->getLogic('PriceConfig')->getRoomPrice($roomType);
		return $response->withJson($price, 201);
	}

	public function save(Request $request, Response $response){
		$id = $request->getParam('id');
		$value = $request->getParam('value');
		$newPrice = $this->getLogic('PriceConfig')->save($id, $value);
		return $response->withJson($newPrice, 201);
	}


}
