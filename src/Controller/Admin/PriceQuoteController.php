<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Domain\Config;

define('SINGLE_ROOM', 1);
define('DOUBLE_ROOM', 2);

class PriceQuoteController extends BaseController
{
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response)
	{
		

		return $this->view->render($response, '/admin/BookRoom/price-quote.html', [
			'pageHeader' => 'Báo Giá',
			'pageDescription' => '',
		]);
    }
    

    public function quotation(Request $request, Response $response, array $args) {
		// tùy vào kiểu thuê sẽ chọn hàm tính tiền thích hợp
		// request luôn có rentType và tuy vào rentType sẽ có các tham số khác nhau
		//   + hourType: loại phòng (hoặc số phòng), số giờ thuê, có sau 11h không ?
		//   + allInType: có phải cuối tuần không, đêm hay ngày, phòng thuê hoặc loại phòng
		if (!isset($request))
			return $response->withStatus(501);
		$quotation = null;
		if ($request->getParam('rentType') == '1') {

			$params = array (
				'people' => $request->getParam('people'),
				'hours' => $request->getParam('hours'),				
				'roomType' => $request->getParam('roomType'),
				'isWeekend' =>$request->getParam('isWeekend'),
				'isAfter23h' =>$request->getParam('isAfter23h'),
				//'priceList' => $request->getParam('priceList')
			);
			
            $quotation = $this->getLogic('PriceQuote')->accountByHour($params);
            $quotationJson = json_encode($quotation);
		} else {
				$params = array (
					'isWeekend' =>$request->getParam('isWeekend'),
					//'priceList' => $request->getParam('priceList'),
					'rentType' => $request->getParam('rentType'),
					'people' => $request->getParam('people'),
					'earlyHour' => $request->getParam('earlyHour'),
					'OTHour' => $request->getParam('OTHour'),
					'roomType' => $request->getParam('roomType')
				);
                $quotation = $this->getLogic('PriceQuote')->accountAllIn($params);
                $quotationJson = json_encode($quotation);
			}
		return $response->withJson($quotationJson, 201);
	}
	public function LogArray($array){
		foreach($array as $key=>$value){
			$this->logger->info($key.'=>'.$value);
		}
	}



}
