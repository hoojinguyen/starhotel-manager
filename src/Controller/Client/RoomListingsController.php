<?php

namespace Romi\Controller\Client;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Respect\Validation\Rules\Length;

class RoomListingsController extends BaseController
{

	public function view(Request $request, Response $response)
	{

		date_default_timezone_set('Asia/Bangkok');
		$checkinTime = date_format(date_create($request->getQueryParam('checkinDay')), 'Y-m-d');
		$checkoutTime  = date_format(date_create($request->getQueryParam('checkoutDay')), 'Y-m-d');
		$roomType = 0;

		$listRoomEmpty = $this->getLogic('BookRoom')->findRoomEmpty($checkinTime, $checkoutTime, $roomType);
		
		$singleRoom = array();
		$doubleRoom = array();
		$vipRoom = array();
		foreach ($listRoomEmpty as $list) {
			if ($list['idRoomType'] == 1) {
				array_push($singleRoom, $list);
			} else if ($list['idRoomType'] == 2) {
				array_push($doubleRoom, $list);
			} else if ($list['idRoomType'] == 3) {
				array_push($vipRoom, $list);
			}
		}


		$price = $this->getLogic('Accounting')->getPriceRoomType();
		$priceDefaultDay = array();
		$priceWeekendDay = array();

		foreach ($price as $p) {
			if ($p['chargeType'] == "DAY_DEFAULT") {
				array_push($priceDefaultDay, $p);
			} else if ($p['chargeType'] == "DAY_WEEKEND") {
				array_push($priceWeekendDay, $p);
			}
		}


		$arraySingleRoom = array(
			"numRoom" => count($singleRoom),
			'idRoom' =>  $singleRoom[0][id],
			'roomType' =>  $singleRoom[0][roomType],
			'priceDayDefault' => $this->formatMoneyVND($priceDefaultDay[0]["price"]),
			'priceDayWeekend' => $this->formatMoneyVND($priceWeekendDay[0]["price"])
		);
		$arrayDoubleRoom = array(
			"numRoom" => count($doubleRoom),
			'idRoom' =>  $doubleRoom[0][id],
			'roomType' =>  $doubleRoom[0][roomType],
			'priceDayDefault' => $this->formatMoneyVND($priceDefaultDay[1]["price"]),
			'priceDayWeekend' => $this->formatMoneyVND($priceWeekendDay[1]["price"])
		);
		$arrayVipRoom = array(
			"numRoom" => count($vipRoom),
			'idRoom' =>  $vipRoom[0][id],
			'roomType' =>  $vipRoom[0][roomType],
			'priceDayDefault' => $this->formatMoneyVND($priceDefaultDay[2]["price"]),
			'priceDayWeekend' => $this->formatMoneyVND($priceWeekendDay[2]["price"])
		);

		
		return $this->view->render($response, '/client/room-listings.html', [
			'arraySingleRoom' => $arraySingleRoom,
			'arrayDoubleRoom' => $arrayDoubleRoom,
			'arrayVipRoom' 	  => $arrayVipRoom,
		]);
	}
}
