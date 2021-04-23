<?php

namespace Romi\Controller\Client;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;

class FinishController extends BaseController {

	public function view(Request $request, Response $response) {
		if (!isset($request))
		return $response->withStatus(501);

		$roomDetail = $this->detailRoom($request);

		return $this->view->render($response, '/client/finish.html', [
			'roomDetail' => $roomDetail,
		]);
	}

	public function detailRoom(Request $request){
		date_default_timezone_set('Asia/Bangkok');
	
		$emailContact = $request->getQueryParam('emailContact');
		$bookingCode = $request->getQueryParam('bookingCode');
		$timeExpectCheckin = $request->getQueryParam('timeArrival');

		$dayCheckin = new \Datetime(date_format(date_create($request->getQueryParam('dayCheckin')), 'Y-m-d'));
		$dayCheckin = date_format($dayCheckin, 'd/m/Y');


	
		$roomDetail = array(
			'emailContact' => $emailContact,
			'bookingCode' => $bookingCode,
			'timeArrival' => $timeExpectCheckin,
			'dayCheckin' => $dayCheckin,
		);
		return $roomDetail;

	}
}
