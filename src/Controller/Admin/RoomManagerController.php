<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class RoomManagerController extends BaseController
{
	protected $templatePath = '/admin/';


	public function view(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);
			
		$floors = $this->getLogic('Floor')->loadFloor();
		$roomTypes = $this->getLogic('RoomType')->loadRoomType();
		$rooms = $this->getLogic('Room')->loadRoom();

		return $this->view->render($response, '/admin/room/room-manager.html', [
			'pageHeader' => 'Quản Lý Phòng',
			'pageDescription' => '',
			'floors' =>  $floors,
			'roomTypes' => $roomTypes,
			'rooms' => $rooms,

		]);
	}

	//FLOOR
	// Load floor
	public function loadFloor(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$floors = $this->getLogic('Floor')->loadFloor();

		if (!$floors) {
			return $response->withJson($floors, 409);
		}
		return $response->withJson($floors, 201);
	}

	// add floor
	public function saveFloor(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getParamFloor($request);
		$this->validateFloor($params, $response);

		$floor = $this->getLogic('Floor')->createFloor($params);
		if (!$floor) {
			return $response->withJson($floor, 409);
		}
		return $response->withJson($floor, 201);
	
	}
	protected function validateFloor($params, Response $response){
		$validation = $this->validator->validateArray($params, [
			'name' => validator::notEmpty(),
			'code' => validator::notEmpty()

		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}
	public function getParamFloor(Request $request){
		date_default_timezone_set('Asia/Bangkok');
		$currentDay =  date("d-m-Y H:i:s");
		$params = array(
			'name' => $request->getParam('nameFloor'),
			'code' => $request->getParam('codeFloor'),
			'active' => "1",
			'createdAt' => $currentDay,
			'updatedAt' => $currentDay,
			'updatedBy' => 1,
			'createdBy' > 1,
			'idTenant' => 1
		);
		return $params;
	}

	// Delete floor
	public function deleteFloor(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('id'),
			'active' => "0"
		);
		$floor = $this->getLogic('Floor')->deleteFloor($params);
		if ( !$floor ) {
			return $response->withJson($floor, 409);
		}
		return $response->withJson($floor, 201);
	}

	// get infor floor for form edit
	public function loadFloorById(Request $request, Response $response){
		$id = $request->getParam('id');
		$floor = $this->getLogic('Floor')->loadFloorById($id);
		if ( !$floor ) {
			return $response->withJson($floor, 409);
		}
		return $response->withJson($floor, 201);
	
	}

	// update floor
	public function updateFloor(Request $request, Response $response, array $args){

		$params = array(
			'id' => $request->getParam('id'),
			'name' => $request->getParam('nameFloor'),
			'active' => "1",
			'code' => $request->getParam('codeFloor'),
			'idTenant' => 1
		);

		$this->validateFloor($params, $response);
		$floor = $this->getLogic('Floor')->updateFloor($params);

		if ( !$floor ) {
			return $response->withJson($floor, 409);
		}
		return $response->withJson($floor, 201);
	}




	// RoomType
	// Load RoomType
	public function loadRoomType(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$roomTypes = $this->getLogic('RoomType')->loadRoomType();

		if (!$roomTypes) {
		return $response->withJson($roomTypes, 409);
		}
		return $response->withJson($roomTypes, 201);
	}

	// save RoomType
	public function saveRoomType(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getParamRoomType($request);
		$this->validateRoomType($params, $response);

		$roomType = $this->getLogic('RoomType')->createRoomType($params);
		if ( !$roomType ) {
			return $response->withJson($roomType, 409);
		}

		return $response->withJson($roomType, 201);

	}
	protected function validateRoomType($params, Response $response){
		$validation = $this->validator->validateArray($params, [
			'name' => validator::notEmpty(),
			'price' => validator::notEmpty()->optional(validator::numeric()),
			'code' => validator::notEmpty(),
			'numOfPeopleStay' => validator::notEmpty()->optional(validator::numeric())
		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}
	public function getParamRoomType(Request $request){
		$params = array(
			'name' => $request->getParam('nameRoomType'),
			'price' => $request->getParam('price'),
			'active' => "1",
			'code' => $request->getParam('codeRoomType'),
			'numOfPeopleStay' => $request->getParam('numOfPeopleStay'),

		);;
		return $params;
	}

	// Delete RoomType
	public function deleteRoomType(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('id'),
			'active' => "0"
		);
		// $id = $request->getParam('id');

		$roomType = $this->getLogic('RoomType')->deleteRoomType($params);
		if ( !$roomType ) {
			return $response->withJson($roomType, 409);
		}
		return $response->withJson($roomType, 201);
	}

	// get infor RoomType for form edit
	public function loadRoomTypeById(Request $request, Response $response){

		$id = $request->getParam('id');
		$roomType = $this->getLogic('RoomType')->loadRoomTypeById($id);

		if ( !$roomType ) {
			return $response->withJson($roomType, 409);
		}
		return $response->withJson($roomType, 201);
	}
	// update RoomType
	public function updateRoomType(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('id'),
			'name' => $request->getParam('nameRoomType'),
			'price' => $request->getParam('price'),
			'numOfPeopleStay' => $request->getParam('numOfPeopleStay'),
			'active' => "1",
			'code' => $request->getParam('codeRoomType')

		);
		$this->validateRoomType($params, $response);
		$roomType = $this->getLogic('RoomType')->updateRoomType($params);
		
		if ( !$roomType ) {
			return $response->withJson($roomType, 409);
		}
		return $response->withJson($roomType, 201);
	}

	// Room
	// Load Room
	public function loadRoom(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$rooms = $this->getLogic('Room')->loadRoom();

		if ( !$rooms ) {
			return $response->withJson($rooms, 409);
		}
		return $response->withJson($rooms, 201);
	}

	// save Room
	public function saveRoom(Request $request, Response $response, array $args){
		if (!isset($request))
			return $response->withStatus(501);

		$params = $this->getParamRoom($request);
		$this->validateRoom($params, $response);

		$room = $this->getLogic('Room')->createRoom($params);

		if ( !$room ) {
			return $response->withJson($room, 409);
		}
		return $response->withJson($room, 201);
	}
	
	protected function validateRoom($params, Response $response){
		$validation = $this->validator->validateArray($params, [
			'nameRoom' => validator::notEmpty()
		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}
	}
	public function getParamRoom(Request $request){
		$params = array(
			'floorId' => $request->getParam('floor'),
			'roomTypeId' => $request->getParam('roomType'),
			'nameRoom' => $request->getParam('nameRoom'),
			'status' => $request->getParam('statusRoom'),
			'description' => "Phòng khách sạn"

		);;
		return $params;
	}

	// Delete Room
	public function deleteRoom(Request $request, Response $response, array $args){
		
		$id = $request->getParam('id');

		$room = $this->getLogic('Room')->deleteRoom($id);

		if ( !$room ) {
			return $response->withJson($room, 409);
		}
		return $response->withJson($room, 201);
	}

	// get infor Room for form edit
	public function loadRoomById(Request $request, Response $response){
		$id = $request->getParam('id');
		$room = $this->getLogic('Room')->loadRoomById($id);
		
		if ( !$room ) {
			return $response->withJson($room, 409);
		}
		return $response->withJson($room, 201);

	}
	// update Room
	public function updateRoom(Request $request, Response $response, array $args){
		$params = array(
			'id' => $request->getParam('idRoom'),
			'floorId' => $request->getParam('floor'),
			'roomTypeId' => $request->getParam('roomType'),
			'nameRoom' => $request->getParam('nameRoom'),
			'status' => $request->getParam('statusRoom'),
			'description' => "Phòng khách sạn"

		);
		$this->validateRoom($params, $response);
		$room = $this->getLogic('Room')->updateRoom($params);

		if ( !$room ) {
			return $response->withJson($room, 409);
		}
		return $response->withJson($room, 201);

		
	}
}
