<?php
namespace Romi\Controller\Client;

use Romi\Controller\BaseController;
use Romi\Controller\Client\ClientBookRoomController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class PaymentController extends BaseController   {

	public function view(Request $request, Response $response) {
		if (!isset($request))
		return $response->withStatus(501);

		$roomDetail = $this->detailRoom($request);
		return $this->view->render($response, '/client/payment.html', [
			'roomDetail' => $roomDetail,
		]);
	}

	public function detailRoom(Request $request){
		date_default_timezone_set('Asia/Bangkok');
		$roomType  = $request->getQueryParam('roomType');
		$roomId = $request->getQueryParam('roomId');
		$valueDiscount = $request->getQueryParam('valueDiscount');
		
		$checkinDay = new \Datetime(date_format(date_create($request->getQueryParam('dayCheckin')), 'Y-m-d'));
		$checkoutDay = new \Datetime(date_format(date_create($request->getQueryParam('dayCheckout')), 'Y-m-d'));
		$quotation = $this->getLogic('Accounting')->accountByDay($roomId, $checkinDay, $checkoutDay);

		$checkin = date_format($checkinDay, 'd/m/Y');
		$checkout = date_format($checkoutDay, 'd/m/Y');

		$priceTotal = 0;
		foreach ($quotation as $q) {
			$priceTotal += $q[amount];
		}

		if($valueDiscount != 0 ){
			$priceDiscount = ($priceTotal*	$valueDiscount)/100;
			$priceTotal = $priceTotal - $priceDiscount;
		}

		$roomDetail = array(
			'roomType' => $roomType,
			'dayCheckin' => $checkin,
			'dayCheckout' => $checkout,
			'numDay' => count($quotation),
			'priceTotal' => $priceTotal,
			'priceTotalVND' => $this->formatMoneyVND($priceTotal),
			'priceDiscount' => $priceDiscount,
			'priceDiscountVND' => $this->formatMoneyVND($priceDiscount),
			'valueDiscount' => $valueDiscount,
		);
		return $roomDetail;

	}
}
