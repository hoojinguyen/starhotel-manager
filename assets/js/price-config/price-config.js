$(document).ready(function () {
	UpdatePrice()
})

function getPrice(roomType) {
	$(".overlay").css("display", "block")
	var data = null;
	switch (roomType) {
		case 1: {
			data = 1
			$("#room-type-display").val("PHÒNG ĐƠN")

			break;
		}
		case 2: {
			data = 2
			$("#room-type-display").val("PHÒNG ĐÔI");
			break;
		}
		default: {
			break;
		}
	}
	$(".overlay").removeClass("hide");
	$.ajax({
		type: 'POST',
		url: '/ajax/getPrice',
		dataType: 'json',
		data: {
			'roomType': data
		},
		success: function (price, textStatus, xhr) {
			$("#priceTable tr:has(td)").remove();
			$("#priceTable tr:has(th)").remove();

			var infoData = '<tr><td style="padding-left: 20px;"> Giờ đầu </td><td class="text-center">' + price.firstHourPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.firstHourPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Giờ tiếp theo </td><td class="text-center">' + price.nextHourPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.nextHourPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Giờ đầu sau 23h </td><td class="text-center">' + price.firstHourAfter23h.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.firstHourAfter23h.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Giờ tiếp theo sau 23h </td><td class="text-center">' + price.nextHourAfter23h.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.nextHourAfter23h.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Ngày </td><td class="text-center">' + price.dayPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.dayPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Ngày cuối tuần </td><td class="text-center">' + price.weekendDayPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.weekendDayPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Đêm </td><td class="text-center">' + price.nightPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.nightPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Đêm cuối tuần </td><td class="text-center">' + price.weekendNightPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.weekendNightPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Lố giờ </td><td class="text-center">' + price.OTHourPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.OTHourPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Vào sớm </td><td class="text-center">' + price.earlyHourPrice.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.earlyHourPrice.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'
			infoData += '<tr><td style="padding-left: 20px;"> Phụ phí </td><td class="text-center">' + price.surcharge.value + '</td><td class="text-center"> <span class = "badge bg-blue price-edit-button" data-function = "edit" id="price-edit-button" data-id = "' + price.surcharge.id + '"><i class="fa fa-fw fa-wrench"></i></span></td></tr>'

			$("#priceTable").append(infoData);
			$(".overlay").addClass("hide");
		},
		error: function (xhr, textStatus, errorThrown) {
			console.log(errorThrown);
		},
	})
}

function UpdatePrice() {
	$("#priceTable").on('click','.price-edit-button', function () {
	

		if ($(this).data("function") == "edit") {
			$(this).parent().parent().find("td:eq(1)").attr('contenteditable', 'true')
			$(this).parent().parent().find("td:eq(1)").css('border-style', 'groove;')
			$(this).removeClass('bg-blue').addClass('bg-yellow')
			$(this).find(".fa-wrench").removeClass('fa-wrench').addClass('fa-save')
			$(this).data("function", "save")
		} else {
			$(".overlay").removeClass("hide");
			var id = $(this).data("id")
			var priceText = $(this).parent().parent().find("td:eq(1)").text();
			var priceObject = $(this).parent().parent().find("td:eq(1)");
			var priceValue = priceText.replace(/[^0-9]/g, "")

			$.ajax({
				type: 'POST',
				url: '/ajax/update',
				dataType: 'json',
				data: {
					'id': id,
					'value': priceValue
				},
				success: function (data, textStatus, xhr) {
					priceObject.text(data)
					$(".overlay").addClass("hide");
				},
				error: function (xhr, textStatus, errorThrown) {
					console.log(errorThrown);
				},
			})
			$(this).parent().parent().find("td:eq(1)").attr('contenteditable', 'false')
			$(this).removeClass('bg-yellow').addClass('bg-blue')
			$(this).find(".fa-save").removeClass('fa-save').addClass('fa-wrench')
			$(this).data("function", "edit")

		}

	})
}