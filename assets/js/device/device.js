function validateDeviceForm() {
	let deviceForm = $("#deviceInfo");
	if ($("#deviceInfo").length) {
		let validator = deviceForm.validate({
			errorClass: "has-error",
			validClass: "has-success",
			rules: {
				name: {
					required: true,
					minlength: 2
				},
				code: {
					required: true,
					minlength: 2
				},
				price: {
					required: true,
					minlength: 4
				},
				importDate: {
					required: true,
					minlength: 4
				}

			},
			messages: {
				name: {
					required: "Dữ liệu này là bắt buộc !",
					minlength: 2
				},
				code: {
					required: "Dữ liệu này là bắt buộc !",
					minlength: 2
				},
				price: {
					required: "Dữ liệu này là bắt buộc !",
					minlength: 4
				},
				importDate: {
					required: "Dữ liệu này là bắt buộc !",
					minlength: 4
				}
			}
		});

		return validator.form();
	}

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

function loadDevice() {
	$(".devices-table .overlay").addClass("show").removeClass("hide")
	$.ajax({
		type: 'POST',
		url: '/ajax/manage/device/reload',
		dataType: 'json',
		success: function (data) {
			var info_data = '';
			$("#devices tr:has(td)").remove();
			if (data.length <= 0) {
				info_data = '<tr><td colspan = 5>Không tìm thấy thiết bị nào</td></tr>';
				$('#devices').append(info_data);
			} else {

				$.each(data, function (key, value) {
					info_data += '<tr>';
					info_data += '<td>' + value.code + '</td>';
					info_data += '<td>' + value.name + '</td>';
					info_data += '<td>' + value.price + '</td>';
					info_data += '<td>' + value.importDate + '</td>';

					info_data += '<td>  <button type="button" class="btn btn-warning btn-sm edit-button-device"  data-id="' + value.id + '"  data-toggle="modal" data-target="#modal-addDevice"><span class="fa fa-wrench"></span></button> <button type="button" class="btn btn-danger btn-sm del-button-device"  data-id="' + value.id + '"   data-device-name="' + value.name + '"   data-toggle="modal" data-target="#modal-del-device"><span class="fa fa-trash-o"></span></button> </td>'
					info_data += '</tr>';
				});
				$('#devices').append(info_data);
			}

		}
	}).done(function () {
		$(".devices-table .overlay").addClass("hide").removeClass("show")
	})
}

function loadDeviceInRoom(roomId) {
	$(".deviceInRoom-table .overlay").addClass("show").removeClass("hide")
	$("#btn-add-deviceInRoom").data("roomId", roomId);

	// if (room == 1) {
	// 	$("#room-display").val("PHÒNG 101")
	// } else if( room ==2 ) {
	// 	$("#room-display").val("PHÒNG 102")

	// }
	$.ajax({
		type: 'POST',
		url: '/ajax/manage/deviceInRoom/reload',
		data: {
			'room': roomId
		},
		dataType: 'json',
		success: function (data) {
			var info_data = '';
			$("#deviceInRoom tr:has(td)").remove();
			if (data.length <= 0) {
				info_data = '<tr><td colspan = 5>Không tìm thấy thiết bị nào</td></tr>';
				$('#deviceInRoom').append(info_data);
			} else {
				$.each(data, function (key, value) {
					info_data += '<tr>';
					info_data += '<td>' + value.code + '</td>';
					info_data += '<td>' + value.name + '</td>';
					info_data += '<td>' + value.quantity + '</td>';
					info_data += '<td><button type="button" class="btn btn-warning btn-sm edit-button-deviceInRoom" data-function="edit" data-id="' + value.id + '"><span class="fa fa-wrench"></span></button><button type="button" class="btn btn-danger btn-sm del-button-deviceInRoom" data-toggle="modal" data-target="#modal-del-deviceInRoom" data-id="' + value.id + '"><span class="fa fa-trash-o"></span></button></td>'
					info_data += '</tr>';
				});
				$('#deviceInRoom').append(info_data);
			}
		}
	}).done(function () {
		$(".deviceInRoom-table .overlay").addClass("hide").removeClass("show")
	})

}

function addDevice() {
	$("#modal-addDevice .overlay").removeClass("hide").addClass("show");
	$("#add-or-update-device").text("Đang lưu ...");
	if (!validateDeviceForm()) {
		$("#add-or-update-device").text("Lưu");
		$(".modal-addDevice .overlay").addClass("hide").removeClass("show");
		return 0;
	}
	var data = $("#deviceInfo").serialize();
	$.ajax({
		type: 'POST',
		url: '/ajax/manager/device/adddevice',
		data,
		dataType: 'json',
		error: function (xhr, textStatus, errorThrown) {
			switch (xhr.status) {
				case 422:
					$("#resultDevice").removeClass("success");
					$("#resultDevice").addClass("error");
					$("#resultDevice").text("Dữ liệu không hợp lệ !");
					break;
				case 500:
					$("#resultDevice").removeClass("success");
					$("#resultDevice").addClass("error");
					$("#resultDevice").text("Hệ thống đang bận, vui lòng thử lại sau !");
					break;
				default:
					break;
			}
		},
		success: function (data, textStatus, xhr) {
			$("#resultDevice").removeClass();
			$("#resultDevice").addClass("success");
			$("#resultDevice").text("Lưu thành công !");
			$("#add-or-update-device").text("Lưu");

			setTimeout(function () { $("#resultDevice").text(""); }, 1000);
			setTimeout(function () { $("#modal-addDevice").modal('hide'); }, 1000);
			loadDevice();
			$("#modal-addDevice .overlay").removeClass("show").addClass("hide");

		},
	})

}

function updateDevice() {

	$(".modal-addDevice .overlay").addClass("show").removeClass("hide");

	$("#add-or-update-device").text("Đang cập nhật ...");

	if (!validateDeviceForm()) {
		$(".modal-addDevice .overlay").addClass("hide").removeClass("show");

		$("#add-or-update-device").text("Cập nhật");
		return 0;
	}
	var data = $("#deviceInfo").serialize();
	data += '&id=' + id;
	$.ajax({
		type: 'POST',
		url: '/ajax/manager/device/update',
		data,
		dataType: 'json',
		error: function (xht, textStatus, errorThrown) {
			switch (xht.status) {
				case 422:
					$("#resultDevice").removeClass("success");
					$("#resultDevice").addClass("error");
					$("#resultDevice").text("Dữ liệu không hợp lệ !");
					break;
				case 500:
					$("#resultDevice").removeClass("success");
					$("#resultDevice").addClass("error");
					$("#resultDevice").text("Hệ thống đang bận, vui lòng thử lại sau !");
					break;

				default:
					break;
			}
		},
		success: function (data, textStatus, xht) {
			$("#resultDevice").removeClass();
			$("#resultDevice").addClass("success");
			$("#resultDevice").text("Cập nhật thành công !");
			$("#add-or-update-device").text("Cập nhật");

			setTimeout(function () { $("#resultDevice").text(""); }, 1000);
			setTimeout(function () { $("#modal-addDevice").modal('hide'); }, 1000);
			loadDevice();
			$(".modal-addDevice .overlay").addClass("hide").removeClass("show");

		},

	})

}

function deleteDevice() {
	$("#devices").on('click', '.del-button-device', function () {
		var id = $(this).data("id");
		$("#modal-del-device").addClass("show").removeClass("hide");
		$("#confirm-del-device").on('click', function () {
			$("#confirm-del-device").text("Đang xóa ...");
			$.ajax({
				type: 'POST',
				url: '/ajax/manage/device/delete',
				dataType: 'json',
				data: {
					'id': id,
				},
				error: function () {
					alert("Không thể xóa, Đang có phòng sử dụng thiết bị này !!")
				}
			}).always(function () {
				$("#resultDelDevice").text("Xóa thành công !");
				$("#confirm-del-device").text("Xóa");
				$("#modal-del-device").modal('hide');
				loadDevice();
				$("#modal-del-device").addClass("hide").removeClass("show");

			});


		})

	})


}

function deleteDeviceInRoom() {
	var id

	$("#deviceInRoom").on('click', '.del-button-deviceInRoom', function () {
		id = $(this).data("id");
	})
	$("#confirm-del-deviceInRoom").on('click', function () {
		$("#modal-del-deviceInRoom .overlay").addClass("show").removeClass("hide")
		$("#confirm-del-deviceInRoom").text("Đang xóa ...");
		$("#confirm-del-deviceInRoom").attr("disable", "true");
		$.ajax({
			type: 'POST',
			url: '/ajax/manage/deviceInRoom/delete',
			dataType: 'json',
			data: {
				'id': id,
			},
			success: function (data) {
				$("#resultDelDevice").text("Xóa thành công !");
				$("#confirm-del-deviceInRoom").text("Xóa");
				$("#modal-del-deviceInRoom").modal('hide');
				loadDeviceInRoom($("#btn-add-deviceInRoom").data("roomtype"));
				$("#modal-del-deviceInRoom .overlay").addClass("hide").removeClass("show")

			}


		})

	})


}

function loadDeviceName() {
	var id;
	$("#btn-add-deviceInRoom").on('click', function () {
		$("#modal-add-deviceInRoom .overlay").addClass("show").removeClass("hide")
		$.ajax({
			type: 'POST',
			url: '/ajax/manage/device/reload',
			dataType: 'json',
			success: function (data) {
				var info_data = '';
				$("#list-Device option").remove();
				if (data.length <= 0) {
					$("#list-Device").val("Chưa có thiết bị");
				} else {
					id = data[0].id
					$.each(data, function (key, value) {
						info_data += '<option data-id = "' + value.id + '">' + value.name + '</option>';
					});
					$('#list-Device').append(info_data);
				}

			}
		}).done(function () {
			$("#modal-add-deviceInRoom .overlay").addClass("hide").removeClass("show")
		})


	})
	$("#confirm-add-deviceInRoom").on('click', function () {
		$("#modal-add-deviceInRoom .overlay").addClass("show").removeClass("hide")
		$.ajax({
			type: 'POST',
			url: '/ajax/manage/deviceInRoom/save',
			dataType: 'json',
			data: {
				'roomTypeId': $("#btn-add-deviceInRoom").data("roomtype"),
				'deviceId': id,
				'quantity': $("#add-quantity").val()
			},
			success: function (data) {
				$("#modal-add-deviceInRoom").modal("hide");
				loadDeviceInRoom($("#btn-add-deviceInRoom").data("roomtype"))
			}
		}).done(function () {
			$("#modal-add-deviceInRoom .overlay").addClass("hide").removeClass("show")
		})
	})

	$("#list-Device").on('change', function () {
		id = $("#list-Device :selected").data("id")
	});
}
function UpdateDeviceInRoom() {
	$("#deviceInRoom").on('click', '.edit-button-deviceInRoom', function () {
		if ($(this).data("function") == "edit") {
			$(this).parent().parent().find("td:eq(2)").attr('contenteditable', 'true')
			$(this).removeClass('btn-warning').addClass('btn-success')
			$(this).find(".fa-wrench").removeClass('fa-wrench').addClass('fa-save')
			$(this).data("function", "save")
		} else {
			$(".deviceInRoom-table .overlay").addClass("show").removeClass("hide")
			var id = $(this).data("id")
			var quantityText = $(this).parent().parent().find("td:eq(2)").text();
			var quantityObject = $(this).parent().parent().find("td:eq(2)");
			var quantityValue = quantityText.replace(/[^0-9]/g, "")

			$.ajax({
				type: 'POST',
				url: '/ajax/manage/deviceInRoom/update',
				dataType: 'json',
				data: {
					'id': id,
					'value': quantityValue
				},
				success: function (data, textStatus, xhr) {
					if (data > 0) {
						quantityObject.text(data)
					} else {
						quantityObject.parent().remove()
					}
				},
				error: function (xhr, textStatus, errorThrown) {
					console.log(errorThrown);
				},
			}).done(function () {
				$(".deviceInRoom-table .overlay").addClass("hide").removeClass("show")
			})
			$(this).parent().parent().find("td:eq(2)").attr('contenteditable', 'false')
			$(this).addClass('btn-warning').removeClass('btn-success')
			$(this).find(".fa-save").removeClass('fa-save').addClass('fa-wrench')
			$(this).data("function", "edit")

		}

	})
}

$(document).ready(function () {
	deleteDeviceInRoom();
	loadDeviceName();
	validateDeviceForm();
	$("#btn-add-deviceInRoom").data("roomtype", 1);
	$("#deviceInfo #importDate").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});

	$("#btn-add-device").on('click', function () {
		$("#deviceInfo").find("input[type = 'hidden']").val('addDevice');
		$("#add-or-update-device").text("Lưu");

		$("#add-or-update-device").removeClass("btn-warning");
		$("#add-or-update-device").addClass("btn-primary");

		$("#close-device").removeClass("btn-warning");
		$("#close-device").addClass("btn-primary");

		$("#refreshAnnimationDevice").addClass("text-blue");
		$("#refreshAnnimationDevice").removeClass("text-orange");

		// reset validate
		$("input").removeClass("valid");
		$("input").removeClass("error");
		$(".error").css("display", "none");

		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");

		$("#deviceInfo")[0].reset();

	});

	$("#devices").on('click', '.edit-button-device', function () {

		id = $(this).data("id");

		$(".modal-addDevice .overlay").addClass("show").removeClass("hide");


		$("#add-or-update-device").text("Cập nhật");
		$("#deviceInfo").find("input[type = 'hidden']").val('updateDevice');

		$("#add-or-update-device").removeClass("btn-primary");
		$("#add-or-update-device").addClass("btn-warning");

		$("#close-device").removeClass("btn-primary");
		$("#close-device").addClass("btn-warning");

		$("#refreshAnnimationDevice").removeClass("text-blue");
		$("#refreshAnnimationDevice").addClass("text-orange");


		// reset validate
		$("input").removeClass("valid");
		$("input").removeClass("error");
		$(".error").css("display", "none");

		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");

		$.ajax({
			type: 'POST',
			url: '/ajax/manager/device/loadbyid',
			dataType: 'json',
			data: {
				'id': id
			},
			success: function (data, textStatus, xhr) {
				$("#deviceInfo").find("input[name = 'name']").val(data.name);
				$("#deviceInfo").find("input[name = 'price']").val(data.price);
				$("#deviceInfo").find("input[name = 'code']").val(data.code);
				$("#deviceInfo").find("input[name = 'importDate']").val(data.importDate);
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		}).done(function () {
			$(".modal-addDevice .overlay").removeClass('show').addClass('hide')

		})
	});


	// select pop-up add or update
	$("#add-or-update-device").on('click', function () {
		if ($("#deviceInfo").find("input[type = 'hidden']").val() == "addDevice") {
			addDevice();
		}
		else {
			updateDevice();
		}
	});

	$("#close-device").on('click', function () {
		$("input").removeClass("has-error");
		$("input").removeClass("has-success");
		$(".has-error").remove()
		$(".error").css("display", "none");

		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");

		$("#deviceInfo")[0].reset();
	})

	UpdateDeviceInRoom();


	// Delete
	deleteDevice();
})