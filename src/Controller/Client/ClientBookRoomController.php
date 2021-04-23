<?php

namespace Romi\Controller\Client;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class ClientBookRoomController extends BaseController
{
	public function view(Request $request, Response $response)
	{

		if (!isset($request))
		return $response->withStatus(501);

		$roomDetail = $this->detailRoom($request);

		return $this->view->render($response, '/client/book-room.html', [
			'roomDetail' => $roomDetail,
		]);
	}


	public function detailRoom(Request $request){
		date_default_timezone_set('Asia/Bangkok');
		$roomType  = $request->getQueryParam('roomType');
		$roomId = $request->getQueryParam('roomId');
		$adult = $request->getQueryParam('adult');
		$child = $request->getQueryParam('child');


		$dayCheckin = new \Datetime(date_format(date_create($request->getQueryParam('dayCheckin')), 'Y-m-d'));
		$dayCheckout = new \Datetime(date_format(date_create($request->getQueryParam('dayCheckout')), 'Y-m-d'));

		$quotation = $this->getLogic('Accounting')->accountByDay($roomId, $dayCheckin, $dayCheckout);
		$bookingCode = $this->getLogic("BookRoom")->createBookingCode();

		$checkin = date_format($dayCheckin, 'd/m/Y');
		$checkout = date_format($dayCheckout, 'd/m/Y');

		$priceTotal = 0;
		foreach ($quotation as $q) {
			$priceTotal += $q[amount];
		}

		$roomDetail = array(
			'bookingCode' => $bookingCode,
			'idRoom' => $roomId,
			'adult' => $adult,
			'child' => $child,
			'roomType' => $roomType,
			'dayCheckinDisplay' => $checkin,
			'dayCheckoutDisplay' => $checkout,
			'numDay' => count($quotation),
			'priceTotal' => $priceTotal,
			'priceTotalVND' => $this->formatMoneyVND($priceTotal),
			'dayCheckin' => $request->getQueryParam('dayCheckin'),
			'dayCheckout' => $request->getQueryParam('dayCheckout')
		);
		return $roomDetail;

	}

	public function checkDiscount(Request $request, Response $response)
	{
		$codeDiscount = $request->getParam('discount');
		$priceTotal = $request->getParam('priceTotal');
		$discount = $this->getLogic("Discount")->checkDiscount($codeDiscount);
		if (!$discount) {
			return $response->withJson($discount, 409);
		}
		
		$valueDiscount = $discount[0]['value'];
		$priceDiscount = ($priceTotal*	$valueDiscount)/100;
		$priceTotalThen = $priceTotal - $priceDiscount;
		$discountArr = array(
			'value' => $valueDiscount,
			'priceDiscount' => $priceDiscount,
			'priceDiscountVND' => $this->formatMoneyVND($priceDiscount),
			'priceTotal' => $priceTotalThen,
			'priceTotalVND' => $this->formatMoneyVND($priceTotalThen)
		);
		return $response->withJson($discountArr, 201);
	}


}
