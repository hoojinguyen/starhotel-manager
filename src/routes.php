<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Romi\Controller\User\AccountController;
use Romi\Controller\Auth\LoginController;
use Romi\Controller\User\ProfileController;
use Romi\Controller\Admin\HomeController;
use Romi\Controller\Admin\GuestController;
use Romi\Controller\Service\ParamSettingController;
use Romi\Controller\Admin\RoomManagerController;
use Romi\Controller\Admin\CustomerController;
use Romi\Controller\Admin\BookRoomController;
use Romi\Controller\Admin\ServiceManagerController;
use Romi\Controller\Admin\EmployeeController;
use Romi\Controller\Admin\CheckInController;
use Romi\Controller\Admin\DashboardController;
use Romi\Controller\Admin\ServiceController;
use Romi\Controller\Admin\PriceRoomController;
use Romi\Controller\Admin\MapHotelController;
use Romi\Controller\Admin\UserController;
use Romi\Controller\Admin\ReportController;
use Romi\Controller\Admin\UserLoginController;
use Romi\Controller\Admin\AccountingController;

use Romi\Controller\Client\HomePageController;
use Romi\Controller\Client\RoomListingsController;
use Romi\Controller\Client\ClientBookRoomController;
use Romi\Controller\Client\PaymentController;
use Romi\Controller\Client\FinishController;



use Romi\Controller\Admin\PriceConfigController;
use Romi\Controller\Admin\DeviceManageController;
use Romi\Controller\Admin\CheckOutController;
use Romi\Controller\Admin\PriceQuoteController;
use Romi\Middleware\OptionalAuth;
use Romi\Middleware\ClientAuth;
use Romi\Middleware\Acl;

use Romi\Controller\Admin\SendMailController as SendMail;


// Routes
$app->get('/', function (Request  $request, Response  $response, array  $args) {
	// Sample log message
	$this->logger->info("Data Api '/' route");

	// Render index view
	return  $this->renderer->render($response, 'index.phtml',  $args);
});

// Route login and logout
$app->get('/admin/login', LoginController::class . ':getLogin')->setName('userGet.getLogin');
$app->post('/admin/login', LoginController::class . ':postLogin')->setName('userPost.postLogin');
$app->post('/admin/logout', LoginController::class . ':postLogout')->setName('logout')->add(new OptionalAuth($container));


// Route send code email use swiftmailer
$app->get('/admin/mail', function (Request $request, Response $response) use ($container) {
	$user = new stdClass;
	$paramContact = array(
		'nameContact' => $request->getParam('nameContact'),
		'emailContact' => $request->getParam('emailContact'),
		'bookingCode' => $request->getParam('bookingCode'),
		'dayCheckin' => $request->getParam('dayCheckin'),
	);
	$user->name = $paramContact["nameContact"];
	$user->email = $paramContact["emailContact"];
	$user->bookingCode = $paramContact["bookingCode"];
	$user->dayCheckin = $paramContact["dayCheckin"];
	$container['mailer']->setTo($user->email, $user->name, $user->bookingCode, $user->dayCheckin)->sendMessage(new SendMail($user));
	$response->getBody()->write('Mail sent!');
	return $response;
});


// Route client
$app->get('/client/homepage', HomePageController::class . ':view');

$app->group('/client', function(){
	$this->get('/roomlistings', RoomListingsController::class . ':view');
	$this->get('/bookroom', ClientBookRoomController::class . ':view');
	$this->get('/payment', PaymentController::class . ':view');
	$this->post('/payment', PaymentController::class . ':postBookRoom')->setName('payment.postBookRoom');
	$this->get('/finish', FinishController::class . ':view');
})->add(new ClientAuth($container));
$app->post('/client/ajax/savebookroom', BookRoomController::class . ':saveBookRoom')->setName('bookroom')->setArgument('action', 'read')->add(new Acl($container));
$app->post('/client/ajax/checkdiscount', ClientBookRoomController::class . ':checkDiscount')->setName('bookroom')->setArgument('action', 'read')->add(new Acl($container));
// Route get
$app->group('/admin', function () {
	$this->get('/dashboard', DashboardController::class . ':view')->setName('infohotel')->setArgument('action', 'read');
	$this->get('/infohotel', HomeController::class . ':view')->setName('infohotel')->setArgument('action', 'read');
	$this->get('/service', ServiceController::class . ':view')->setName('infohotel')->setArgument('action', 'read');
	$this->get('/infopriceroom', PriceRoomController::class . ':view')->setName('infohotel')->setArgument('action', 'read');
	$this->get('/hotelmap', MapHotelController::class . ':view')->setName('infohotel')->setArgument('action', 'read');

	$this->get('/room/pricequote', PriceQuoteController::class . ':view')->setName('pricequote')->setArgument('action', 'read');

	$this->get('/room/bookroom', BookRoomController::class . ':view')->setName('bookroom')->setArgument('action', 'read');
	$this->get('/room/checkin', CheckInController::class . ':view')->setName('checkin')->setArgument('action', 'read');
	$this->get('/room/checkout', CheckOutController::class . ':view')->setName('checkout')->setArgument('action', 'read');


	$this->get('/manager/room', RoomManagerController::class . ':view')->setName('room')->setArgument('action', 'read');
	$this->get('/manage/device', DeviceManageController::class . ':view')->setName('device')->setArgument('action', 'read');
	$this->get('/manager/service', ServiceManagerController::class . ':view')->setName('service')->setArgument('action', 'read');
	$this->get('/employee', EmployeeController::class . ':view')->setName('employee')->setArgument('action', 'read');

	$this->get('/guest/list', GuestController::class . ':view')->setName('guest')->setArgument('action', 'read');
	$this->post('/guest/list', GuestController::class . ':load')->setName('guest')->setArgument('action', 'read');

	$this->get('/report', ReportController::class . ':view')->setName('report')->setArgument('action', 'read');
	$this->get('/guestovernight', ReportController::class . ':listGuestOverNight')->setName('report')->setArgument('action', 'read');

	$this->get('/system/setting', PriceConfigController::class . ':view')->setName('priceconfig')->setArgument('action', 'read');

	$this->get('/user', UserController::class . ':view')->setName('user')->setArgument('action', 'read');
	$this->post('/user', UserController::class . ':load')->setName('user')->setArgument('action', 'read');

	$this->get('/customer', CustomerController::class . ':view')->setName('customer')->setArgument('action', 'read');
	$this->post('/customer', CustomerController::class . ':load')->setName('customer')->setArgument('action', 'update');
})->add(new OptionalAuth($container))->add(new Acl($container));


// Routes post of Ajax
$app->group('/ajax', function () {
	$this->post('/guest/save', GuestController::class . ':save')->setName('guest')->setArgument('action', 'create');
	$this->post('/guest/getGuestById', GuestController::class . ':loadGuestById')->setName('guest')->setArgument('action', 'read');
	$this->post('/guest/reload', GuestController::class . ':load')->setName('guest')->setArgument('action', 'read');
	$this->post('/guest/delete', GuestController::class . ':delete')->setName('guest')->setArgument('action', 'delete');

	$this->post('/manager/room/addfloor', RoomManagerController::class . ':saveFloor')->setName('room')->setArgument('action', 'create');
	$this->post('/manager/room/loadfloor', RoomManagerController::class . ':loadFloor')->setName('room')->setArgument('action', 'read');
	$this->post('/manager/room/loadfloorbyid', RoomManagerController::class . ':loadFloorById')->setName('room')->setArgument('action', 'update');
	$this->post('/manager/room/updatefloor', RoomManagerController::class . ':updateFloor')->setName('room')->setArgument('action', 'update');
	$this->post('/manager/room/deletefloor', RoomManagerController::class . ':deleteFloor')->setName('room')->setArgument('action', 'delete');

	$this->post('/manager/room/addroomtype', RoomManagerController::class . ':saveRoomType')->setName('room')->setArgument('action', 'create');
	$this->post('/manager/room/loadroomtype', RoomManagerController::class . ':loadRoomType')->setName('room')->setArgument('action', 'read');
	$this->post('/manager/room/loadroomtypebyid', RoomManagerController::class . ':loadRoomTypeById')->setName('room')->setArgument('action', 'update');
	$this->post('/manager/room/updateroomtype', RoomManagerController::class . ':updateRoomType')->setName('room')->setArgument('action', 'update');
	$this->post('/manager/room/deleteroomtype', RoomManagerController::class . ':deleteRoomType')->setName('room')->setArgument('action', 'delete');

	$this->post('/manager/room/addroom', RoomManagerController::class . ':saveRoom')->setName('room')->setArgument('action', 'create');
	$this->post('/manager/room/loadroom', RoomManagerController::class . ':loadRoom')->setName('room')->setArgument('action', 'read');
	$this->post('/manager/room/loadroombyid', RoomManagerController::class . ':loadRoomById')->setName('room')->setArgument('action', 'update');
	$this->post('/manager/room/updateroom', RoomManagerController::class . ':updateRoom')->setName('room')->setArgument('action', 'update');
	$this->post('/manager/room/deleteroom', RoomManagerController::class . ':deleteRoom')->setName('room')->setArgument('action', 'create');

	$this->post('/manager/service/addservicetype', ServiceManagerController::class . ':saveServiceType')->setName('service')->setArgument('action', 'create');
	$this->post('/manager/service/loadservicetype', ServiceManagerController::class . ':loadServiceType')->setName('service')->setArgument('action', 'read');
	$this->post('/manager/service/loadservicetypebyid', ServiceManagerController::class . ':loadServiceTypeById')->setName('service')->setArgument('action', 'update');
	$this->post('/manager/service/updateservicetype', ServiceManagerController::class . ':updateServiceType')->setName('service')->setArgument('action', 'update');
	$this->post('/manager/service/deleteservicetype', ServiceManagerController::class . ':deleteServiceType')->setName('service')->setArgument('action', 'delete');

	$this->post('/manager/service/addservice', ServiceManagerController::class . ':saveService')->setName('service')->setArgument('action', 'create');
	$this->post('/manager/service/loadservice', ServiceManagerController::class . ':loadService')->setName('service')->setArgument('action', 'read');
	$this->post('/manager/service/loadservicebyid', ServiceManagerController::class . ':loadServiceById')->setName('service')->setArgument('action', 'update');
	$this->post('/manager/service/updateservice', ServiceManagerController::class . ':updateService')->setName('service')->setArgument('action', 'update');
	$this->post('/manager/service/deleteservice', ServiceManagerController::class . ':deleteService')->setName('service')->setArgument('action', 'delete');

	$this->post('/manager/device/adddevice', DeviceManageController::class . ':saveDevice')->setName('device')->setArgument('action', 'create');
	$this->post('/manager/device/loadbyid', DeviceManageController::class . ':loadDeviceById')->setName('device')->setArgument('action', 'read');
	$this->post('/manage/device/reload', DeviceManageController::class . ':loadDevice')->setName('device')->setArgument('action', 'read');
	$this->post('/manager/device/update', DeviceManageController::class . ':updateDevice')->setName('device')->setArgument('action', 'update');
	$this->post('/manage/device/delete', DeviceManageController::class . ':deleteDevice')->setName('device')->setArgument('action', 'delete');

	$this->post('/manage/deviceInRoom/save', DeviceManageController::class . ':saveDeviceInRoom')->setName('device')->setArgument('action', 'create');
	$this->post('/manage/deviceInRoom/reload', DeviceManageController::class . ':loadDeviceInRoom')->setName('device')->setArgument('action', 'read');
	$this->post('/manage/deviceInRoom/update', DeviceManageController::class . ':updateDeviceInRoom')->setName('device')->setArgument('action', 'update');
	$this->post('/manage/deviceInRoom/delete', DeviceManageController::class . ':deleteDeviceInRoom')->setName('device')->setArgument('action', 'delete');

	$this->post('/employee/saveemployee', EmployeeController::class . ':saveEmployee')->setName('employee')->setArgument('action', 'create');
	$this->post('/employee/loademployee', EmployeeController::class . ':loadEmployee')->setName('employee')->setArgument('action', 'read');
	$this->post('/employee/loademployeebyid', EmployeeController::class . ':loadEmployeeById')->setName('employee')->setArgument('action', 'update');
	$this->post('/employee/deleteemployee', EmployeeController::class . ':deleteEmployee')->setName('employee')->setArgument('action', 'delete');

	$this->post('/user/add', UserController::class . ':save')->setName('user')->setArgument('action', 'create');
	$this->post('/user/getUserById', UserController::class . ':loadUserById')->setName('user')->setArgument('action', 'update');
	$this->post('/user/delete', UserController::class . ':delete')->setName('user')->setArgument('action', 'delete');

	$this->post('/customer/add', CustomerController::class . ':save')->setName('customer')->setArgument('action', 'create');
	$this->post('/customer/getCustomerById', CustomerController::class . ':LoadCustomerById')->setName('customer')->setArgument('action', 'read');
	$this->post('/customer/reload', CustomerController::class . ':load')->setName('customer')->setArgument('action', 'read');
	$this->post('/customer/update', CustomerController::class . ':update')->setName('customer')->setArgument('action', 'update');
	$this->post('/customer/delete', CustomerController::class . ':delete')->setName('customer')->setArgument('action', 'delete');


	$this->post('/room/findroomempty', BookRoomController::class . ':findRoomEmpty')->setName('bookroom')->setArgument('action', 'read');
	$this->post('/room/savebookroom', BookRoomController::class . ':saveBookRoom')->setName('bookroom')->setArgument('action', 'read');
	$this->post('/quotation/day', AccountingController::class . ':DayQuotation')->setName('bookroom')->setArgument('action', 'read');

	$this->post('/room/pricequote', PriceQuoteController::class . ':quotation')->setName('pricequote')->setArgument('action', 'read');

	$this->post('/room/getinfobookroom', CheckInController::class . ':getInfoBookRoom')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/confirmcheckin', CheckInController::class . ':confirmCheckIn')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/cancelbookroom', CheckInController::class . ':cancelBookRoom')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/changeroom', CheckInController::class . ':changeRoom')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/addguestandguestinroom', CheckInController::class . ':addGuestAndGuestInRoom')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/addguestinroom', CheckInController::class . ':addGuestInRoom')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/getguestbyterm', CheckInController::class . ':getGuestByTerm')->setName('checkin')->setArgument('action', 'read');
	$this->post('/room/getinfoguestinroom', CheckInController::class . ':getInfoGuestInRoom')->setName('checkin')->setArgument('action', 'read');

	$this->post('/room/getdetailcheckout', CheckOutController::class . ':getDetailCheckout')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/savepayment', CheckOutController::class . ':savePayment')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/changequantityservice', CheckOutController::class . ':changeQuantityService')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/addfeeroom', CheckOutController::class . ':addFeeRoom')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/addfeeservice', CheckOutController::class . ':addFeeService')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/reloadfeeroom', CheckOutController::class . ':reloadFeeRoom')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/reloadfeeservice', CheckOutController::class . ':reloadfeeService')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/deletefeeroom', CheckOutController::class . ':deleteFeeRoom')->setName('checkout')->setArgument('action', 'read');
	$this->post('/room/deletefeeservice', CheckOutController::class . ':deleteFeeService')->setName('checkout')->setArgument('action', 'read');


	$this->post('/report', ReportController::class . ':report')->setName('report')->setArgument('action', 'read');
	$this->post('/room/loadinfoguestovernight', ReportController::class . ':loadInfoGuestOverNight')->setName('report')->setArgument('action', 'read');
	$this->post('/getPrice', PriceConfigController::class . ':getPrice')->setName('priceconfig')->setArgument('action', 'read');
	$this->post('/update', PriceConfigController::class . ':save')->setName('priceconfig')->setArgument('action', 'update');
})->add(new OptionalAuth($container))->add(new Acl($container));


$app->post('/test', BookRoomController::class . ':checkDiscount1')->setName('userGet.checkDiscount');


$app->get('post', function ($request, $response, $args) use ($cache) {
	$page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
	$limit = 10;
	$skip = ($page - 1) * $limit;
});



// send sms with textlocal api
// $app->get('/admin/sendsms', function (Request $request, Response $response) {

// 	$apiKey = urlencode('f1ORMB+n8PI-2pRxW8kt19YB9enMyg6KHwwKi0GsR9');
	
// 	// Message details
// 	$numbers = urlencode('84389457027');
// 	$sender = urlencode('Van Hoi');
// 	$message = rawurlencode('Welcome to My homies');
 
// 	// Prepare data for POST request
// 	$data = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
 
// 	// Send the GET request with cURL
// 	$ch = curl_init('https://api.txtlocal.com/send/?' . $data);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	$response = curl_exec($ch);
// 	curl_close($ch);
	
// 	// Process your response here
// 	echo $response;
// });
