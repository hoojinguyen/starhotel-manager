$(document).ready(function () {
	validateUserForm();
	initUser();
});

function searchUsers() {
	var searchBy = $("#userSearchBy").val();
	var pageSize = $(".user-entries").val();
	var pageIndex = $("#userCurrentPageIndex").val();
	var sortBy = $("#userCurrentSortBy").val();
	var sortDir = $("#userCurrentSortDir").val();
	$("#users tr:has(td)").remove();

	$.ajax({
		type: 'POST',
		url: '/admin/user',
		dataType: 'json',
		data: {
			searchBy: searchBy,
			pageSize: pageSize,
			pageIndex: pageIndex,
			sortBy: sortBy,
			sortDir: sortDir
		}
	}).done(function (result) {
		$("#users tr:has(td)").remove();
		var rowsData = '';

		$.each(result, function (key, value) {
			// rowData += '<tr>';
			// rowData += '<td>' + value.username + '</td>';
			// rowData += '<td>' + convertDateTimeToString(value.lastLogin.date) + '</td>';
			// rowData += '<td>' + convertDateTimeToString(value.createdAt.date) + '</td>';
			// rowData += '<td>' + convertDateTimeToString(value.updatedAt.date) + '</td>';
			// rowData += '<td><button class="glyphicon glyphicon-edit btn btn-warning edit-user m-r-10" data-toggle="modal" data-target="#user-edit-modal" data-user-id="' + value.id + '" ></button>';
			// rowData += '<button class="glyphicon glyphicon-trash btn btn-danger delete-user" data-toggle="modal" data-target="#user-delete-modal"  data-user-id="' + value.id + '"></button></td>';
			// rowData += '</tr>';

			var rowData = $('#userRowTemplate').html();
			rowData = rowData.replace(/\[user_username\]/g, value.username);
			rowData = rowData.replace(/\[user_lastLogin\]/g, convertDateTimeToString(value.lastLogin.date));
			rowData = rowData.replace(/\[user_createdAt\]/g, convertDateTimeToString(value.createdAt.date));
			rowData = rowData.replace(/\[user_updatedAt\]/g, convertDateTimeToString(value.updatedAt.date));
			rowData = rowData.replace(/\[user_id\]/g, value.id);
			rowsData += rowData;
		});
		$('#users tbody').append(rowsData);
		handleAddUserEvent();
		handleDeleteUserEvent();
		handleEditUserEvent();
	}).fail(function (result) {
		$("tr:has(td)").remove();
		var emptyRow = '';
		emptyRow += '<tr>';
		emptyRow += '<td colspan="5" class="text-center">' + 'Không tìm thấy dữ liệu' + '</td>';
		emptyRow += '</tr>';
		$('#users').append(emptyRow);
	});
};

function reloadUserList() {

	$.ajax({
		type: 'POST',
		url: '/ajax/customer/reload',
		data: '',
		dataType: 'json',
	}).done(function (result) {
		$("#customers tr:has(td)").remove();
		var customerRows = '';
		$.each(result, function (key, value) {

			var customerRow = $('#customerRowTemplate').html();
			customerRow = customerRow.replace(/\[customer_name\]/g, value.name);
			customerRow = customerRow.replace(/\[customer_code\]/g, value.code);
			customerRow = customerRow.replace(/\[customer_id\]/g, value.id);
			customerRow = customerRow.replace(/\[customer_active\]/g, (value.active) ?
				'<span class="label label-success">Đang kích hoạt</span>' :
				'<span class="label label-danger">Vô hiệu hóa</span>');
			customerRows += customerRow;
		});
		$('#customersBody').append(customerRows);
		handleEventForDeleteButton();
	});

}

function validateUserForm() {
	let form = $("#userInfo");

	if ($("#userInfo").length) {
		let validator = form.validate({
			errorClass: "has-error",
			validClass: "has-success",
			rules: {
				username: {
					required: true,
					minlength: 4,
				},
				password: {
					required: true,
				},
			}
		});

		return validator.valid();
	}
	return true;
}

function saveUser() {

	$(".overlay").removeClass("hide");
	$(".overlay").addClass("show");
	var actionTexting = ($("#action").val() == 'add') ? "Đang lưu..." : "Đang cập nhật...";

	$("#saveUser").text(actionTexting);

	var actionText = ($("#action").val() == 'add') ? "Lưu" : "Cập Nhật";

	if (!validateUserForm()) {

		$("#saveUser").text(actionText);
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
		return 0;
	}

	$.ajax({
		type: 'POST',
		url: '/ajax/user/add',
		data: $("#userInfo").serialize(),
		dataType: 'json',
		error: function (xhr, textStatus, errorThrown) {
			switch (xhr.status) {
				case 422:
					$("#userFormResult").removeClass();
					$("#userFormResult").addClass("text-red");
					$("#userFormResult").text("Dữ liệu không hợp lệ !");
					break;
				case 500:
					$("#userFormResult").removeClass();
					$("#userFormResult").addClass("text-red");
					$("#userFormResult").text("Hệ thống đang bận, vui lòng thử lại sau !");
					break;
				case 409: {
					$("#userFormResult").removeClass();
					$("#userFormResult").addClass("text-red");
					$("#userFormResult").text("Tài khoản đã tồn tại !");
					$("#username").parent().removeClass("has-success");
					$("#username").parent().addClass("has-error");
					break;
				}
				default:
					break;
			}
		},
		success: function (data, textStatus, xhr) {
			switch (xhr.status) {
				case 201: {
					$("#userFormResult").removeClass();
					$("#userFormResult").addClass("text-green");
					$("#username").parent().removeClass("has-error");
					$("#username").parent().addClass("has-success");
					$("#userFormResult").text("Lưu thành công !");
					searchUsers();
					break;
				}
				default:
					break;
			}
		},
	}).always(function () {
		$("#username").removeClass("has-success");
		$("#saveUser").text(actionText);
		$(".overlay").removeClass("show");
		$(".overlay").addClass("hide");
	});
}

function initUser() {

	$("#userSearchBy").change(function () {
		searchUsers();
	});

	$(".user-entries").change(function () {
		searchUsers();
	});

	$(".user-page-link").on('click', function (event) {
		var selectedIndex = $(this).data('page');
		var currentIndex = $('#userCurrentPageIndex').val();
		if (selectedIndex == -1 && currentIndex > 1) {
			selectedIndex = parseInt(currentIndex, 10) - 1;
		}
		else if (selectedIndex == 0) {
			selectedIndex = parseInt(currentIndex, 10) + 1;
		}
		else if (selectedIndex == -1 && currentIndex == 1) {
			selectedIndex = 1;
		}
		$('#userCurrentPageIndex').val(selectedIndex);

		// remove class active
		$(".page-item").removeClass("active");
		// add class active with index selected
		$(this).parent().addClass("active");

		searchUsers();

	});

	$(".sort-desc-user").on('click', function (event) {

		$('#userCurrentSortBy').val($(this).data('sortby'));
		$('#userCurrentSortDir').val($(this).data('sortdir'));

		$(".sort-desc-user").removeClass("active-sort");
		$(".sort-asc-user").removeClass("active-sort");
		$(this).addClass("active-sort");

		searchUsers();
	});

	$(".sort-asc-user").on('click', function (event) {

		$('#userCurrentSortBy').val($(this).data('sortby'));
		$('#userCurrentSortDir').val($(this).data('sortdir'));

		$(".sort-desc-user").removeClass("active-sort");
		$(".sort-asc-user").removeClass("active-sort");
		$(this).addClass("active-sort");
		searchUsers();
	});

	// Generate Password
	$("#generatePassword").on('click', function () {
		$("#password").val(Math.random().toString(36).substring(7));
	});

	handleEditUserEvent();
	handleAddUserEvent();
	handleDeleteUserEvent();

	$("#confirmDeleteUser").on('click', function () {
		$.ajax({
			type: 'POST',
			url: '/ajax/user/delete',
			dataType: 'json',
			data: {
				'id': $("#deleteUserId").val()
			}
		}).always(function () {
			searchUsers();
		});
		$("#user-delete-modal").modal('hide');
	});
	// End delete user

	$("#saveUser").on('click', function () {
		saveUser();
	});
}

function handleAddUserEvent() {
	$(".add-user").on('click', function () {
		$("#username").parent().removeClass("has-success");
		$("#username").parent().removeClass("has-error");
		$("#userFormResult").removeClass();
		$("#userFormResult").text("");
		$("#userInfo").find("#action").val('add');
		$("#saveUser").text("Lưu");
		$(".modal-title").text("Thêm khách hàng");
		$("#userInfo")[0].reset();
		$("#username").attr("disabled", false);
	});
}


function handleDeleteUserEvent() {
	$('.delete-user').on('click', function () {
		var userId = $(this).data("user-id");
		$("#deleteUserId").val(userId);
	});
}

function handleEditUserEvent() {
	$(".edit-user").on('click', function () {
		$("#username").parent().removeClass("has-success");
		$("#username").parent().removeClass("has-error");
		$("#userFormResult").removeClass();
		$("#userFormResult").text("");
		$("#userInfo")[0].reset();
		$("#saveUser").text("Cập nhật");
		$(".modal-title").text("Đổi mật khẩu khách hàng");
		var userId = $(this).data("user-id");
		$("#userInfo").find("#action").val('update');
		$("#userInfo").find("#userId").val(userId);
		$("#username").attr("disabled", true);

		$.ajax({
			type: 'POST',
			url: '/ajax/user/getUserById',
			dataType: 'json',
			data: {
				'id': userId,
			},
			success: function (data, textStatus, xhr) {
				$("#userInfo").find("input[name = 'username']").val(data.username);
				$("#userInfo").find("input[name = 'password']").val(data.password);
			},
			error: function (xhr, textStatus, errorThrown) {
				console.log(errorThrown);
			}
		});
	});
}