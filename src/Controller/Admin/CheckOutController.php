<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Shared\Enum\BookRoomStatus;
use Romi\Domain\BookingStatus;
use Romi\Shared\Enum\FeeType;

class CheckOutController extends BaseController
{
	protected $templatePath = '/admin/';

	public function formatMoneyVND($priceFloat)
	{
		$symbol = ' VNĐ';
		$symbol_thousand = ',';
		$decimal_place = 0;
		$price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
		return $price . $symbol;
	}

	public function view(Request $request, Response $response)
	{

		date_default_timezone_set('Asia/Bangkok');
		$serviceTypes =  $this->getLogic('ServiceType')->loadNameServiceType();
		$services =  $this->getLogic('Service')->loadNameService();

		$listCheckout =  $this->getLogic('Checkout')->loadListRoom(BookRoomStatus::CHECK_IN,BookRoomStatus::CHECK_OUT);

		if($listCheckout){
			$i = 0;
			foreach ($listCheckout as $daytime) {
				$listCheckout[$i]['dayCheckin'] = date_format($daytime['dayCheckin'], 'd/m/Y');
				$listCheckout[$i]['dayCheckout'] = date_format($daytime['dayCheckout'], 'd/m/Y');
				$listCheckout[$i]['timeArrival'] = date_format($daytime['timeArrival'], 'H:i');
				$listCheckout[$i]['deposited'] = $this->formatMoneyVND($daytime['deposited']);
				$listCheckout[$i]['valueDiscount'] = $daytime['valueDiscount'] . "%";
				$i++;
			}
		}
		else {
			$listCheckout = "";
		}

		$currentDay = date("d/m/Y");
		return $this->view->render($response, '/admin/bookroom/check-out.html', [
			'pageHeader' => 'Trả Phòng',
			'pageDescription' => "",
			'serviceTypes' => $serviceTypes,
			'services' => $services,
			'listCheckouts' => $listCheckout,
			'currentDay' =>$currentDay,

		]);
	}

	public function getDetailCheckout(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$bookingCode = $request->getParam("bookingCode");
		$status = BookRoomStatus::CHECK_IN;
		$infoCommon = $this->getLogic('Checkout')->getInfoCommon($bookingCode, $status);
		$i = 0;
		foreach ($infoCommon as $info) {
			$infoCommon[$i]['dayCheckin'] = date_format($info['dayCheckin'], 'd/m/Y');
			$infoCommon[$i]['dayCheckout'] = date_format($info['dayCheckout'], 'd/m/Y');
			$infoCommon[$i]['timeArrival'] = date_format($info['timeArrival'], 'H:i');
			$i++;
		}
		if ($infoCommon) {
			$infoFeeRoom = $this->getLogic('Checkout')->getInfoFeeRoom($bookingCode);
			$infoFeeService = $this->getLogic('Checkout')->getInfoFeeService($bookingCode);
			$arr = array(
				$infoCommon,
				$infoFeeRoom,
				$infoFeeService
			);
			return $response->withJson($arr, 201);
		}
		return $response->withJson($infoCommon, 407);
	}

	public function savePayment(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$now = date_create()->format('Y-m-d');
		$status = BookRoomStatus::CHECK_OUT;
		$idBookingRoom = $request->getParam('idBookingRoom');
		$paramPayment = array(
			'idTenant' => 1,
			'idBooking' => $request->getParam('idBooking'),
			'idBookingRoom' => $idBookingRoom,
			'amount' => $request->getParam("priceTotal"),
			'paymentAt' => $now,
			'description' => "Tiền thanh toán",
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $now,
			'updatedAt' => $now,
		);
		$payment = $this->getLogic("Payment")->createPayment($paramPayment);
		if ($payment) {
			$this->getLogic("Checkin")->changeStatusBookingRoom($idBookingRoom, $status);

			$paymentId= reset($payment);
			$now = new \Datetime('now');
			$paramPaymentRef = array(
				'idTenant' => 1,
				'idPayment' => $paymentId,
				'amount' => $request->getParam("priceTotal"),
				'year' => $now->format('Y'),
				'month' => $now->format('m'),
				'quarter' => $this->getLogic('Report')->getQuarterFromDate($now),
			);
			$this->getLogic("PaymentRef")->createPaymentRef($paramPaymentRef);

			return $response->withJson($payment, 201);
		}
		return $response->withStatus(501);
	}

	public function addFeeRoom(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);
		$now = date_create()->format('Y-m-d');
		$billId = $request->getParam("idBill");
		$paramFee = array(
			'idBookingRoom' => $request->getParam("idBookingRoom"),
			'idBill' => $billId,
			'idTenant' => 1,
			'feeName' => $request->getParam("feeName"),
			'feeAmount' => $request->getParam("feeAmount"),
			'typeFee' => FeeType::FEE_INCURRED,
			'description' => 'Phí phát sinh',
			'createdBy' => 1,
			'updatedBy' => 1,
			'createdAt' => $now,
			'updatedAt' => $now,
		);

		$fee = $this->getLogic("BillDetail")->createBillDetail($paramFee);
		if ($fee) {
			$amount = $this->getLogic("BillDetail")->sumAmountWhenChange($billId);
			$amount = $amount[0][1];
			$this->getLogic("Bill")->changeBill($amount, $billId);

			return $response->withJson($fee, 201);
		}
		return $response->withStatus(501);
	}




	public function addFeeService(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$idService = $request->getParam("idService");
		$idBookingRoom = $request->getParam("idBookingRoom");
		$priceService =  $request->getParam('priceService');
		$quantity =  $request->getParam('quantity');
		$amount = $quantity * $priceService;

		// check service already exist ?
		$check = $this->getLogic("ServiceDetail")->checkExitServiceDetail($idService, $idBookingRoom);

		if ($check) {
			// service already exist
			$idServiceDetail = $check[0]["idServiceDetail"];
			$quantityNew = $check[0]["quantity"] + $quantity;
			$amountNew =  $check[0]["amount"] + $amount;
			$paramsChange = array(
				"idServiceDetail" => $idServiceDetail,
				'quantity' => $quantityNew,
				'amount' => $amountNew,
			);
			$change = $this->getLogic("Checkout")->changeQuantityService($paramsChange);
			if ($change) {
				return $response->withJson($change, 201);
			}
			return $response->withStatus(501);
		} else {
			// create new  
			$now = date_create()->format('Y-m-d');
			$paramFee = array(
				'idBookingRoom' => $idBookingRoom,
				'idService' => $idService,
				'idTenant' => 1,
				'quantity' => $quantity,
				'amount' => $amount,
				'createdBy' => 1,
				'updatedBy' => 1,
				'createdAt' => $now,
				'updatedAt' => $now,
			);

			$new = $this->getLogic("ServiceDetail")->createServiceDetail($paramFee);
			if ($new) {
				return $response->withJson($new, 201);
			}
			return $response->withStatus(501);
		}
	}


	public function changeQuantityService(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);
		$quantity =  $request->getParam('quantity');
		$priceService =  $request->getParam('priceService');
		$amount = $quantity * $priceService;
		$params = array(
			"idServiceDetail" => $request->getParam('idServiceDetail'),
			'quantity' => $quantity,
			'amount' => $amount,
		);

		$change = $this->getLogic("Checkout")->changeQuantityService($params);
		if ($change) {
			return $response->withJson($change, 201);
		}
		return $response->withStatus(501);
	}


	public function reloadFeeRoom(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$bookingCode = $request->getParam("bookingCode");
		$infoFeeRoom = $this->getLogic('Checkout')->getInfoFeeRoom($bookingCode);

		if ($infoFeeRoom) {
			return $response->withJson($infoFeeRoom, 201);
		}
		return $response->withJson($infoFeeRoom, 407);
	}

	public function reloadFeeService(Request $request, Response $response)
	{
		if (!isset($request))
			return $response->withStatus(501);

		$bookingCode = $request->getParam("bookingCode");
		$infoFeeService = $this->getLogic('Checkout')->getInfoFeeService($bookingCode);

		if ($infoFeeService) {
			return $response->withJson($infoFeeService, 201);
		}
		return $response->withJson($infoFeeService, 407);
	}

	public function deleteFeeService(Request $request, Response $response)
	{
		$idDetailService = $request->getParam('idDetailService');

		$res = $this->getLogic('Checkout')->deleteFeeService($idDetailService);

		if ($res) {
			return $response->withJson($res, 201);
		} else {
			return $response->withStatus(417);
		}
	}

	public function deleteFeeRoom(Request $request, Response $response)
	{
		$idBillDetail = $request->getParam('idBillDetail');

		$res = $this->getLogic('Checkout')->deleteFeeRoom($idBillDetail);

		if ($res) {
			return $response->withJson($res, 201);
		} else {
			return $response->withStatus(417);
		}
	}
}
