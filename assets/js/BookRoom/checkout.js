$(document).ready(function () {

	$('.btnCheckoutLive').on('click', function () {
		var bookingCode = $(this).data("booking-code");
		$("#bookingCode").val(bookingCode);
		$("#btnSearchCodeBooking").trigger("click");
		$("#listRoomCheckout").addClass("hidden");
	});



	$("#btnSearchCodeBooking").on('click', function () {
		getDetailCheckout();
		$("#listRoomCheckout").addClass("hidden");
	});

	$("#btnPayment").on('click', function () {
		savePayment();
	});

	$("#btnSaveAddFeeRoom").on('click', function () {
		addFeeRoom();

	});


	$("#btnSaveAddFeeService").on('click', function () {
		addFeeService();
	});

	$('#printBill').on('click', function () {
		printBill();
	});

	getValueFromDropDown();

	deleteFeeRoom();

	deleteFeeService();

})

function getValueFromDropDown() {
	$("#clickDrop").on("click", "#serviceName", function () {
		var name = $(this).data("service-name");
		var id = $(this).data("service-id");
		var price = $(this).data("service-price");
		var unit = $(this).data("service-unit");

		$("#idService").val(id);
		$("#nameService").val(name);
		$("#priceService").val(price);
		$("#unit").val(unit);
		$("#priceServiceFormat").val(formatMoney(price));
	})
}

function formatMoney(amount, decimalCount = 2, thousands = ",") {
	try {
		decimalCount = Math.abs(decimalCount);
		decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

		const negativeSign = amount < 0 ? "-" : "";

		let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
		let j = (i.length > 3) ? i.length % 3 : 0;

		return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + " VNĐ";
	} catch (e) {
		console.log(e)
	}
}


function getDetailCheckout() {
	var bookingCode = $("#bookingCode").val();
	$("#refreshButtonSearch").removeClass("hide");
	$.ajax({
		type: 'POST',
		url: '/ajax/room/getdetailcheckout',
		dataType: 'json',
		data: {
			'bookingCode': bookingCode
		},
		success: function (data, textStatus, xhr) {
			$("#formDetailCheckout").removeClass("hide");
			$("#refreshButtonSearch").addClass("hide");

			//info common
			$("#idBooking").val(data[0][0].idBooking);
			$("#idBookingRoom").val(data[0][0].idBookingRoom);
			$("#idBill").val(data[1][0].idBill);
			$("#nameRoom").text(data[0][0].nameRoom);
			$("#roomType").text(data[0][0].roomType);
			$("#nameContact").text(data[0][0].nameContact);
			$("#numGuest").text(data[0][0].numGuest);

			$("#dayCheckin").text(data[0][0].dayCheckin);
			$("#dayCheckout").text(data[0][0].dayCheckout);
			$("#timeArrival").text(data[0][0].timeArrival);
			//info fee room 

			$("#feeRoomDetail tr:has(td)").remove();
			var totalFeeRoom = 0;
			var data_fee_room = "";
			$.each(data[1], function (key, value) {
				var priceFeeRoom = formatMoney(value.priceFeeRoom);
				totalFeeRoom += value.priceFeeRoom;

				data_fee_room += '<tr>';
				data_fee_room += '<td >' + ++key + '</td>';
				data_fee_room += '<td >' + value.nameFeeRoom + '</td>';
				data_fee_room += '<td >' + priceFeeRoom + '</td>';
				if (value.typeFee == 1) {
					data_fee_room += '<td> <button type="button" class="btn btn-xs btn-hover btn-danger disabled" id="btnDelFeeRoom"  data-bill-detail-id="' + value.idBillDetail + '"> <i class="fa fa-trash-o"> </i> </button> </td>'

				}
				else {
					data_fee_room += '<td> <button type="button" class="btn btn-xs btn-hover btn-danger" id="btnDelFeeRoom"  data-bill-detail-id="' + value.idBillDetail + '"> <i class="fa fa-trash-o"> </i> </button> </td>'
				}
				data_fee_room += '</tr>';
			});

			data_fee_room += '<td colspan="2" class="text-bold">' + ' Tổng tiền phòng:' + '</td> ';
			data_fee_room += '<td colspan="2" class="text-bold">' + formatMoney(totalFeeRoom) + '</td>';
			$('#feeRoomDetail').append(data_fee_room);

			//info fee service
			if (data[2] === null) {
				var data_fee_service = "";
				$("#feeServiceDetail tr:has(td)").remove();
				data_fee_service += '<tr>';
				data_fee_service += '<td colspan="7" class="text-center">' + '<p> Chưa có dịch vụ nào được đặt ... </p>' + '</td>';
				data_fee_service += '</tr>';
				$('#feeServiceDetail').append(data_fee_service);
				var totalFeeService = 0;
			}
			else {
				$("#feeServiceDetail tr:has(td)").remove();
				var totalFeeService = 0;
				var data_fee_service = "";
				$.each(data[2], function (key, value) {
					var priceService = formatMoney(value.priceService);
					var amount = formatMoney(value.amount);
					totalFeeService += value.amount;

					data_fee_service += '<tr>';
					data_fee_service += '<td >' + ++key + '</td>';
					data_fee_service += '<td >' + value.nameService + '</td>';
					data_fee_service += '<td >' + value.unit + '</td>';
					data_fee_service += '<td >' + priceService + '</td>';
					data_fee_service += '<td >' + value.quantity + '</td>';
					data_fee_service += '<td >' + amount + '</td>';
					data_fee_service += '<td> <button type="button" class="btn btn-xs btn-hover btn-danger" id="btnDelFeeService" data-service-id="' + value.idService + '" data-service-detail-id="' + value.idServiceDetail + '"data-service-quantity="' + value.quantity + '"data-price-service="' + value.priceService + '"><i class="fa fa-trash-o"></i></button> </td>'
					data_fee_service += '</tr>';
				});



				data_fee_service += '<td colspan="5" class="text-bold">' + ' Tổng tiền dịch vụ:' + '</td> ';
				data_fee_service += '<td colspan="2" class="text-bold">' + formatMoney(totalFeeService) + '</td>';
				$('#feeServiceDetail').append(data_fee_service);
			}

			var totalPayment = totalFeeRoom + totalFeeService;
			var valueDiscount = data[1][0].valueDiscount;
			$("#valueDiscount").val(valueDiscount);
			$("#totalFeeService").val(totalFeeService);
			$("#totalFeeRoom").val(totalFeeRoom);
			$("#deposited").val(data[1][0].deposited);
			// var discountPrice = (discount*totalFeeRoom)/100;
			var discountPrice = data[1][0].priceDiscount;
			$("#priceDiscount").val(discountPrice);
			var depositedPrice = data[1][0].deposited;
			var totalNeedPayment = totalPayment - discountPrice - depositedPrice;
			$("#totalPayment").text(formatMoney(totalPayment));
			$("#lblDiscount").text(valueDiscount);
			$("#discountPrice").text(formatMoney(discountPrice));
			$("#depositedPrice").text(formatMoney(depositedPrice));
			$("#totalNeedPayment").text(formatMoney(totalNeedPayment));

			$("#priceTotal").val(totalNeedPayment);


		},
		error: function (xhr, textStatus, errorThrown) {
			console.log(errorThrown);
			$("#refreshButtonSearch").addClass("hide");
			$("#formDetailCheckout").addClass("hide");
			$("#notFoundBookingRoom").removeClass("hide");
			setTimeout(function () { $("#notFoundBookingRoom").addClass("hide"); }, 5000);
		}
	})

}

function savePayment() {

	var idBooking = $("#idBooking").val();
	var idBookingRoom = $("#idBookingRoom").val();
	var priceTotal = $("#priceTotal").val();
	var data = "";
	data += "idBooking=" + idBooking + "&idBookingRoom=" + idBookingRoom + "&priceTotal=" + priceTotal;

	$.ajax({
		type: 'POST',
		url: '/ajax/room/savepayment',
		dataType: 'json',
		data,
		success: function (data, textStatus, xhr) {


			$("#inputCodeBooking").val("");
			$("#formDetailCheckout").addClass("hide");
			$("#checkoutSuccess").removeClass("hide");
			setTimeout(function () { $("#checkoutSuccess").addClass("hide"); }, 1000);
			setTimeout(function () { location.reload(true); }, 1000);
		},
		error: function (xhr, textStatus, errorThrown) {
			console.log(errorThrown);
			$("#refreshButtonSearch").addClass("hide");
			$("#formDetailCheckout").addClass("hide");
			$("#notFoundBookingRoom").removeClass("hide");
			setTimeout(function () { $("#notFoundBookingRoom").addClass("hide"); }, 2000);
		}
	})

}

function addFeeRoom() {

	var feeName = $("#nameFee").val();
	var feeAmount = $("#priceFee").val();
	var idBill = $("#idBill").val();
	var idBookingRoom = $("#idBookingRoom").val();
	var discount = $("#discount").val();
	var data = "";
	data += "idBookingRoom=" + idBookingRoom + "&idBill=" + idBill + "&feeName=" + feeName + "&feeAmount=" + feeAmount + "&discount=" + discount;

	$.ajax({
		type: 'POST',
		url: '/ajax/room/addfeeroom',
		dataType: 'json',
		data,
		success: function (data, textStatus, xhr) {

			$("#addFeeSuccess").removeClass("hide");
			setTimeout(function () { $("#addFeeSuccess").addClass("hide"); }, 2000);
			reloadFeeRoom();
		},
		error: function (xhr, textStatus, errorThrown) {
			console.log(errorThrown);
			$("#addFeeFail").removeClass("hide");
			setTimeout(function () { $("#addFeeFail").addClass("hide"); }, 2000);
		}
	})

}

function reloadFeeRoom() {
	var bookingCode = $("#bookingCode").val();

	$.ajax({
		type: 'POST',
		url: '/ajax/room/reloadfeeroom',
		dataType: 'json',
		data: {
			'bookingCode': bookingCode
		},
		success: function (data, textStatus, xhr) {
			$("#feeRoomDetail tr:has(td)").remove();
			var totalFeeRoom = 0;
			var data_fee_room = "";
			$.each(data, function (key, value) {
				var priceFeeRoom = formatMoney(value.priceFeeRoom);
				totalFeeRoom += value.priceFeeRoom;

				data_fee_room += '<tr>';
				data_fee_room += '<td >' + ++key + '</td>';
				data_fee_room += '<td >' + value.nameFeeRoom + '</td>';
				data_fee_room += '<td >' + priceFeeRoom + '</td>';
				if (value.typeFee == 1) {
					data_fee_room += '<td> <button type="button" class="btn btn-xs btn-hover btn-danger disabled" id="btnDelFeeRoom"  data-bill-detail-id="' + value.idBillDetail + '"> <i class="fa fa-trash-o"> </i> </button> </td>'
				}
				else {
					data_fee_room += '<td> <button type="button" class="btn btn-xs btn-hover btn-danger" id="btnDelFeeRoom"  data-bill-detail-id="' + value.idBillDetail + '"> <i class="fa fa-trash-o"> </i> </button> </td>'
				}
				data_fee_room += '</tr>';
			});

			data_fee_room += '<td colspan="2" class="text-bold">' + ' Tổng tiền phòng:' + '</td> ';
			data_fee_room += '<td colspan="2" class="text-bold">' + formatMoney(totalFeeRoom) + '</td>';
			$('#feeRoomDetail').append(data_fee_room);


			var totalFeeService = $("#totalFeeService").val();
			$("#totalFeeRoom").val(totalFeeRoom);


			var totalPayment = parseInt(totalFeeRoom) + parseInt(totalFeeService);
			var discountPrice = data[1].priceDiscount;
			var deposited = $("#deposited").val();
			var totalNeedPayment = parseInt(totalPayment) - parseInt(discountPrice) - parseInt(deposited);
			$("#totalPayment").text(formatMoney(totalPayment));
			$("#totalNeedPayment").text(formatMoney(totalNeedPayment));
			$("#priceTotal").val(totalNeedPayment);
		},
		error: function (xhr, textStatus, errorThrown) {
			console.log(errorThrown);
		}
	})


}


function addFeeService() {

	var idBookingRoom = $("#idBookingRoom").val();
	var idService = $("#idService").val();
	var priceService = $("#priceService").val();
	var quantity = $("#quantityService").val();

	var data = "";
	data += "idBookingRoom=" + idBookingRoom + "&idService=" + idService + "&priceService=" + priceService + "&quantity=" + quantity;

	$.ajax({
		type: 'POST',
		url: '/ajax/room/addfeeservice',
		dataType: 'json',
		data,
		success: function (data, textStatus, xhr) {

			$("#addFeeServiceSuccess").removeClass("hide");
			setTimeout(function () { $("#addFeeServiceSuccess").addClass("hide"); }, 2000);
			reloadFeeService();

		},
		error: function (xhr, textStatus, errorThrown) {
			console.log(errorThrown);
			$("#addFeeServiceFail").removeClass("hide");
			setTimeout(function () { $("#addFeeServiceFail").addClass("hide"); }, 2000);
		}
	})

}

function reloadFeeService() {
	var bookingCode = $("#bookingCode").val();

	$.ajax({
		type: 'POST',
		url: '/ajax/room/reloadfeeservice',
		dataType: 'json',
		data: {
			'bookingCode': bookingCode
		},
		success: function (data, textStatus, xhr) {
			$("#feeServiceDetail tr:has(td)").remove();
			var totalFeeService = 0;
			var data_fee_service = "";
			$.each(data, function (key, value) {
				var priceService = formatMoney(value.priceService);
				var amount = formatMoney(value.amount);
				totalFeeService += value.amount;

				data_fee_service += '<tr>';
				data_fee_service += '<td >' + ++key + '</td>';
				data_fee_service += '<td >' + value.nameService + '</td>';
				data_fee_service += '<td >' + value.unit + '</td>';
				data_fee_service += '<td >' + priceService + '</td>';
				data_fee_service += '<td >' + value.quantity + '</td>';
				data_fee_service += '<td >' + amount + '</td>';
				data_fee_service += '<td> <button type="button" class="btn btn-xs btn-hover btn-danger" id="btnDelFeeService" data-service-id="' + value.idService + '" data-service-detail-id="' + value.idServiceDetail + '"data-service-quantity="' + value.quantity + '"data-price-service="' + value.priceService + '"><i class="fa fa-trash-o"></i></button> </td>'
				data_fee_service += '</tr>';
			});


			data_fee_service += '<td colspan="5" class="text-bold">' + ' Tổng tiền dịch vụ:' + '</td> ';
			data_fee_service += '<td colspan="2" class="text-bold">' + formatMoney(totalFeeService) + '</td>';
			$('#feeServiceDetail').append(data_fee_service);


			var totalFeeRoom = $("#totalFeeRoom").val();
			$("#totalFeeService").val(totalFeeService);

			var totalPayment = parseInt(totalFeeRoom) + parseInt(totalFeeService);
			var discountPrice = $("#priceDiscount").val();
			var deposited = $("#deposited").val();
			var totalNeedPayment = parseInt(totalPayment) - parseInt(discountPrice) - parseInt(deposited);
			$("#totalPayment").text(formatMoney(totalPayment));
			$("#totalNeedPayment").text(formatMoney(totalNeedPayment));
			$("#priceTotal").val(totalNeedPayment);


		},
		error: function (xhr, textStatus, errorThrown) {
			var data_fee_service = "";
			$("#feeServiceDetail tr:has(td)").remove();
			data_fee_service += '<tr>';
			data_fee_service += '<td colspan="7" class="text-center">' + '<p> Chưa có dịch vụ nào được đặt ... </p>' + '</td>';
			data_fee_service += '</tr>';
			$('#feeServiceDetail').append(data_fee_service);
			var totalFeeService = 0;

			var totalFeeRoom = $("#totalFeeRoom").val();
			$("#totalFeeService").val(totalFeeService);

			var totalPayment = parseInt(totalFeeRoom) + parseInt(totalFeeService);
			var discountPrice = $("#priceDiscount").val();
			var deposited = $("#deposited").val();
			var totalNeedPayment = parseInt(totalPayment) - parseInt(discountPrice) - parseInt(deposited);
			$("#totalPayment").text(formatMoney(totalPayment));
			$("#totalNeedPayment").text(formatMoney(totalNeedPayment));
			$("#priceTotal").val(totalNeedPayment);
		}
	})


}

function deleteFeeService() {
	$("#feeServiceDetail").on('click', '#btnDelFeeService', function () {
		var idDetailService = $(this).data("service-detail-id");
		$.ajax({
			type: 'POST',
			url: '/ajax/room/deletefeeservice',
			dataType: 'json',
			data: {
				'idDetailService': idDetailService,
			},
			success: function (data, textStatus, xhr) {
				reloadFeeService();
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});
	})
}

function deleteFeeRoom() {
	$("#feeRoomDetail").on('click', '#btnDelFeeRoom', function () {
		var idBillDetail = $(this).data("bill-detail-id");
		$.ajax({
			type: 'POST',
			url: '/ajax/room/deletefeeroom',
			dataType: 'json',
			data: {
				'idBillDetail': idBillDetail,
			},
			success: function (data, textStatus, xhr) {
				reloadFeeRoom();
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});
	})
}


function printBill() {
	var pageTitle = 'Hóa đơn thanh toán',
		stylesheet = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
		newWin = window.open('');
	newWin.document.write('<html><head><title>' + pageTitle + '</title>' +
		'<link rel="stylesheet" href="' + stylesheet + '">' +
		'</head><body>' + $('#formDetailCheckout')[0].outerHTML + '</body></html>');
	newWin.document.close();
	newWin.print();
	newWin.close();
	return false;
}












