<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class ServiceManagerController extends BaseController {
	protected $templatePath = '/admin/';
	function formatMoneyVND($priceFloat) {
        $symbol = ' VNĐ';
        $symbol_thousand = ',';
        $decimal_place = 0;
        $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
        return $price.$symbol;
        }
	public function view(Request $request, Response $response) {
		if (!isset($request))
			return $response->withStatus(501);

        $serviceTypes = $this->getLogic('ServiceType')->loadServiceType();
		$services = $this->getLogic('Service')->loadService();
		return $this->view->render($response, '/admin/ServiceManager/service-manager.html', [
			'pageHeader' => 'Danh Sách Loại Và Dịch Vụ',
            'pageDescription' => '',
            'serviceTypes' => $serviceTypes,
			'services' => $services	,
		]);
	}
    
    // ServiceType
	// Load ServiceType
	public function loadServiceType(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);
		$serviceTypes = $this->getLogic('ServiceType')->loadServiceType();
		if (!$serviceTypes) {
			return $response->withStatus(201);
		}
		return $response->withJson($serviceTypes);
	}

	// save ServiceType
	public function saveServiceType(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getParamServiceType($request);
		$this->validateServiceType($params, $response);

		$serviceType = $this->getLogic('ServiceType')->createServiceType($params);
		if ($serviceType === true) {
			return $response->withJson($serviceType, 201);
		} else {
			return $response->withJson($serviceType, 409);
		}
		return 0;
	}
	protected function validateServiceType($params, Response $response){
		$validation = $this->validator->validateArray($params, [
			'name' => validator::notEmpty(),
            'code' => validator::notEmpty(),
            'active' => validator::notEmpty()
		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}
	public function getParamServiceType(Request $request){
		$params = array(
			'name' => $request->getParam('nameServiceType'),
			'active' => $request->getParam('active'),
			'code' => $request->getParam('codeServiceType'),
		);;
		return $params;
	}

	// Delete ServiceType
	public function deleteServiceType(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('id'),
			'active' => "0"
		);
		// $id = $request->getParam('id');

		$serviceType = $this->getLogic('ServiceType')->deleteServiceType($params);
		if ($serviceType) {
			return $response->withStatus(201);
		} else {
			return  $response->withStatus(417);
		}
	}

	// get infor ServiceType for form edit
	public function loadServiceTypeById(Request $request, Response $response){
		$id = $request->getParam('id');
		$serviceType = $this->getLogic('ServiceType')->loadServiceTypeById($id);
		return $response->withJson($serviceType, 201);
	}
	// update ServiceType
	public function updateServiceType(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('id'),
			'name' => $request->getParam('nameServiceType'),
			'active' => $request->getParam('active'),
			'code' => $request->getParam('codeServiceType')

		);
		$this->validateServiceType($params, $response);
		$serviceType = $this->getLogic('ServiceType')->updateServiceType($params);
		return $response->withJson($serviceType, 201);
	}

    // Service
	// Load Service
	public function loadService(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

			$services = $this->getLogic('Service')->loadService();

			if (!$services) {
				return $response->withJson($services, 409);
			}
		
		
		$i=0;
		foreach( $services as $price ){
			$services[$i]['price'] = $this->formatMoneyVND($price['price']);
			$i++;
		}
		return $response->withJson($services, 201);
	}

	// save Service
	public function saveService(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getParamService($request);
		$this->validateService($params, $response);

		$service = $this->getLogic('Service')->createService($params);
		if ($service) {
			return $response->withJson($service, 201);
		} else {
			return $response->withJson($service, 409);
		}
		return 0;
	}
	protected function validateService($params, Response $response){
		$validation = $this->validator->validateArray($params, [
            'name' => validator::notEmpty(),
            'price' => validator::notEmpty(),
            'unit' => validator::notEmpty(),
			'status' => validator::notEmpty(),
			'code' => validator::notEmpty(),
		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}
	public function getParamService(Request $request){
		return array(
			'name' => $request->getParam('nameService'),
			'price' => $request->getParam('price'),
			'serviceTypeId' => $request->getParam('serviceType'),
			'unit' => $request->getParam('unit'),
			'status' => $request->getParam('status'),
			'code' =>  $request->getParam('codeService')
		);
	}

	// Delete Service
	public function deleteService(Request $request, Response $response, array $args){
	
		$service = $this->getLogic('Service')->deleteService($request->getParam('id'));
		if (!$service) {
			return $response->withJson($service,409);
		} 	
		return $response->withJson($service,201);
	}

	// get infor Service for form edit
	public function loadServiceById(Request $request, Response $response){
		$id = $request->getParam('id');
		$service = $this->getLogic('Service')->loadServiceById($id);
		if (!$service) {
			return $response->withJson($service,409);
		} 	
		return $response->withJson($service,201);
	}
	// update Service
	public function updateService(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('id'),
			'serviceTypeId' => $request->getParam('serviceType'),
            'name' => $request->getParam('nameService'),
            'price' => $request->getParam('price'),
            'status' =>$request->getParam('status'),
			'unit' => $request->getParam('unit'),
			'code' => $request->getParam('codeService')

		);
		$this->validateService($params, $response);
		$service = $this->getLogic('Service')->updateService($params);
		return $response->withJson($service, 201);
	}

}
