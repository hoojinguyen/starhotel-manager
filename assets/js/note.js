$(document).ready(function () {
	validateGuestForm();
	initGuest();
});

function searchGuests() {
	var searchBy = $("#searchBy").val();
	var pageSize = $(".entries").val();
	var pageIndex = $("#currentPageIndex").val();
	var sortBy = $("#currentSortBy").val();
	var sortDir = $("#currentSortDir").val();

	$.ajax({
		type: 'POST',
		url: 'http://localhost:8009/admin/guest/list',
		dataType: 'json',
		data: {
			searchBy: searchBy,
			pageSize: pageSize,
			pageIndex: pageIndex,
			sortBy: sortBy,
			sortDir: sortDir
		}
	}).done(function (result) {
		var rowData = '';
		$("tr:has(td)").remove();
		$.each(result, function (key, value) {
			rowData += '<tr class="text-center">';
			rowData += '<td>' + value.name + '</td>';
			rowData += '<td>' + value.idCardNo + '</td>';
			rowData += '<td>' + value.phoneNumber + '</td>';
			rowData += '<td>' + value.yearOfBirth + '</td>';
			rowData += '<td><button class="glyphicon glyphicon-edit btn btn-warning edit-guest m-r-10"  data-toggle="modal" data-target="#modal-add-update" data-guest-id="' + value.id + '" ></button>';
			rowData += '<button class="glyphicon glyphicon-trash btn btn-danger delete-guest" data-toggle="modal" data-target="#modal-del"  data-guest-id="' + value.id + '"></button></td>';
			rowData += '</tr>';
		});
		$('#guests').append(rowData);

	}).fail(function () {
		$("tr:has(td)").remove();
		var emptyRow = '';
		emptyRow += '<tr>';
		emptyRow += '<td colspan="5" class="text-center">' + 'Không tìm thấy dữ liệu' + '</td>';
		emptyRow += '</tr>';
		$('#guests').append(emptyRow);
	});
};

function validateGuestForm() {
	let guestForm = $("#guestInfo");

	if ($("#guestInfo").length) {
		let validator = guestForm.validate({
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
			}
		});

		return validator.valid();
	}
	return true;
}

function convertDateFormat(date) {
	var date1 = new Date(date);
	var radix = 10;
	var dateConverted = '';
	if (parseInt(date1.getMonth(), radix) < 9) {
		dateConverted = date1.getFullYear() + '-' + '0' + (parseInt(date1.getMonth() + 1, radix)).toString() + '-' + date1.getDate();
	}
	else {
		dateConverted = date1.getFullYear() + '-' + (parseInt(date1.getMonth() + 1, radix)).toString() + '-' + date1.getDate();
	}

	return dateConverted;
}

function saveGuest() {
	$(".overlay").removeClass("hide");
	$(".overlay").addClass("show");
	var actionTexting = ($("#action").val() == 'add') ? "Đang lưu..." : "Đang cập nhật...";

	$("#save").text(actionTexting);

	var actionText = ($("#action").val() == 'add') ? "Lưu" : "Cập Nhật";

	if (!validateGuestForm()) {

		$("#save").text(actionText);
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
		return 0;
	}

	$.ajax({
		type: 'POST',
		url: 'http://localhost:8009/ajax/guest/save',
		data: $("#guestInfo").serialize(),
		dataType: 'json',
		error: function (xhr, textStatus, errorThrown) {
			switch (xhr.status) {
				case 422:
					$("#result").removeClass();
					$("#result").addClass("error");
					$("#result").text("Dữ liệu không hợp lệ !");
					break;
				case 500:
					$("#result").removeClass();
					$("#result").addClass("error");
					$("#result").text("Hệ thống đang bận, vui lòng thử lại sau !");
					break;
				case 409: {
					$("#result").removeClass();
					$("#result").addClass("error");
					$("input[idCardNo]").removeClass("has-success");
					$("input[name = 'idCardNo']").addClass("has-error");
					$("#result").text("CMND đã tồn tại !");
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
					$("#result").addClass("success");
					$("#result").text("Lưu thành công !");
					searchGuests();
					break;
				}
				default:
					break;
			}
		},
	}).always(function () {
		$("#save").text(actionText);
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
	})

}

function initGuest() {

	$("#searchBy").change(function () {
		searchGuests();
	});

	$(".entries").change(function () {
		searchGuests();
	});

	$(".page-link").on('click', function (event) {
		var selectedIndex = $(this).data('page');
		var currentIndex = $('#currentPageIndex').val();
		if (selectedIndex === -1 && currentIndex > 1) {
			selectedIndex = parseInt(currentIndex, 10) - 1;
		}
		else if (selectedIndex === 0) {
			selectedIndex = parseInt(currentIndex, 10) + 1;
		}
		else if (selectedIndex === -1 && currentIndex === 1) {
			selectedIndex = 1;
		}
		$('#currentPageIndex').val(selectedIndex);

		// remove class active
		$(".page-item").removeClass("active");
		// add class active with index selected
		$(this).parent().addClass("active");

		searchGuests();
	});

	$(".sort-desc").on('click', function (event) {

		$('#currentSortBy').val($(this).data('sortby'));
		$('#currentSortDir').val($(this).data('sortdir'));

		$(".sort-desc").removeClass("active-sort");
		$(".sort-asc").removeClass("active-sort");
		$(this).addClass("active-sort");

		searchGuests();
	});

	$(".sort-asc").on('click', function (event) {

		$('#currentSortBy').val($(this).data('sortby'));
		$('#currentSortDir').val($(this).data('sortdir'));

		$(".sort-desc").removeClass("active-sort");
		$(".sort-asc").removeClass("active-sort");
		$(this).addClass("active-sort");
		searchGuests();
	});
	
	$(".edit-guest").on('click', function () {

		$("#save").text("Cập nhật");
		$(".modal-title").text("Thay đổi thông tin khách hàng");
		var guestId = $(this).data("guest-id");

		$("#guestInfo").find("#action").val('update');
		$("#guestInfo").find("#guestId").val(guestId);

		$.ajax({
			type: 'POST',
			url: 'http://localhost:8009/ajax/guest/getGuestById',
			dataType: 'json',
			data: {
				'id': guestId,
			},
			success: function (data, textStatus, xhr) {
				$("#guestInfo").find("input[name = 'name']").val(data.name);
				$("#guestInfo").find("input[name = 'gender']").val(data.gender);
				$("#guestInfo").find("input[name = 'idCardNo']").val(data.idCardNo);
				$("#guestInfo").find("input[name = 'phoneNumber']").val(data.phoneNumber);
				$("#guestInfo").find("input[name = 'yearOfBirth']").val(data.yearOfBirth);
				$("#guestInfo").find("input[name = 'idCardIssuePlace']").val(data.idCardIssuePlace);
				if (data.idCardExpiryDate) {
					var expiryDate = convertDateFormat(data.idCardExpiryDate.date);
					$("#guestInfo").find("input[name = 'idCardExpiryDate']").val(expiryDate);
				}
				if (data.idCardIssueDate) {
					var issueDate = convertDateFormat(data.idCardIssueDate.date);
					$("#guestInfo").find("input[name = 'idCardIssueDate']").val(issueDate);
				}
				$("#guestInfo").find("input[name = 'address']").val(data.address);
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});
	});

	$('.delete-guest').on('click', function () {
		var guestId = $(this).data("guest-id");
		$("#deleteGuestId").val(guestId);
	});

	$("#confirm-del").on('click', function () {
		$.ajax({
			type: 'POST',
			url: 'http://localhost:8009/ajax/guest/delete',
			dataType: 'json',
			data: {
				'id': $("#deleteGuestId").val()
			}
		});
		$("#modal-del").modal('hide');
		searchGuests();
	});

	$(".add-guest").on('click', function () {
		$("#guestInfo").find("#action").val('add');
		$("#save").text("Lưu");
		$(".modal-title").text("Thêm khách hàng");
		$("#guestInfo")[0].reset();
	});

	$("#save").on('click', function () {
		saveGuest();
	});

}
