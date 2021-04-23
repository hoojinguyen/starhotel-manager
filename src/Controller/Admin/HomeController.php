<?php

namespace Romi\Controller\Admin;

use Romi\Controller\BaseController;
use Romi\Transformer\AuthorizedTenantTransformer;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as validator;
use League\Fractal\Resource\Item;
use Faker\Provider\DateTime;
use Romi\Shared\Enum\BookRoomStatus;
use Romi\Shared\Enum\RentType;

class HomeController extends BaseController {
	protected $templatePath = '/admin/';
	public function view(Request $request, Response $response) {

	
		// $numEmptyRoom = $this->getLogic('Home')->loadNumEmptyRoom($now)[0][numEmptyRoom];
		$numGuestOrder = $this->getLogic('Home')->loadNumGuestBooking(BookRoomStatus::CHECK_IN)[0][numGuestOrder];
		$numGuestOverNight =  $this->getLogic('Home')->loadNumGuestBy(BookRoomStatus::CHECK_IN)[0][peopleNumber];
		$numGuestDay =  $this->getLogic('Home')->loadNumGuestBy(BookRoomStatus::CHECK_IN)[0][peopleNumber];

		date_default_timezone_set('Asia/Bangkok');
		$currentDay = date_create()->format('Y-m-d');
		$infoGuestRooms =  $this->getLogic('Checkout')->loadInfoDayBookingNewest(BookRoomStatus::BOOK_ROOM, $currentDay);

		if ($infoGuestRooms) {
			$i = 0;
			foreach ($infoGuestRooms as  &$daytime) {
				$infoGuestRooms[$i]['dayCheckin'] = date_format($daytime['dayCheckin'], 'd/m/Y');
				$infoGuestRooms[$i]['dayCheckout'] = date_format($daytime['dayCheckout'], 'd/m/Y');
				$infoGuestRooms[$i]['dayBooking'] = date_format($daytime['dayBooking'], 'd/m/Y H:i');
				$i++;
			}
		} else {
			$infoGuestRooms = 0;
		}

		$dateNow= (new \DateTime())->format('d/m/Y');

		return $this->view->render($response, '/admin/home.html', [
			'pageHeader' => 'Tổng Hợp Thông Tin',
			'pageDescription' => 'Ngày ' .  $dateNow,
				// 'numEmptyRoom'=> $numEmptyRoom ,
			'numGuestOrder' => $numGuestOrder,
			'numGuestOverNight' => $numGuestOverNight,
			'numGuestDay' => $numGuestDay,
			'infoGuestRooms' => $infoGuestRooms,
			'currentDay' => date_create()->format('d/m/Y')
	
		]);
	}


}
