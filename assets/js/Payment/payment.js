$(document).ready(function () {
	getCheckoutInfo();
	getServiceDetailById();
	getQuotation();
	payment();
})
function getCheckoutInfo() {
	$(".listRroom").on('click', '#btnPayment', function () {
		var id = $(this).data("order-id");

		$.ajax({
			type: 'POST',
			url: '/ajax/checkout/getorderroomid',
			dataType: 'json',
			data: {
				'id': id
			},
			success: function (data, textStatus, xhr) {
				console.log(data);
				$("#modal-checkout").find("#guestName").text(data.guestId.name);
				$("#modal-checkout").find("#phoneNumber").text(data.guestId.phoneNumber);
				$("#modal-checkout").find("#idCardNo").text(data.guestId.idCardNo);
				$("#modal-checkout").find("#roomName").text(data.roomId.nameRoom);
				$("#modal-checkout").find("#roomType").text(data.roomId.roomTypeId.name);
				if (data.dateCheckin) {
					var dCheckIn = formatDateTime(data.dateCheckin.date);
					$("#modal-checkout").find("#checkinTime").text(dCheckIn);
				}
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		})
	})
}

function getServiceDetailById() {
	$(".listRroom").on('click', '#btnPayment', function () {
		var id = $(this).data("order-id");
		$.ajax({
			type: 'POST',
			url: '/ajax/checkout/getservicedetailbyid',
			dataType: 'json',
			data: {
				'id': id
			}
		})
			.done(function (result) {
				var info_data = '';
				$("#infoDetailService tr:has(td)").remove();
				$.each(result, function (key, value) {
					var sumPrice = formatMoney(value.sumPrice);
					info_data += '<tr>';
					info_data += '<td>' + value.name + '</td>';
					info_data += '<td>' + value.numOrder + '</td>';
					info_data += '<td>' + sumPrice + '</td>';
					info_data += '</tr>';
				});
				$('#infoDetailService').append(info_data);
			});
	})
}

function formatDateTime(date) {
	var date1 = new Date(date);
	var format = "AM";
	var hour = date1.getHours();
	var min = date1.getMinutes();
	if (hour > 11) { format = "PM"; }
	if (hour > 12) { hour = hour - 12; }
	if (hour == 0) { hour = 12; }
	if (min < 10) { min = "0" + min; }

	var radix = 10;
	var dateConverted = '';
	if (parseInt(date1.getMonth(), radix) < 9) {
		dateConverted = date1.getDate() + '-' + '0' + (parseInt(date1.getMonth() + 1, radix)).toString() + '-' + date1.getFullYear() + " " + hour + ":" + min + " " + format;
	}
	else {
		dateConverted = date1.getDate() + '-' + (parseInt(date1.getMonth() + 1, radix)).toString() + '-' + date1.getFullYear() + " " + hour + ":" + min + " " + format;
	}
	if (parseInt(date1.getDate(), radix) < 9) {
		dateConverted = "0" + dateConverted
	}


	return dateConverted;
}

function getQuotation() {
	$(".listRroom").on('click', '#btnPayment', function () {
		$(".modal .overlay").css('display', 'block')

		var id = $(this).data("order-id");
		$("#modal-checkout #orderId").val(id);
		$.ajax({
			type: 'POST',
			url: '/ajax/checkout/quotation',
			dataType: 'json',
			data: {
				'orderId': id
			},
			success: function (data, textStatus, xhr) {
				if (data.roomAmount.rentType == "Theo ngày" || data.roomAmount.rentType == "Qua đêm") {

					$("#modal-checkout #extraInfo").css("display", "block");
					$("#modal-checkout").find("#OTHour").text(data.roomAmount.OTHour);
					$("#modal-checkout").find("#earlyHour").text(data.roomAmount.earlyHour);
					$("#modal-checkout").find("#weekendDays").text(data.roomAmount.weekendDays);
					$("#modal-checkout #weekendDayBlock").css("display", "block");
					$("#modal-checkout #hoursBlock").css("display", "none");
					if (data.roomAmount.rentType == "Qua đêm") {
						$("#modal-checkout").find("#rentType").text('Qua đêm');
					} else {
						$("#modal-checkout").find("#rentType").text('Theo ngày');
					}
				} else {
					$("#modal-checkout").find("#rentType").text('Theo giờ');
					$("#modal-checkout").find("#hours").text(data.roomAmount.hours);

					$("#modal-checkout #extraInfo").css("display", "none");
					$("#modal-checkout #weekendDayBlock").css("display", "none");
					$("#modal-checkout #hoursBlock").css("display", "block");

				}
				$("#modal-checkout").find("#surcharge").text(formatMoney(data.roomAmount.surcharge));
				$("#modal-checkout").find("#checkoutTime").text(formatDateTime(data.roomAmount.checkoutTime.date));
				$("#modal-checkout").find("#totalService").text(formatMoney(data.serviceAmount));
				$("#modal-checkout").find("#totalBill").text(formatMoney(data.total));
				$("#modal-checkout").find("#totalRoom").text(formatMoney(data.roomAmount.total));
				$("#modal-checkout").find("#datapayment").val(data.roomAmount.total);

			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		}).done(function () {
			$(".modal .overlay").css('display', 'none')
		})
	})
}

function payment() {
	$("#confirmPayment").on('click', function () {
		$.ajax({
			type: 'POST',
			url: '/ajax/checkout/payment',
			dataType: 'json',
			data: {
				'orderId': $("#modal-checkout #orderId").val(),
				'amount': $("#modal-checkout #datapayment").val(),
			},
			success: function (data, textStatus, xhr) {
				console.log("data");
				
				setTimeout(function () { $('#modal-checkout').modal('hide'); }, 5000);
				setTimeout(function () {
					location.reload();
				}, 5000);


			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		})
	})
}
