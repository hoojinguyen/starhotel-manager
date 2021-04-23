<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Shared\Enum\BookRoomStatus;
use Romi\Shared\Enum\FeeType;


class BookRoomController extends BaseController
{
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response)
	{
		date_default_timezone_set('Asia/Bangkok');
		$currentDay = date("Y-m-d");

		$bookingCode = $this->getLogic(BookRoom)->createBookingCode();

		$roomTypes = $this->getLogic('RoomType')->loadRoomType();

		return $this->view->render($response, '/admin/bookroom/book-room.html', [
			'pageHeader' => 'Đặt Phòng Cho Khách Lẻ',
			'pageDescription' => '',
			'bookingCode' => $bookingCode,
			'dayBooking' => $currentDay,
			'roomTypes' => $roomTypes
		]);
	}

	public function findRoomEmpty(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$params = array(
			'dayCheckin' => $request->getParam('dayCheckin'),
			'dayCheckout' => $request->getParam('dayCheckout'),
		);

		$validation = $this->validator->validateArray($params, [
			'dayCheckin' => validator::notEmpty(),
			'dayCheckout' => validator::notEmpty(),
		]);
		if ($validation->failed()) {
			$result = $validation->getErrors();
			return $response->withJson($result, 422);
		}

		date_default_timezone_set('Asia/Bangkok');
		$checkinTime = date_format(date_create($params['dayCheckin']), 'Y-m-d');
		$checkoutTime  = date_format(date_create($params['dayCheckout']), 'Y-m-d');
		$roomType = $request->getParam('roomType');

		$listRoomEmpty = $this->getLogic('BookRoom')->findRoomEmpty($checkinTime, $checkoutTime, $roomType);
		if ($listRoomEmpty) {
			return $response->withJson($listRoomEmpty, 201);
		} else {
			return $response->withStatus(417);
		}
	}



	public function saveBookRoom(Request $request, Response $response)
	{

		if (!isset($request))
			return $response->withStatus(501);


		date_default_timezone_set('Asia/Bangkok');
		$currentDay =  date("Y-m-d H:i");
		$dayCheckin = date_format(date_create($request->getParam('dayCheckin')), 'd-m-Y H:i:s');
		$dayCheckout  = date_format(date_create($request->getParam('dayCheckout')), 'd-m-Y H:i:s');
		$dayBooking  = date_format(date_create($currentDay), 'd-m-Y H:i:s');
		$timeArrival = date_format(date_create($request->getParam('timeArrival')), 'H:i');
		$adult = $request->getParam('adult');
		$child = $request->getParam('child');
		$deposited = $request->getParam('deposited');
		if ($adult == 0) {
			$adult = 1;
		}

		// Create Contact 
		$idContact = $this->createContact($request, $response);

		$paramBooking = array(
			'dayCheckin' => $dayCheckin,
			'dayCheckout' => $dayCheckout,
			'dayBooking' => $dayBooking,
			'timeArrival' => $timeArrival,

			'roomType' => $request->getParam('roomType'),
			'adult' => $adult,
			'child' => $child,
			'discount' => $request->getParam('discount'),
			'deposited' => $deposited,

			'idRoom' => $request->getParam('idRoom'),
			'idContact' => $idContact,
			'idTenant' => 1,

			'bookingCode' => $request->getParam('bookingCode'),
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $dayCheckin,
			'updatedAt' => $dayCheckin,
			'note' => $request->getParam('note'),
			'status' => BookRoomStatus::BOOK_ROOM,
		);


		// Create Booking
		$idBooking = $this->createBooking($paramBooking, $response);

		// Create Booking Room
		$idBookingRoom = $this->createBookingRoom($paramBooking, $idBooking, $response);

		// Check Discount
		$codeDiscount = $paramBooking['discount'];
		if ($codeDiscount == "") {
			$idDiscount = 1;
			$valueDiscount = 0;
		} else {
			$discount = $this->checkDiscount($paramBooking['discount']);
			if ($discount == false) {
				$idDiscount = 1;
				$valueDiscount = 0;
			} else {
				$idDiscount = $discount[0]['id'];
				$valueDiscount = $discount[0]['value'];
			}
		}


		$paramBills = array(
			'checkinDay' => new \Datetime($request->getParam("dayCheckin")),
			'checkoutDay' => new \Datetime($request->getParam("dayCheckout")),
			'createdAt' => $request->getParam("dayCheckin"),
			'deposited' => $paramBooking['deposited'],
			'idDiscount' => $idDiscount,
			'valueDiscount' => 	$valueDiscount,
			'roomId' => $paramBooking['idRoom'],
			'idBookingRoom' => $idBookingRoom
		);

		// Create Bill 
		$idBill = $this->createBill($paramBills, $response);

		// Create Bill Detail
		$billDetail  = $this->createBillDetail($paramBills, $idBill, $response);

		// Save Payment deposited
		if ($deposited != 0 || $deposited != "") {
			$this->savePaymentDeposited($deposited, $idBooking, $idBookingRoom, $request->getParam("dayCheckin"));
		}
		return $response->withJson($billDetail, 201);
	}


	public function createContact(Request $request, Response $response)
	{
		$paramContact = array(
			'nameContact' => strtoupper($request->getParam('nameContact')),
			'phoneNumberContact' => $request->getParam('phoneNumberContact'),
			'emailContact' => $request->getParam('emailContact'),
			'idTenant' => 1
		);

		$validationContact = $this->validator->validateArray($paramContact, [
			'nameContact' => validator::notEmpty(),
			'phoneNumberContact' => validator::notEmpty(),
		]);
		if ($validationContact->failed()) {
			$rsContact = $validationContact->getErrors();
			return $response->withJson($rsContact, 422);
		}

		$contact = $this->getLogic("Contact")->createContact($paramContact);

		if (!$contact) {
			return $response->withJson($contact, 409);
		}
		$idContact = reset($contact);
		return $idContact;
	}

	public function createBooking($paramBooking, Response $response)
	{
		$validationBooking = $this->validator->validateArray($paramBooking, [
			'bookingCode' => validator::notEmpty(),
			'dayCheckin' => validator::notEmpty(),
			'dayCheckout' => validator::notEmpty(),
			'bookingCode' => validator::notEmpty(),
			'idRoom' => validator::notEmpty(),

		]);

		if ($validationBooking->failed()) {
			$rsBooking = $validationBooking->getErrors();
			return $response->withJson($rsBooking, 422);
		}

		$booking = $this->getLogic("BookRoom")->createBooking($paramBooking);

		if (!$booking) {
			return $response->withJson($booking, 409);
		}
		$idBooking = reset($booking);

		return $idBooking;
	}

	public function createBookingRoom($paramBooking, $idBooking, Response $response)
	{
		$bookingRoom = $this->getLogic("BookRoom")->createBookingRoom($paramBooking, $idBooking);
		if (!$bookingRoom) {
			return $response->withJson($bookingRoom, 409);
		}
		$idBookingRoom = reset($bookingRoom);
		return $idBookingRoom;
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

	public function createBill($paramBills, Response $response)
	{
		$amount = $this->amountRate($paramBills['roomId'], $paramBills['checkinDay'], $paramBills['checkoutDay']);
		$priceDiscount = ($amount * $paramBills['valueDiscount']) / 100;
		$paramBill = array(
			'idDiscount' => $paramBills['idDiscount'],
			'idTenant' => 1,
			'amount' => $amount,
			'deposited' => $paramBills['deposited'],
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $paramBills['createdAt'],
			'updatedAt' => $paramBills['createdAt'],
			'priceDiscount' => $priceDiscount
		);

		// Create Bill
		$bill = $this->getLogic("Bill")->createBill($paramBill);
		if (!$bill) {
			return $response->withJson($bill, 409);
		}

		$idBill = reset($bill);
		return $idBill;
	}

	public function createBillDetail($paramBills, $idBill, Response $response)
	{
		$quotation = $this->getLogic('Accounting')->accountByDay($paramBills['roomId'], $paramBills['checkinDay'], $paramBills['checkoutDay']);
		for ($i = 0; $i < count($quotation); $i++) {
			$date  = date_format(date_create($quotation[$i][date]), 'Y-m-d');
			$paramBillDetail = array(
				'idBill' => $idBill,
				'idBookingRoom' => $paramBills['idBookingRoom'],
				'idTenant' => 1,

				'feeAmount' => $quotation[$i][amount],
				'feeName' => $date,
				'typeFee' => FeeType::FEE_DATE,
				'description' => "",
				'createdBy' => 1,
				'updatedBy' => 1,
				'createdAt' => $paramBills['createdAt'],
				'updatedAt' => $paramBills['createdAt'],
			);
			$billDetail = $this->getLogic("BillDetail")->createBillDetail($paramBillDetail);
			if (!$billDetail) {
				return $response->withJson($billDetail, 409);
			}
		}
	}


	public function savePaymentDeposited($deposited, $idBooking, $idBookingRoom, $createAt)
	{
		$paramPayment = array(
			'idTenant' => 1,
			'idBooking' => $idBooking,
			'idBookingRoom' => $idBookingRoom,
			'amount' => $deposited,
			'paymentAt' => $createAt,
			'description' => "Tiền trả trước",
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $createAt,
			'updatedAt' => $createAt,
		);
		$payment = $this->getLogic("Payment")->createPayment($paramPayment);

		$paymentId = reset($payment);
		$now = new \Datetime('now');
		$paramPaymentRef = array(
			'idTenant' => 1,
			'idPayment' => $paymentId,
			'amount' => $deposited,
			'year' => $now->format('Y'),
			'month' => $now->format('m'),
			'quarter' => $this->getLogic('Report')->getQuarterFromDate($now),
		);
		$this->getLogic("PaymentRef")->createPaymentRef($paramPaymentRef);
	}

	public function checkDiscount($codeDiscount)
	{
		$discount = $this->getLogic("Discount")->checkDiscount($codeDiscount);
		if (!$discount) {
			return false;
		}
		return $discount;
	}
}
