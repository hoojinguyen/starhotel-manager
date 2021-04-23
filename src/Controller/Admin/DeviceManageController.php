<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Domain\Device;

class DeviceManageController extends BaseController
{
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response)
	{

		$device =$this->getLogic('DeviceManage')->loadAllDevice() ;
		$deviceInRoom= $this->getLogic('DeviceManage')->loadDeviceInRoom(1);

		$room = $this->getLogic("DeviceManage")->loadRoom();

		var_dump($room);

		return $this->view->render($response, '/admin/device-manage.html', [
			'pageHeader' => 'Quản lý thiết bị',
			'devices' =>$device ,
			'devicesInRoom' => $deviceInRoom ,
			'rooms' => $room
		]);
	}

	// Save

	public function saveDevice(Request $request, Response $response) {
		$data = $this->getDeviceDataFromRequest($request);
		$device = $this->getLogic('DeviceManage')->saveDevice($data);
		return $response->withJson($device, 201);
	}

	public function saveDeviceInRoom(Request $request, Response $response) {
		$deviceId = $request->getParam('deviceId');
		$roomTypeId = $request->getParam('roomTypeId');
		$quantity = $request->getParam('quantity');
		$deviceInRoom = $this->getLogic('DeviceManage')->saveDeviceInRoom($roomTypeId, $deviceId, $quantity);


		return $response->withJson($deviceInRoom, 201);
	}

	// Update

	public function updateDevice(Request $request, Response $response){
		$id = $request->getParam('id');
		$data = $this->getDeviceDataFromRequest($request);
		$device = $this->getLogic('DeviceManage')->updateDevice($id, $data);
		return $response->withJson($device, 201);
	}

	public function updateDeviceInRoom(Request $request, Response $response){
		$id = $request->getParam("id");
		$value = $request->getParam("value");

		return $response->withJson($this->getLogic("DeviceManage")->updateDeviceInRoom($id, $value), 201);
	}

	// Load

	public function loadDevice(Request $request, Response $response){
		return $response->withJson($this->getLogic("DeviceManage")->loadAllDevice(), 201);
	}

	
	public function loadDeviceInRoom(Request $request, Response $response){
		$roomType = $request->getParam('roomType');
		return $response->withJson($this->getLogic("DeviceManage")->loadDeviceInRoom($roomType), 201);
	}

	// Delete

	public function deleteDevice(Request $request, Response $response){
		$id = $request->getParam("id");
		return $response->withJson($this->getLogic('DeviceManage')->deleteDevice($id), 201);
	}

	public function deleteDeviceInRoom(Request $request, Response $response){
		$id = $request->getParam("id");
		return $response->withJson($this->getLogic('DeviceManage')->deleteDeviceInRoom($id), 201);
	}











	// Support function

	public function loadDeviceById(Request $request, Response $response){
		$data = $this->getEntityManager()->getRepository(Device::class)->findOneBy(array('id' => $request->getParam('id')));
		$device = [
			'name'=> $data->getName(),
			'code'=> $data->getCode(),
			'price'=> $data->getPrice(),
			'id'=> $data->getId(),
			'importDate'=> date_format($data->getImportDate(),'d-m-Y'),
		];
		return $response->withJson($device, 201);
	}

	public function getDeviceDataFromRequest(Request $request){
		$data['name'] = $request->getParam('name');
		$data['code'] = $request->getParam('code');
		$data['price'] = $request->getParam('price');
		$data['importDate'] = $request->getParam('importDate');
		$data['idTenant'] = 1;
		$data['createdBy'] =1; 
		$data['updatedBy'] =1; 
		return $data;
	}


}
