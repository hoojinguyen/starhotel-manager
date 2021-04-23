<?php

namespace Romi\BusinessLogic;

use Romi\Domain\Config;
use Romi\Domain\RoomType;


define('COUPLE_ROOM', 2);


class PriceConfigLogic extends BaseLogic
{ 
	public function getRoomPrice($roomType){
		$price = null;
		switch($roomType){
			case SINGLE_ROOM:{
				$price = $this->getEntityManager()->getRepository(Config::class)->getPriceSingleRoom();
				$roomType = "Phòng Đơn";
				break;
			}
			case DOUBLE_ROOM:{
				$price = $this->getEntityManager()->getRepository(Config::class)->getPriceDoubleRoom();
				$roomType = "Phòng Đôi";
				break;
			}
			default:{
				break;
			}
			
		}
		$roomTypes = $this->getLogic('RoomType')->loadRoomType();
		$typeList = array();
		foreach($roomType as $roomTypes){
			array_push($typeList, $roomType->getName());
		}

		return [
			"roomType" => $typeList,
			"firstHourPrice" => [
				'value' => $this->formatMoneyVND($price[0]['value']),
				'id' => $price[0]['id']
			],
			"nextHourPrice" => [
				'value' => $this->formatMoneyVND($price[1]['value']),
				'id' => $price[1]['id']
			],
			"firstHourAfter23h" => [
				'value' => $this->formatMoneyVND($price[2]['value']),
				'id' => $price[2]['id']
			],
			"nextHourAfter23h" => [
				'value' => $this->formatMoneyVND($price[3]['value']),
				'id' => $price[3]['id']
			],
			"dayPrice" => [
				'value' => $this->formatMoneyVND($price[4]['value']),
				'id' => $price[4]['id']
			],
			"nightPrice" => [
				'value' => $this->formatMoneyVND($price[5]['value']),
				'id' => $price[5]['id']
			],
			"weekendDayPrice" => [
				'value' => $this->formatMoneyVND($price[6]['value']),
				'id' => $price[6]['id']
			],
			"weekendNightPrice" => [
				'value' => $this->formatMoneyVND($price[7]['value']),
				'id' => $price[7]['id']
			],
			"OTHourPrice" => [
				'value' => $this->formatMoneyVND($price[8]['value']),
				'id' => $price[8]['id']
			],
			"earlyHourPrice" => [
				'value' => $this->formatMoneyVND($price[9]['value']),
				'id' => $price[9]['id']
			],
			"surcharge" => [
				'value' => $this->formatMoneyVND($price[10]['value']),
				'id' => $price[10]['id']
			],
		];
	}
	function formatMoneyVND($priceFloat)
	{
		$symbol = ' VNĐ';
		$symbol_thousand = ',';
		$decimal_place = 0;
		$price = number_format($priceFloat, $decimal_place, '', $symbol_thousand);
		return $price . $symbol;
	}
	public function getPrice(){
		$dql = "SELECT config.key, config.value FROM Romi\Domain\Config config WHERE config.key IN ('firstHourPrice1', 'nextHourPrice1', 'firstHourAfter23h1', 'nextHourAfter23h1', 'dayPrice1','nightPrice1','weekendDayPrice1','weekendNightPrice1','OTHourPrice1','earlyHourPrice1','surcharge')";
		return $this->getEntityManager()->createQuery($dql)->getResult();
	}
	public function save($id, $value) {
		$config = $this->getEntityManager()->getRepository(Config::class)->findOneBy(array('id'=> $id));
		$config->setValue((int)$value);
		$this->saveOrUpdate($config);
		return $this->formatMoneyVND($value);
	}
}
