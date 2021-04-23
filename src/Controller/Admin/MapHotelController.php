<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class MapHotelController extends BaseController {
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response) {
        $floors = $this->getLogic('Floor')->loadFloor();
        $rooms = $this->getLogic('Room')->loadRoom();
        $roomTypes = $this->getLogic('RoomType')->loadRoomType();

		return $this->view->render($response, '/admin/hotel-map.html', [
			'pageHeader' => 'Sơ Đồ Khách Sạn',
            'pageDescription' => '',
            'floors' => $floors,
            'roomTypes' => $roomTypes,
            'rooms' => $rooms,
		]);
	}
}
