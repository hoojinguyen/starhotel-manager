<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class ServiceController extends BaseController {
	protected $templatePath = '/admin/';

	public function view(Request $request, Response $response) {

		
		$infoServices =  $this->getLogic('Service')->loadInfoService("1");

		$i=0;
		foreach( $infoServices as $price ){
			$infoServices[$i]['price'] = $this->formatMoneyVND($price['price']);
			$i++;
		}

		return $this->view->render($response, '/admin/service.html', [
			'pageHeader' => 'Bảng Giá Các Loại Dịch Vụ',
            'pageDescription' => '',
			'infoServices' => $infoServices,
		]);
	}
}
