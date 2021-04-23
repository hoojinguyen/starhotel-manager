<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;


class AccountingController extends BaseController
{
	public function DayQuotation(Request $request, Response $response, array $args)
	{
		if (!isset($request)) {
			return $response->withStatus(501);
		}
		$roomId = $request->getParam("roomId");
		$dayCheckin = new \Datetime($request->getParam("dayCheckin"));
		$dayCheckout = new \Datetime($request->getParam("dayCheckout"));

		$quotation = $this->getLogic('Accounting')->accountByDay($roomId, $dayCheckin, $dayCheckout);

		return $response->withJson($quotation, 201);
	}
}
