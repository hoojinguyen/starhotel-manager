$(document).ready(function () {
	$("#searchBy").change(function () {
		reloadGuestList();
	});
	validateGuestForm();

	$("#btnAddGuest").on('click', function () {
		resetGuestFormValidation();
	})
	$(".entries").change(function () {
		reloadGuestList();
	});
	$(".sort-desc").on('click', function (event) {

		$('#currentSortBy').val($(this).data('sortby'));
		$('#currentSortDir').val($(this).data('sortdir'));

		$(".sort-desc").removeClass("active-sort");
		$(".sort-asc").removeClass("active-sort");
		$(this).addClass("active-sort");
		reloadGuestList();

		$(".sort-asc").on('click', function (event) {

			$('#currentSortBy').val($(this).data('sortby'));
			$('#currentSortDir').val($(this).data('sortdir'));

			$(".sort-desc").removeClass("active-sort");
			$(".sort-asc").removeClass("active-sort");
			$(this).addClass("active-sort");
			reloadGuestList();
		});
	});
	validateGuestForm();

	handleEventForEditButton();

	handleEventForDeleteButton();

	handleEventForConfirmDeleteButton();

	handleEventForAddButton();

	handleEventForSaveButton();

	handleEventForPaginationButton();
	$(".overlay").removeClass("show");
	$(".overlay").addClass("hide");

	inputDateTimePicker()
});

function inputDateTimePicker() {
	$("#idCardExpiryDate").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});
	$("#idCardIssueDate").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});
}


// $(".page-link").on('click', function (event) {
// 	var selectedIndex = $(this).data('page');
// 	var currentIndex = $('#currentPageIndex').val();
// 	if (selectedIndex === -1 && currentIndex > 1) {
// 		selectedIndex = parseInt(currentIndex, 10) - 1;
// 	}
// 	else if (selectedIndex === 0) {
// 		selectedIndex = parseInt(currentIndex, 10) + 1;
// 	}
// 	else if (selectedIndex === -1 && currentIndex === 1) {
// 		selectedIndex = 1;
// 	}
// 	$('#currentPageIndex').val(selectedIndex);

// 	// remove class active
// 	$(".page-item").removeClass("active");
// 	// add class active with index selected
// 	$(this).parent().addClass("active");

// 	reloadGuestList();
// });


function validateGuestForm() {
	var guestForm = $('#guestInfo');

	if ($("#guestInfo").length) {
		var validator = guestForm.validate({
			errorClass: "has-error",
			validClass: "has-success",
			rules: {
				name: {
					required: true,
					minlength: 6
				},
				gender: {
					required: true
				},
				phoneNumber: {
					required: true,
					digits: true,
					minlength: 9,
					maxlength: 10
				},
				idCardNo: {
					required: true,
					digits: true,
					minlength: 9,
					maxlength: 10
				},
				idCardExpiryDate: {
					required: true
				},
				idCardIssuePlace: {
					minlength: 3
				},
				yearOfBirth: {
					range: [1900, 2019]
				}
			},
		});
		return validator.form();
	}
}

function saveGuest() {
	var actionText;
	var data = $('#guestInfo').serialize();

	if ($('#action').val() == 'add') {
		actionText = "Lưu";
	}
	else {
		actionText = "Cập nhật";
		var guestId = $('#guestId').val();
		data += '&id=' + guestId;
	}

	$(".overlay").removeClass("hide");
	$(".overlay").addClass("show");
	$(".overlay").removeClass("hide");
	$(".overlay").addClass("show");

	if (!validateGuestForm()) {
		$("#save").text(actionText);
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
		return 0;
	}

	$.ajax({
		type: 'POST',
		url: '/ajax/guest/save',
		data: $("#guestInfo").serialize(),
		dataType: 'json',
		error: function (xhr, textStatus, errorThrown) {
			switch (xhr.status) {
				case 422:
					$("#result").removeClass();
					$("#result").addClass("error");
					$("#result").text("Dữ liệu không hợp lệ!");
					break;
				case 500:
					$("#result").removeClass();
					$("#result").addClass("error");
					$("#result").text("Hệ thống đang bận, vui lòng thử lại sau!");
					break;
				case 409: {
					$("#result").removeClass();
					$("#result").addClass("error");
					$("input[id]").removeClass("has-success");
					$("#guestInfo").find("input[name = 'id']").addClass("has-error");
					$("#result").text("Đã tồn tại!");
					break;
				}
				default:
					break;
			}
		},
		success: function (data, textStatus, xhr) {
			switch (xhr.status) {
				case 201: {
					$("#result").removeClass();
					resetGuestFormValidation();
					$("#result").addClass("success");
					$("#result").text("Lưu thành công!");
					reloadGuestList();
					break;
				}
				default:
					break;
			}
		},
	});
		// .always(function () {
		// 	$("#save-guest").text(actionText);
		// 	$(".overlay").removeClass("show");
		// 	$(".overlay").addClass("hide");
		// });

}

function reloadGuestList() {
	// get page index from button
	var pageIndex = $('#currentPageIndex').val();

	// set page index to hidden input
	$('#currentPageIndex').val(pageIndex);
	var searchBy = $("#searchBy").val();
	var pageSize = $(".entries").val();
	var sortBy = $("#currentSortBy").val();
	var sortDir = $("#currentSortDir").val();
	$(".overlay").addClass("show").removeClass("hide");

	$.ajax({
		type: 'POST',
		url: 'http://localhost:8008/ajax/guest/reload',
		data: {
			pageIndex: 0,
			searchBy: searchBy,
			pageSize: pageSize,
			sortBy: sortBy,
			sortDir: sortDir
		},
		dataType: 'json',
	}).always(function (result) {
		$("#guests tr:has(td)").remove();

		// set list guests
		var guestRows = '';
		var guestsDisplay = 0;
		$.each(result.guests, function (key, value) {
			var guestRow = $('#guestRowTemplate').html();
			guestRow = guestRow.replace(/\[guest_id\]/g, value.id);
			guestRow = guestRow.replace(/\[guest_name\]/g, value.name);
			guestRow = guestRow.replace(/\[guest_idCardNo\]/g, value.idCardNo);
			guestRow = guestRow.replace(/\[guest_phoneNumber\]/g, value.phoneNumber);
			guestRow = guestRow.replace(/\[guest_yearOfBirth\]/g, value.yearOfBirth);
			guestRow = guestRow.replace(/\[guest_address\]/g, value.address);
			guestsDisplay++;
			guestRows += guestRow;
		});



		if (guestsDisplay == 0) {
			guestRows = '<tr><td colspan="6" style = "text-align: center">Không tìm thấy khách hàng</td></tr>'
		}
		$('#guestsBody').append(guestRows);

		$('.paging').html(guestsDisplay);
		$('.sum-paging').html(result.counts);

		$(".overlay").addClass("hide").removeClass("show");

		// if we don't need to create pagination
		$("#pagination").remove();
		if (!result.paginationInfo.needed) return;

		// create or re-render pagination element
		var paginationElement = '';

		paginationElement += '<nav id="pagination">';
		paginationElement += '<ul class="pagination m-0">';

		if (pageIndex == 0) {
			paginationElement += '<li class="page-item disabled"><a href="#" class="page-link" data-page="-1">Trước</a></li>';
		}
		else {
			paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex - 1) + '">Trước</a></li>';
		}

		for (var i = 0; i < result.paginationInfo.lastpage; i++) {
			if (i === Number(result.paginationInfo.page)) {
				paginationElement += '<li class="page-item active"><a class="page-link" href="#" data-page="' + i + '"">' + Number(i + 1) + '</a></li>';
			} else {
				paginationElement += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '"">' + Number(i + 1) + '</a></li>';
			}
		}

		if (pageIndex === Number(result.paginationInfo.lastpage) - 1) {
			paginationElement += '<li class="page-item disabled"><a href="#" class="page-link" data-page="' + (pageIndex) + '">Tiếp</a></li>';
		}
		else {
			paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex + 1) + '">Tiếp</a></li>';
		}
		paginationElement += '</ul>';
		paginationElement += '</nav>';

		$("#pagination-container").append(paginationElement);
		handleEventForPaginationButton();
	});
	// .fail(function (result) {

	// 	$("tr:has(td)").remove();
	// 	var info_data = '';
	// 	info_data += '<tr>';
	// 	info_data += '<td colspan="5" class="text-center">' + 'Không tìm thấy dữ liệu' + '</td>';
	// 	info_data += '</tr>';
	// 	$('#guestsBody').append(info_data);

	// 	$(".overlay").removeClass("show");
	// 	$(".overlay").addClass("hide");
	// 	$("#guests").css("opacity", "1")

	// });
}

function handleEventForAddButton() {
	$(".add-guest").on('click', function () {
		resetGuestFormValidation();
		$("#guestInfo").find("#action").val('add');
		$("#save-guest").text("Lưu");
		$(".modal-title").text("Thêm khách hàng");
		resetGuestFormValidation();
	});
}

function handleEventForSaveButton() {
	$("#save-guest").on('click', function () {
		saveGuest();
	});
}

function handleEventForConfirmDeleteButton() {
	$(".delete-guest").on("click", function(){
		$("#resultDelete").text("");
	});
	$("#confirm-del").on('click', function () {
		$("#modal-del .overlay").removeClass('hide').addClass("show");
		$.ajax({
			type: 'POST',
			url: '/ajax/guest/delete',
			dataType: 'json',
			data: {
				'id': $("#deleteGuestId").val(),
			},
			success: function (data, textStatus, xhr) {
				switch (xhr.status) {
					case 201: {
						$("#modal-del .overlay").removeClass('show').addClass("hide");
						$("#modal-del").modal('hide');
						reloadGuestList();
						break;
					}
					default:
						break;
				}
			},
			error: function (xhr, textStatus, errorThrown) {
				switch (xhr.status) {
					case 500: {
						$("#resultDelete").removeClass("hidden");
						$("#resultDelete").text("Bạn không thể xóa khách hàng này!");
						$("#modal-del .overlay").removeClass('show').addClass("hide");
						break;
					}
					case 422: {
						$("#resultDelete").removeClass("hidden");
						$("#resultDelete").text("Bạn không có quyền truy cập chức năng này!");
						$("#modal-del .overlay").removeClass('show').addClass("hide");
						break;
					}
				}


			}

		});
	});
}

function handleEventForDeleteButton() {
	$("#result").removeClass();
	$("#result").text('');
	$("#guests").on('click', '.delete-guest', function () {
		var guestId = $(this).data("guest-id");
		$("#deleteGuestId").val(guestId);
	});

}

function handleEventForPaginationButton() {
	$('.page-item').on('click', function (e) {
		var pageIndex = Number($(this).find('.page-link').data('page'));
		if ($(this).hasClass("disabled")) {
			e.preventDefault()
			return false;
		}
		$(".overlay").removeClass("hide");
		$(".overlay").addClass("show");
		// get page index from button

		// set page index to hidden input
		$('#currentPageIndex').val(pageIndex);
		var searchBy = $("#searchBy").val();
		var pageSize = $(".entries").val();
		var sortBy = $("#currentSortBy").val();
		var sortDir = $("#currentSortDir").val();

		$.ajax({
			type: 'POST',
			url: 'http://localhost:8008/ajax/guest/reload',
			data: {
				'pageIndex': pageIndex,
				searchBy: searchBy,
				pageSize: pageSize,
				sortBy: sortBy,
				sortDir: sortDir
			},
			dataType: 'json',
		}).done(function (result) {
			$("#guests tr:has(td)").remove();
			var guestsDisplay = 0;

			// set list guests
			var guestRows = '';
			$.each(result.guests, function (key, value) {
				var guestRow = $('#guestRowTemplate').html();
				guestRow = guestRow.replace(/\[guest_id\]/g, value.id);
				guestRow = guestRow.replace(/\[guest_name\]/g, value.name);
				guestRow = guestRow.replace(/\[guest_idCardNo\]/g, value.idCardNo);
				guestRow = guestRow.replace(/\[guest_phoneNumber\]/g, value.phoneNumber);
				guestRow = guestRow.replace(/\[guest_yearOfBirth\]/g, value.yearOfBirth);
				guestRow = guestRow.replace(/\[guest_address\]/g, value.address);
				guestsDisplay++;
				guestRows += guestRow;
			});

			// if we don't need to create pagination
			$("#pagination").remove();
			if (!result.paginationInfo.needed) return;

			// create or re-render pagination element
			var paginationElement = '';

			paginationElement += '<nav id="pagination">';
			paginationElement += '<ul class="pagination m-0">';


			if (pageIndex === 0) {
				paginationElement += '<li class="page-item disabled"><a href="#" class="page-link" data-page="-1">Trước</a></li>';
			}
			else {
				paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex - 1) + '">Trước</a></li>';
			}

			for (var i = 0; i < result.paginationInfo.lastpage; i++) {
				if (i === Number(result.paginationInfo.page)) {
					paginationElement += '<li class="page-item active"><a class="page-link" href="#" data-page="' + i + '"">' + Number(i + 1) + '</a></li>';
				} else {
					paginationElement += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '"">' + Number(i + 1) + '</a></li>';
				}
			}

			if (pageIndex === Number(result.paginationInfo.lastpage) - 1) {
				paginationElement += '<li class="page-item disabled"><a href="#" class="page-link" data-page="' + (pageIndex) + '">Tiếp</a></li>';
			}
			else {
				paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex + 1) + '">Tiếp</a></li>';
			}
			paginationElement += '</ul>';
			paginationElement += '</nav>';
			$("#pagination-container").append(paginationElement);
			$('#guestsBody').append(guestRows);
			$('.paging').html(guestsDisplay);
			$('.sum-paging').html(result.counts);
			handleEventForPaginationButton();
			handleEventForEditButton();
			handleEventForDeleteButton();
			$(".overlay").removeClass("show");
			$(".overlay").addClass("hide");
		});
	})
}

function handleEventForEditButton() {

	$("#guests").on('click', '.edit-guest', function () {
		resetGuestFormValidation();

		$("#modal-add-update").find(".overlay").addClass("show").removeClass("hide")
		$("#save-guest").text("Cập nhật");
		$(".modal-title").text("Thay đổi thông tin Khách Hàng");
		var guestId = $(this).data("guest-id");

		$("#guestInfo").find("#action").val('update');
		$("#guestInfo").find("#guestId").val(guestId);

		$.ajax({
			type: 'POST',
			url: '/ajax/guest/getGuestById',
			dataType: 'json',
			data: {
				'id': guestId
			},
			success: function (data, textStatus, xhr) {
				$("#guestInfo").find("input[name= 'name']").val(data[0].name);
				$("#guestInfo").find("select[name = 'gender']").val(data[0].gender);
				$("#guestInfo").find("input[name = 'idCardNo']").val(data[0].idCardNo);
				$("#guestInfo").find("input[name = 'phoneNumber']").val(data[0].phoneNumber);
				$("#guestInfo").find("input[name = 'yearOfBirth']").val(data[0].yearOfBirth);
				$("#guestInfo").find("input[name = 'idCardIssuePlace']").val(data[0].idCardIssuePlace);
				$("#guestInfo").find("textarea[name = 'address']").val(data[0].address);
				$("#guestInfo").find("input[name = 'idCardExpiryDate']").val(data[0].idCardExpiryDate);
				$("#guestInfo").find("input[name = 'idCardIssueDate']").val(data[0].idCardIssueDate);

			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		}).done(function () {
			$("#modal-add-update").find(".overlay").addClass("hide").removeClass("show")
		});



	});
}

function resetGuestFormValidation() {
	$("input").removeClass("has-error");
	$("input").removeClass("has-success");
	$("select").removeClass("has-error");
	$("select").removeClass("has-success");


	$(".has-error").remove()
	$(".has-success").remove()
	$(".success").text("")
	$(".error").text("")


	$(".error").css("display", "none");

	$(".overlay").removeClass("show");
	$(".overlay").addClass("hide");

	$("#guestInfo")[0].reset();
}
