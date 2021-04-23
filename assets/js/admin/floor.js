function validateFloorForm() {
	let floorForm = $("#floorInfo");

	let validator = floorForm.validate({
		//errorClass: "has-error",
		// validClass: "has-success",
		rules: {
			nameFloor: {
				required: true,
				minlength: 4
			},
			codeFloor: {
				required: true,
				minlength: 2
			}
		},
	})

	return validator.form();

}

function loadFloor() {
	var data = "";
	$.ajax({
		type: 'POST',
		url: '/ajax/manager/room/loadfloor',
		data,
		dataType: 'json',
	}).done(function (result) {
		var info_data = '';
		$("#floors tr:has(td)").remove();

		$.each(result, function (key, value) {
			info_data += '<tr>';
			info_data += '<td>' + value.code + '</td>';
			info_data += '<td>' + value.name + '</td>';
			info_data += '<td>' + value.active + '</td>';
			info_data += '<td class="text-center"><button type="button" class="btn btn-warning btn-sm edit-floor" data-toggle="modal" data-target="#modal-addFloor-updateFloor" data-floor-id="' + value.id + '" data-floor-name="' + value.name + '"><span class="fa fa-wrench"></span></button> '
			info_data += '<button type="button" class="btn btn-danger btn-sm delele-floor" data-toggle="modal" data-target="#modal-del-floor" data-floor-id="' + value.id + '" data-floor-name="' + value.name + '"><span class="fa fa-trash-o"></span></button> </td>'
			info_data += '</tr>';
		});
		$('#floors').append(info_data);

	});
};

function addFloor() {
	$(".overlay").removeClass("hide");
	$(".overlay").addClass("show");

	if (!validateFloorForm()) {
		$("#add-or-update-floor").text("Lưu");
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
		return 0;
	}
	var data = $("#floorInfo").serialize();
	$.ajax({
		type: 'POST',
		url: '/ajax/manager/room/addfloor',
		data,
		dataType: 'json',
		error: function (xhr, textStatus, errorThrown) {
			$("#resultFloor").addClass('text-red');
			$("#resultFloor").removeClass("hidden");
			switch (xhr.status) {
				case 500: {
					$("#resultFloor").text("Thêm không thành công!");
					break;
				}
				case 422: {
					$("#resultFloor").text("Bạn không có quyền truy cập chức năng này!");
					break;
				}
			}
			$("#modal-addFloor-updateFloor .overlay").removeClass('show').addClass("hide");
		},
		success: function (data, textStatus, xhr) {
			switch (xhr.status) {
				case 201: {
					$("#resultFloor").removeClass('text-red');
					$("#resultFloor").removeClass('hidden');
					$("#resultFloor").addClass("success");
					$("#resultFloor").text("Thêm thành công !");
					setTimeout(function () { $("#resultFloor").text(""); }, 1000);
					break;
				}
				default:
					break;
			}
			setTimeout(function () { location.reload(); }, 1000);
		}

	});



}

function updateFloor() {
	$(".overlay").removeClass("hide");
	$(".overlay").addClass("show");
	if (!validateFloorForm()) {
		$("#add-or-update-floor").text("Cập nhật");
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
		return 0;
	}
	var data = $("#floorInfo").serialize();
	data += '&id=' + floorId;

	$.ajax({
		type: 'POST',
		url: '/ajax/manager/room/updatefloor',
		data,
		dataType: 'json',
		error: function (xhr, textStatus, errorThrown) {
			$("#resultFloor").addClass('text-red');
			$("#resultFloor").removeClass("hidden");
			switch (xhr.status) {
				case 500: {
					$("#resultFloor").text("Cập nhật không thành công!");
					break;
				}
				case 422: {
					$("#resultFloor").text("Bạn không có quyền truy cập chức năng này!");
					break;
				}
			}
			$("#modal-addFloor-updateFloor .overlay").removeClass('show').addClass("hide");
		},
		success: function (data, textStatus, xhr) {
			switch (xhr.status) {
				case 201: {
					$("#resultFloor").removeClass('text-red');
					$("#resultFloor").removeClass('hidden');
					$("#resultFloor").addClass("success");
					$("#resultFloor").text("Câp nhật thành công !");
					setTimeout(function () { $("#resultFloor").text(""); }, 1000);
					break;
				}
				default:
					break;
			}
			setTimeout(function () { location.reload(); }, 1000);
		},
	});
}

function deletefloor() {
	$('.delete-floor').on('click', function () {
		var floorId = $(this).data("floor-id");
		console.log(floorId);

		$("#deleteFloorId").val(floorId);

		var nameFloor = $(this).data("floor-name");
		$('.info-del-floor').html(nameFloor);
	});

	$("#confirm-del-floor").on('click', function () {
		$.ajax({
			type: 'POST',
			url: '/ajax/manager/room/deletefloor',
			dataType: 'json',
			data: {
				'id': $("#deleteFloorId").val(),
			},

			error: function (xhr, textStatus, errorThrown) {
				$("#result-floor").addClass('text-red');
				$("#result-floor").removeClass("hidden");
				switch (xhr.status) {
					case 500: {
						$("#result-floor").text("Xóa không thành công!");
						break;
					}
					case 422: {
						$("#result-floor").text("Bạn không có quyền truy cập chức năng này!");
						break;
					}
				}
				$("#modal-del-floor .overlay").removeClass('show').addClass("hide");
			},
			success: function (data, textStatus, xhr) {
				switch (xhr.status) {
					case 201: {
						$("#result-floor").removeClass('text-red');
						$("#result-floor").removeClass('hidden');
						$("#result-floor").addClass("success");
						$("#result-floor").text("Xóa thành công!");
						$("#modal-del-floor .overlay").removeClass('show').addClass("hide");
						break;
					}
					default:
						break;
				}
				setTimeout(function () { location.reload(); }, 1000);
			}

		})
	});
}


$(document).ready(function () {
	$(function () {


		// value Add
		$("#btn-add-floor").on('click', function () {
			$("#floorInfo").find("input[type = 'hidden']").val('add');
			$("#add-or-update-floor").text("Lưu");

			$("#add-or-update-floor").removeClass("btn-warning");
			$("#add-or-update-floor").addClass("btn-primary");

			$("#close").removeClass("btn-warning");
			$("#close").addClass("btn-primary");

			// reset validate
			$("input").removeClass("valid");
			$("input").removeClass("error");
			$(".error").css("display", "none");

			$("#floorInfo")[0].reset();

		})



		// value Update
		$('.edit-floor').on('click', function () {
			floorId = $(this).data("floor-id");

			$("#add-or-update-floor").text("Cập nhật");
			$("#floorInfo").find("#floorId").val(floorId);
			$("#floorInfo").find("#floorAction").val('update');

			$("#add-or-update-floor").removeClass("btn-primary");
			$("#add-or-update-floor").addClass("btn-warning");

			$("#close").removeClass("btn-primary");
			$("#close").addClass("btn-warning");

			// reset validate
			$("input").removeClass("valid");
			$("input").removeClass("error");
			$(".error").css("display", "none");

			$.ajax({
				type: 'POST',
				url: '/ajax/manager/room/loadfloorbyid',
				dataType: 'json',
				data: {
					'id': floorId
				},
				success: function (data, textStatus, xhr) {
					$("#floorInfo").find("input[name = 'nameFloor']").val(data.name);
					$("#floorInfo").find("input[name = 'codeFloor']").val(data.code);
				},

				error: function (xhr, textStatus, errorThrown) {
					$("#resultFloor").addClass('text-red');
					$("#resultFloor").removeClass("hidden");
					switch (xhr.status) {
						case 500: {
							$("#resultFloor").text("Cập nhật không thành công!");
							break;
						}
						case 422: {
							$("#resultFloor").text("Bạn không có quyền truy cập chức năng này!");
							break;
						}
					}
					$("#modal-addFloor-updateFloor .overlay").removeClass('show').addClass("hide");
				}

			})
		})


		// select pop-up add or update
		$("#add-or-update-floor").on('click', function () {
			if ($("#floorInfo").find("input[type = 'hidden']").val() == "add") {
				addFloor();
			}
			else {
				updateFloor();
			}
		});


		// Delete
		deletefloor();

	});






})













