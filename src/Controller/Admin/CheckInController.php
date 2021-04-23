<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Shared\Enum\BookRoomStatus;

class CheckInController extends BaseController
{
	protected $templatePath = '/admin/';

	public function view(Request $request, Response $response)
	{
		date_default_timezone_set('Asia/Bangkok');
		$listCheckins =  $this->getLogic('Checkout')->loadListRoom(BookRoomStatus::BOOK_ROOM, BookRoomStatus::CHECK_IN);

		if ($listCheckins) {
			$i = 0;
			foreach ($listCheckins as  &$daytime) {
				$listCheckins[$i] += ["dayCheckinOrigin" => date_format($daytime['dayCheckin'], 'Y-m-d')];
				$listCheckins[$i] += ["dayCheckoutOrigin" => date_format($daytime['dayCheckout'], 'Y-m-d')];
				// array_push($listCheckins[$i] , date_format($daytime['dayCheckin'], 'Y-m-d'));
				// array_push($listCheckins[$i],date_format($daytime['dayCheckout'], 'Y-m-d'));
				$listCheckins[$i]['dayCheckin'] = date_format($daytime['dayCheckin'], 'd/m/Y');
				$listCheckins[$i]['dayCheckout'] = date_format($daytime['dayCheckout'], 'd/m/Y');
				$listCheckins[$i]['timeArrival'] = date_format($daytime['timeArrival'], 'H:i');
				$listCheckins[$i]['deposited'] = $this->formatMoneyVND($listCheckins[$i]['deposited']);
				$listCheckins[$i]['valueDiscount'] = $listCheckins[$i]['valueDiscount'] . "%";
				$listCheckins[$i]['dayBooking'] = date_format($daytime['dayBooking'], 'd/m/Y H:i');
				$i++;
			}
		} else {
			$listCheckins = "";
		}

		$bookingCode = $request->getQueryParam('bookingCode');

		$currentDay = date("d/m/Y");
		// $a = new \DateTime('05:06');
		// $b = new \DateTime('23:59');
		// $interval = $a->diff($b);
		// $h = $interval->format("%h");
		// $m = $interval->format("%i");
		// if($h <= 0){
		// 	var_dump($m);
		// }
		// else {
		// 	if($h > 12 ){
		// 		var_dump($currentDay);
		// 	}
		// 	else{
		// 		var_dump($h);
		// 	}	
		// }

		return $this->view->render($response, '/admin/bookroom/check-in.html', [
			'pageHeader' => 'Nhận Phòng Khách Sạn',
			'pageDescription' => "",
			'bookingCode' => $bookingCode,
			'listCheckins' => $listCheckins,
			'currentDay' => $currentDay

		]);
	}

	public function getInfoBookRoom(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$bookingCode = $request->getParam("bookingCode");
		$res = $this->getLogic("Checkin")->getInfoBookRoom($bookingCode);
		if ($res) {
			// $res = json_encode($res);
			$i = 0;
			foreach ($res as $daytime) {
				$res[$i]['dayCheckin'] = date_format($daytime['dayCheckin'], 'd/m/Y');
				$res[$i]['dayCheckout'] = date_format($daytime['dayCheckout'], 'd/m/Y');
				$res[$i]['timeArrival'] = date_format($daytime['timeArrival'], 'H:m');
				$i++;
			}
			return $response->withJson($res, 201);
		} else {
			return $response->withJson($res, 409);
		}
	}
	public function confirmCheckIn(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$idBookingRoom = $request->getParam("idBookingRoom");
		$res = $this->getLogic("Checkin")->changeStatusBookingRoom($idBookingRoom, BookRoomStatus::CHECK_IN);
		if ($res) {
			return $response->withJson($res, 201);
		} else {
			return $response->withJson($res, 409);
		}
	}

	public function cancelBookRoom(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$idBookingRoom = $request->getParam("idBookingRoom");
		$res = $this->getLogic("Checkin")->changeStatusBookingRoom($idBookingRoom, BookRoomStatus::CANCEL);
		if ($res) {
			return $response->withJson($res, 201);
		} else {
			return $response->withJson($res, 409);
		}
	}

	public function changeRoom(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$paramChange = array(
			'dayCheckin' => new \Datetime($request->getParam("dayCheckin")),
			'dayCheckout' => new \Datetime($request->getParam("dayCheckout")),
			'idBookingRoom' => $request->getParam('idBookingRoom'),
			'idRoomOld' => $request->getParam('idRoomOld'),
			'idRoomNew' => $request->getParam('idRoomNew'),
			'idBill' => $request->getParam('idBill'),
			'idRoomTypeOld' => $request->getParam('idRoomTypeOld'),
			'idRoomTypeNew' => $request->getParam('idRoomTypeNew'),
		);

		if ($paramChange['idRoomTypeOld'] == $paramChange['idRoomTypeNew']) {
			// only change room id 
			$changeRoom = $this->getLogic('checkin')->changeRoom($paramChange['idBookingRoom'], $paramChange['idRoomNew']);
			if (!$changeRoom) {
				return $response->withJson($changeRoom, 409);
			}
			return $response->withJson($changeRoom, 201);
		} else {
			// change room id , change amount of bill , change fee amount of bill detail

			// change room
			$changeRoom = $this->getLogic('checkin')->changeRoom($paramChange['idBookingRoom'], $paramChange['idRoomNew']);
			if (!$changeRoom) {
				return $response->withJson($changeRoom, 409);
			}

			// change bill
			$amount = $this->amountRate($paramChange['idRoomNew'], $paramChange['dayCheckin'], $paramChange['dayCheckout']);
			$changeBill = $this->getLogic('Bill')->changeBill($amount,$paramChange['idBill']);
			if (!$changeBill) {
				return $response->withJson($changeBill, 409);
			}

			// change bill detail
			date_default_timezone_set('Asia/Bangkok');
			$currentDay =  date("Y-m-d H:i");
			$quotation = $this->getLogic('Accounting')->accountByDay($paramChange['idRoomNew'], $paramChange['dayCheckin'], $paramChange['dayCheckout']);
			for ($i = 0; $i < count($quotation); $i++) {
				$date  = date_format(date_create($quotation[$i][date]), 'Y-m-d');
				$billDetailChange = $this->getLogic("BillDetail")->changeBillDetail($paramChange['idBill'], $date, $quotation[$i][amount], $currentDay);
				if (!$billDetailChange) {
					return $response->withJson($billDetailChange, 409);
				}
			}

			return $response->withJson($changeRoom, 201);
		}
	}

	public function amountRate($roomId, $checkinDay, $checkoutDay)
	{
		$amount = 0;
		$quotation = $this->getLogic('Accounting')->accountByDay($roomId, $checkinDay, $checkoutDay);
		for ($i = 0; $i < count($quotation); $i++) {
			$amount +=  $quotation[$i]["amount"];
		}
		return $amount;
	}


	public function getGuestByTerm(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$term = $request->getParam('term');
		$result = $this->getLogic('Guest')->getGuestByTerm($term);

		if ($result) {
			return $response->withJson($result, 201);
		} else {
			return $response->withJson($result, 409);
		}
	}

	public function getInfoGuestInRoom(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$idBookingRoom = $request->getParam("idBookingRoom");
		$res = $this->getLogic("Checkin")->getInfoGuestInRoom($idBookingRoom);
		if ($res) {
			return $response->withJson($res, 201);
		} else {
			return $response->withJson($res, 409);
		}
	}

	public function addGuestAndGuestInRoom(Request $request, Response $response)
	{
		date_default_timezone_set('Asia/Bangkok');
		$idCardIssueDate = date_format(date_create($request->getParam('idCardIssueDate')), 'd-m-Y');
		$idCardExpiryDate  = date_format(date_create($request->getParam('idCardExpiryDate')), 'd-m-Y');
		$now = date_create()->format('Y-m-d');
		$paramGuest = array(
			'name' => $request->getParam('name'),
			'gender' => $request->getParam('gender'),
			'phoneNumber' => $request->getParam('phoneNumber'),
			'idCardNo' => $request->getParam('idCardNo'),
			'idCardIssueDate' => $idCardIssueDate,
			'idCardExpiryDate' => $idCardExpiryDate,
			'idCardIssuePlace' => $request->getParam('idCardIssuePlace'),
			'yearOfBirth' => $request->getParam('yearOfBirth'),
			'address' => $request->getParam('address'),
			'idTenant' => 1,
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $now,
			'updatedAt' => $now,

		);

		$idBookingRoom = $request->getParam('idBookingRoom');
		$guest = $this->getLogic('Guest')->createGuest($paramGuest);
		$idGuest = reset($guest);
		$check =  $this->getLogic('Checkin')->checkExitsGuestInRoom($idGuest, $idBookingRoom);
		if ($check) {
			$paramGuestInRoom = array(
				'idBookingRoom' => $idBookingRoom,
				'idGuest' => $idGuest,
				'idTenant' => 1,
				'createdBy' => 1,
				'updatedBy' => 1,
				'createdAt' => $now,
				'updatedAt' => $now,

			);
			$guestInRoom = $this->getLogic('Checkin')->createGuestInRoom($paramGuestInRoom);
			if ($guestInRoom) {
				return $response->withJson($guestInRoom, 201);
			} else {
				return $response->withJson($guestInRoom, 409);
			}
		} else {
			return $response->withJson($check, 409);
		}
	}
}
