// $(document).ready(function () {
// 	validateCustomerForm();

// 	handleEventForEditButton();

// 	handleEventForDeleteButton();

// 	handleEventForConfirmDeleteButton();

// 	handleEventForAddButton();

// 	handleEventForSaveButton();

// 	handleEventForPaginationButton();
// });


// function validateCustomerForm() {
// 	var customerForm = $('#customerInfo');

// 	if ($("#customerInfo").length) {
// 		var validator = customerForm.validate({
// 			errorClass: "has-error",
// 			validClass: "has-success",
// 			rules: {
// 				name: {
// 					required: true,
// 					minlength: 6,
// 				},
// 				code: {
// 					required: true,
// 					minlength: 6,
// 				},
// 				active: {
// 					required: true,
// 				}
// 			},
// 		});
// 		return validator.valid();
// 	}
// 	return true;
// }

// function saveCustomer() {
// 	var actionText;
// 	var data = $('#customerInfo').serialize();

// 	if ($('#action').val() == 'add') {
// 		actionText = "Lưu";
// 	}
// 	else {
// 		actionText = "Cập nhật";
// 		var customerId = $('#customerId').val();
// 		data += '&id=' + customerId;
// 	}

// 	$(".overlay").removeClass("hide");
// 	$(".overlay").addClass("show");
// 	$(".overlay").removeClass("hide");
// 	$(".overlay").addClass("show");

// 	if (!validateCustomerForm()) {
// 		$("#save").text("Lưu");
// 		$(".overlay").removeClass("show");
// 		$(".overlay").addClass("hide");
// 		return 0;
// 	}

// 	$.ajax({
// 		type: 'POST',
// 		url: ($('#action').val() == 'add') ? 'http://localhost:8009/ajax/customer/add' :
// 			'http://localhost:8009/ajax/customer/update',
// 		data: data,
// 		dataType: 'json',
// 		error: function (xhr, textStatus, errorThrown) {
// 			switch (xhr.status) {
// 				case 422:
// 					$("#result").removeClass();
// 					$("#result").addClass("error");
// 					$("#result").text("Dữ liệu không hợp lệ!");
// 					break;
// 				case 500:
// 					$("#result").removeClass();
// 					$("#result").addClass("error");
// 					$("#result").text("Hệ thống đang bận, vui lòng thử lại sau!");
// 					break;
// 				case 409: {
// 					$("#result").removeClass();
// 					$("#result").addClass("error");
// 					$("input[id]").removeClass("has-success");
// 					$("#customerInfo").find("input[name = 'id']").addClass("has-error");
// 					$("#result").text("Đã tồn tại!");
// 					break;
// 				}
// 				default:
// 					break;
// 			}
// 		},
// 		success: function (data, textStatus, xhr) {
// 			switch (xhr.status) {
// 				case 201: {
// 					$("#result").removeClass();
// 					$("#result").addClass("success");
// 					$("#result").text("Lưu thành công!");
// 					reloadCustomerList();
// 					break;
// 				}
// 				default:
// 					break;
// 			}
// 		},
// 	})
// 		.always(function () {
// 			$("#save").text(actionText);
// 			$(".overlay").removeClass("show");
// 			$(".overlay").addClass("hide");
// 		});

// }

// function reloadCustomerList() {
// 	// get page index from button
// 	var pageIndex = Number($(this).find('.page-link').data('page'));

// 	// set page index to hidden input
// 	$('#currentPageIndex').val(pageIndex);

// 	$.ajax({
// 		type: 'POST',
// 		url: 'http://localhost:8009/ajax/customer/reload',
// 		data: {
// 			'pageIndex': pageIndex
// 		},
// 		dataType: 'json',
// 	}).done(function (result) {
// 		$("#customers tr:has(td)").remove();

// 		// set list customers
// 		var customerRows = '';
// 		$.each(result.customers, function (key, value) {
// 			var customerRow = $('#customerRowTemplate').html();
// 			customerRow = customerRow.replace(/\[customer_name\]/g, value.name);
// 			customerRow = customerRow.replace(/\[customer_code\]/g, value.code);
// 			customerRow = customerRow.replace(/\[customer_id\]/g, value.id);
// 			customerRow = customerRow.replace(/\[customer_active\]/g, (value.active) ?
// 				'<span class="label label-success">Đang kích hoạt</span>' :
// 				'<span class="label label-danger">Vô hiệu hóa</span>');
			
// 			customerRows += customerRow;
// 		});

// 		// if we don't need to create pagination
// 		$("#pagination").remove();
// 		if (!result.paginationInfo.needed) return;

// 		// create or re-render pagination element
// 		var paginationElement = '';

// 		paginationElement += '<nav id="pagination">';
// 		paginationElement += '<ul class="pagination m-0">';
		

// 		if (pageIndex === 0) {
// 			paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="0">Trước</a></li>';
// 		}
// 		else{
// 			paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex - 1) + '">Trước</a></li>';
// 		}

// 		for (var i = 0; i < result.paginationInfo.lastpage; i++) {
// 			if (i === result.paginationInfo.page) {
// 				paginationElement += '<li class="page-item active"><a class="page-link" href="#" data-page="' + i + '"">' + i + '</a></li>';
// 			} else {
// 				paginationElement += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '"">' + i + '</a></li>';
// 			}
// 		}

// 		if (pageIndex === result.paginationInfo.lastpage - 1) {
// 			paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex) + '">Tiếp</a></li>';
// 		}
// 		else {
// 			paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex + 1) + '">Tiếp</a></li>';
// 		}
// 		paginationElement += '</ul>';
// 		paginationElement += '</nav>';
// 		$("#pagination-container").append(paginationElement);
// 		$('#customersBody').append(customerRows);
// 		handleEventForPaginationButton();
// 		handleEventForEditButton();
// 		handleEventForDeleteButton();
// 	});
// }

// function handleEventForAddButton() {
// 	$(".add-customer").on('click', function () {
// 		$("#customerInfo").find("#action").val('add');
// 		$("#save").text("Lưu");
// 		$("#customerInfo").find("input[name= 'name']").val();
// 		$("#customerInfo").find("input[name = 'code']").val();
// 		$("#input-active").prop("checked", false);
// 		$("#input-inactive").prop("checked", false);
// 		$(".modal-title").text("Thêm customer");
// 		$("#customerInfo")[0].reset();
// 	});
// }

// function handleEventForSaveButton() {
// 	$("#save").on('click', function () {
// 		if ($(customerInfo).find("#action").val() == "add") {
// 			saveCustomer();
// 		}
// 		else {
// 			updateCustomer();
// 		}
// 	});
// }

// function handleEventForConfirmDeleteButton() {
// 	$("#confirm-del").on('click', function () {
// 		$.ajax({
// 			type: 'POST',
// 			url: 'http://localhost:8009/ajax/customer/delete',
// 			dataType: 'json',
// 			data: {
// 				'id': $("#deleteCustomerId").val(),
// 			},
// 			success: function (data, textStatus, xhr) {
// 				switch (xhr.status) {
// 					case 201: {
// 						$("#result").removeClass();
// 						$("#result").addClass("success");
// 						$("#result").text("Xóa thành công!");
// 						break;
// 					}
// 					default:
// 						break;
// 				}
// 				reloadCustomerList();
// 			}
// 		});
// 		$("#modal-del").modal('hide');
// 	});
// }

// function handleEventForDeleteButton() {
// 	$("#result").removeClass();
// 	$("#result").text('');
// 	$(".delete-customer").on('click', function () {
// 		var customerId = $(this).data('customer-id');
// 		$("#deleteCustomerId").val(customerId);
// 	});
// }

// function handleEventForPaginationButton() {
// 	$('.page-item').on('click', function () {

// 		// get page index from button
// 		var pageIndex = Number($(this).find('.page-link').data('page'));

// 		// set page index to hidden input
// 		$('#currentPageIndex').val(pageIndex);

// 		$.ajax({
// 			type: 'POST',
// 			url: 'http://localhost:8009/ajax/customer/reload',
// 			data: {
// 				'pageIndex': pageIndex
// 			},
// 			dataType: 'json',
// 		}).done(function (result) {
// 			$("#customers tr:has(td)").remove();

// 			// set list customers
// 			var customerRows = '';
// 			$.each(result.customers, function (key, value) {
// 				var customerRow = $('#customerRowTemplate').html();
// 				customerRow = customerRow.replace(/\[customer_name\]/g, value.name);
// 				customerRow = customerRow.replace(/\[customer_code\]/g, value.code);
// 				customerRow = customerRow.replace(/\[customer_id\]/g, value.id);
// 				customerRow = customerRow.replace(/\[customer_active\]/g, (value.active) ?
// 					'<span class="label label-success">Đang kích hoạt</span>' :
// 					'<span class="label label-danger">Vô hiệu hóa</span>');
// 				customerRows += customerRow;
// 			});

// 			// if we don't need to create pagination
// 			$("#pagination").remove();
// 			if (!result.paginationInfo.needed) return;

// 			// create or re-render pagination element
// 			var paginationElement = '';

// 			paginationElement += '<nav id="pagination">';
// 			paginationElement += '<ul class="pagination m-0">';
			

// 			if (pageIndex === 0) {
// 				paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="0">Trước</a></li>';
// 			}
// 			else{
// 				paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex - 1) + '">Trước</a></li>';
// 			}

// 			for (var i = 0; i < result.paginationInfo.lastpage; i++) {
// 				if (i === result.paginationInfo.page) {
// 					paginationElement += '<li class="page-item active"><a class="page-link" href="#" data-page="' + i + '"">' + i + '</a></li>';
// 				} else {
// 					paginationElement += '<li class="page-item"><a class="page-link" href="#" data-page="' + i + '"">' + i + '</a></li>';
// 				}
// 			}

// 			if (pageIndex === result.paginationInfo.lastpage - 1) {
// 				paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex) + '">Tiếp</a></li>';
// 			}
// 			else {
// 				paginationElement += '<li class="page-item"><a href="#" class="page-link" data-page="' + (pageIndex + 1) + '">Tiếp</a></li>';
// 			}
// 			paginationElement += '</ul>';
// 			paginationElement += '</nav>';
// 			$("#pagination-container").append(paginationElement);
// 			$('#customersBody').append(customerRows);
// 			handleEventForPaginationButton();
// 			handleEventForEditButton();
// 			handleEventForDeleteButton();
// 		});
// 	})
// }

// function handleEventForEditButton() {
// 	$('.edit-customer').on('click', function () {
// 		$("#save").text("Cập nhật");
// 		$(".modal-title").text("Thay đổi thông tin Customer");

// 		var customerId = $(this).data("customer-id");

// 		$("#customerInfo").find("#action").val('update');
// 		$("#customerInfo").find("#customerId").val(customerId);

// 		$.ajax({
// 			type: 'POST',
// 			url: 'http://localhost:8009/ajax/customer/getCustomerById',
// 			dataType: 'json',
// 			data: {
// 				'id': customerId
// 			},
// 			success: function (data, textStatus, xhr) {
// 				$("#customerInfo").find("input[name= 'name']").val(data.name);
// 				$("#customerInfo").find("input[name = 'code']").val(data.code);
// 				(data.active) ?
// 					$("#input-active").prop("checked", true) :
// 					$("#input-inactive").prop("checked", true);
// 			},
// 			error: function (xhr, textStatus, errorThrown) {
// 				console.log(errorThrown);
// 			}
// 		});
// 	});
// }