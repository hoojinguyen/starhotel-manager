<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Shared\Enum\RentType;
use Romi\Shared\Enum\BookRoomStatus;

class ReportController extends BaseController
{
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response)
	{
		return $this->view->render($response, '/admin/Report.html', [
			'pageHeader' => 'Báo cáo',
		]);
	}

	public function listGuestOverNight(Request $request, Response $response) {
		$timeDay = date_create()->format('Y-m-d');
        $guestOverNights =  $this->getLogic('Report')->loadInfoGuestOverNight($timeDay);

		$i=0;
		foreach( $guestOverNights as $daytime ){
			$guestOverNights[$i]['dayCheckin'] = date_format($daytime['dayCheckin'],'d/m/Y H:i:s');
			$guestOverNights[$i]['dayCheckout'] = date_format($daytime['dayCheckout'],'d/m/Y H:i:s');
			$i++;
		}

		$dateNow= (new \DateTime())->format('d-m-Y');
		
		return $this->view->render($response, '/admin/list-guest-overnight.html', [
			'pageHeader' => 'Danh Sách Khách Lưu Trú Qua Đêm',
			'guestOverNights' => $guestOverNights,
			'pageDescription' => 'Ngày ' .  $dateNow,
			
		]);
	}

	public function loadInfoGuestOverNight(Request $request, Response $response) {

		$now = $request->getParam("searchTime");

		if($now == ""){
			$timeDay = date_create()->format('Y-m-d');
		}
		else {
			$timeDay = date_format(date_create($now ), 'Y-m-d');
		}

		$guestOverNights =  $this->getLogic('Report')->loadInfoGuestOverNight($timeDay);
		
		if($guestOverNights){
			$i=0;
			foreach( $guestOverNights as $daytime ){
				$guestOverNights[$i]['dayCheckin'] = date_format($daytime['dayCheckin'],'d/m/Y H:i:s');
				$guestOverNights[$i]['dayCheckout'] = date_format($daytime['dayCheckout'],'d/m/Y H:i:s');
				$i++;
			}
			return $response->withJson($guestOverNights, 201);
		}

		return $response->withJson($guestOverNights, 507);		
	
	}

	


	public function report(Request $request, Response $response)
	{
		if (!isset($request)) {
			return $response->withStatus(501);
		}
		$startDate = $request->getParam('startDate');
		$endDate = $request->getParam('endDate');

		$startTime = new \Datetime($startDate);
		$endTime = new \Datetime($endDate);
		if($startTime>=$endTime) {
			return $response->withJson(false,201);
		}

		$reportData = null;

		switch($request->getParam('reportType')){
			case 'year': {
				$reportData = $this->getLogic('Report')->reportByYear($startTime, $endTime);

				$reportType = array(
					'reportType' => 'year'
				);
				array_unshift($reportData,$reportType);

				break;
			}
			case 'month': {
				$reportData = $this->getLogic('Report')->reportByMonth($startTime, $endTime);
				$reportType = array(
					'reportType' => 'month'
				);
				array_unshift($reportData,$reportType);
				break;
			}
			case 'quarter': {
				$reportData = $this->getLogic('Report')->reportByQuarter($startTime, $endTime);

				$reportType = array(
					'reportType' => 'quarter'
				);
				array_unshift($reportData,$reportType);
				break;

			}

			default: {
				break;
			}
		}

		return $response->withJson($reportData, 201);

	}
}
