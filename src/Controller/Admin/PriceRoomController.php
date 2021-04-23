<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Romi\Domain\Config;

class PriceRoomController extends BaseController {
    protected $templatePath = '/admin/';
    function formatMoneyVND($priceFloat) {
        $symbol = ' VNĐ';
        $symbol_thousand = ',';
        $decimal_place = 0;
        $price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
        return $price.$symbol;
        }
	public function view(Request $request, Response $response) {
    
        // $infoServices =  $this->getLogic('Service')->loadInfoService("Còn");
        $single=$this->getEntityManager()->getRepository(Config::class)->getPriceSingleRoom();
        $paramsSingles =[
            "firstHourPrice1" =>$this->formatMoneyVND($single[0]['value']) ,
            "nextHourPrice1" => $this->formatMoneyVND($single[1]['value']),
            "firstHourAfter23h1" => $this->formatMoneyVND($single[2]['value']),
            "nextHourAfter23h1" => $this->formatMoneyVND($single[3]['value']),
            "dayPrice1" => $this->formatMoneyVND($single[4]['value']),
            "nightPrice1" => $this->formatMoneyVND($single[5]['value']),
            "weekendDayPrice1" => $this->formatMoneyVND($single[6]['value']),
            "weekendNightPrice1" => $this->formatMoneyVND($single[7]['value']),
            "OTHourPrice1" => $this->formatMoneyVND($single[8]['value']),
            "earlyHourPrice1" => $this->formatMoneyVND($single[9]['value']),
            "surcharge" => $this->formatMoneyVND($single[10]['value'])
        ];

        $double=$this->getEntityManager()->getRepository(Config::class)->getPriceDoubleRoom();
        $paramDoubles =[
            "firstHourPrice2" =>$this->formatMoneyVND($double[0]['value']) ,
            "nextHourPrice2" => $this->formatMoneyVND($double[1]['value']),
            "firstHourAfter23h2" => $this->formatMoneyVND($double[2]['value']),
            "nextHourAfter23h2" => $this->formatMoneyVND($double[3]['value']),
            "dayPrice2" => $this->formatMoneyVND($double[4]['value']),
            "nightPrice2" => $this->formatMoneyVND($double[5]['value']),
            "weekendDayPrice2" => $this->formatMoneyVND($double[6]['value']),
            "weekendNightPrice2" => $this->formatMoneyVND($double[7]['value']),
            "OTHourPrice2" => $this->formatMoneyVND($double[8]['value']),
            "earlyHourPrice2" => $this->formatMoneyVND($double[9]['value']),
            "surcharge" => $this->formatMoneyVND($double[10]['value'])
        ];


		return $this->view->render($response, '/admin/price-room.html', [
			'pageHeader' => 'Thông Tin Giá Phòng',
            'pageDescription' => 'Áp dụng từ ngày 15/12/2018',
            'paramsSingle' => $paramsSingles ,
            'paramDouble' => $paramDoubles ,
            'usertypes' => $_SESSION['usertype'],
		]);
	}
}
