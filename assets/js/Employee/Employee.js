function validateEmployeeForm() {
    let employeeForm = $("#employeeInfo");

    if ($("#employeeInfo").length) {
        let validator = employeeForm.validate({
            errorClass: "has-error",
            validClass: "has-success",
            rules: {
                nameEmployee: {
                    required: true,
                    minlength: 2
                },
                codeEmployee: {
                    required: true,
                    minlength: 2
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

function loadEmployee() {
    var data = "";
    $.ajax({
        type: 'POST',
        url: '/ajax/employee/loademployee',
        data,
        dataType: 'json',
    })
        .done(function (result) {

            console.log(result);
            
            var info_data = '';
            $("#employees tr:has(td)").remove();

            $.each(result, function (key, value) {
                
                if (value.dayToWork) {
                    var dayToWork = convertDateFormat(value.dayToWork.date);
                }
                info_data += '<tr>';
                info_data += '<td>' + value.code + '</td>';
                info_data += '<td>' + value.name + '</td>';
                info_data += '<td>' + value.gender + '</td>';
                info_data += '<td>' + value.phoneNumber + '</td>';
                info_data += '<td>' + value.idCardNo + '</td>';
                info_data += '<td>' + value.address + '</td>';
                info_data += '<td>' + value.yearOfBirth + '</td>';
                info_data += '<td>' + dayToWork + '</td>';
                info_data += '<td>' + value.position + '</td>';
                info_data += '<td>' + value.shift + '</td>';
                info_data += '<td>  <button type="button" class="btn btn-warning btn-sm edit-button-employee" data-employee-id="' + value.id + '"  data-toggle="modal" data-target="#modal-addEmployee-updateEmployee"><span class="fa fa-wrench"></span></button>  <button type="button" class="btn btn-danger btn-sm del-button-employee"   data-employee-id="' + value.id + '"   data-employee-name="' + value.name + '"  data-toggle="modal" data-target="#modal-del-employee"><span class="fa fa-trash-o"></span></button> </td>'
                info_data += '</tr>';
            });
            $('#employees').append(info_data);

        });
}

function addEmployee() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");
    $("#add-or-update-employee").text("Đang lưu ...");

    if (!validateEmployeeForm()) {
        $("#add-or-update-employee").text("Lưu");
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#employeeInfo").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/employee/saveemployee',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            switch (xhr.status) {
                case 422:
                    $("#resultEmployee").removeClass("success");
                    $("#resultEmployee").addClass("error");
                    $("#resultEmployee").text("Dữ liệu không hợp lệ !");
                    break;
                case 500:
                    $("#resultEmployee").removeClass("success");
                    $("#resultEmployee").addClass("error");
                    $("#resultEmployee").text("Hệ thống đang bận, vui lòng thử lại sau !");
                    break;
                default:
                    break;
            }
        },
        success: function (data, textStatus, xhr) {
            $("#resultEmployee").removeClass();
            $("#resultEmployee").addClass("success");
            $("#resultEmployee").text("Lưu thành công !");
            $("#add-or-update-employee").text("Lưu");
            setTimeout(function () { $("#resultEmployee").text(""); }, 1000);
            setTimeout(function () { $("#modal-addEmployee-updateEmployee").modal('hide'); }, 1000);
            loadEmployee();
        },
    })

}

function updateEmployee() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");
    $("#add-or-update-employee").text("Đang cập nhật ...");
    if (!validateEmployeeForm()) {
        $("#add-or-update-employee").text("Cập nhật");
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }    
    var data = $("#employeeInfo").serialize();
    data += '&id=' + id;    
    $.ajax({
        type: 'POST',
        url: '/ajax/employee/saveemployee',
        data,
        dataType: 'json',
        error: function (xht, textStatus, errorThrown) {
            switch (xht.status) {
                case 422:
                    $("#resultEmployee").removeClass("success");
                    $("#resultEmployee").addClass("error");
                    $("#resultEmployee").text("Dữ liệu không hợp lệ !");
                    break;
                case 500:
                    $("#resultEmployee").removeClass("success");
                    $("#resultEmployee").addClass("error");
                    $("#resultEmployee").text("Hệ thống đang bận, vui lòng thử lại sau !");
                    break;

                default:
                    break;
            }
        },
        success: function (data, textStatus, xht) {
            $("#resultEmployee").removeClass();
            $("#resultEmployee").addClass("success");
            $("#resultEmployee").text("Cập nhật thành công !");
            $("#add-or-update-employee").text("Cập nhật");
            setTimeout(function () { $("#resultEmployee").text(""); }, 1000);
            setTimeout(function () { $("#modal-addEmployee-updateEmployee").modal('hide'); }, 1000);
            loadEmployee();
          
        },

    })

}

function deleteEmployee() {
    $("#employees").on('click', '.del-button-employee', function () {
        var id = $(this).data("employee-id");

        var nameEmployeeTable = $(this).data("employee-name");
        $('.info-del-employee').html(nameEmployeeTable);
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");

        $("#confirm-del-employee").on('click', function () {
            $(".overlay").removeClass("hide");
            $(".overlay").addClass("show");
            $("#confirm-del-employee").text("Đang xóa ...");

            $.ajax({
                type: 'POST',
                url: '/ajax/employee/deleteemployee',
                dataType: 'json',
                data: {
                    'id': id,
                },
            }).always(function () {
                $("#resultDelEmployee").text("Xóa thành công !");
                $("#confirm-del-employee").text("Xóa");
                setTimeout(function () { $("#resultDelEmployee").text(""); }, 1000);
                setTimeout(function () { $("#modal-del-employee").modal('hide'); }, 1000);
                loadEmployee();
             
            });


        })

    })


}


function inputDateTimePickerEm() {

	$("#dayToWork").datetimepicker({
		timepicker: false,
		format: 'Y-m-d'
	});

}



$(document).ready(function () {
    loadEmployee(); 
    inputDateTimePickerEm();
    validateEmployeeForm();

    // value Add
    $("#btn-add-employee").on('click', function () {
        $("#employeeInfo").find("input[type = 'hidden']").val('addEmployee');
        $("#add-or-update-employee").text("Lưu");

        $("#add-or-update-employee").removeClass("btn-warning");
        $("#add-or-update-employee").addClass("btn-primary");

        $("#close-employee").removeClass("btn-warning");
        $("#close-employee").addClass("btn-primary");

        $("#refreshAnnimationEmployee").addClass("text-blue");
        $("#refreshAnnimationEmployee").removeClass("text-orange");

        // reset validate
        $("input").removeClass("valid");
        $("input").removeClass("error");
        $(".error").css("display", "none");

        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");

        $("#employeeInfo")[0].reset();

    });



    // value Update
    $("#employees").on('click', '.edit-button-employee', function () {
        id = $(this).data("employee-id");


        $("#add-or-update-employee").text("Cập nhật");
        $("#employeeInfo").find("input[type = 'hidden']").val('updateEmployee');

        $("#add-or-update-employee").removeClass("btn-primary");
        $("#add-or-update-employee").addClass("btn-warning");

        $("#close-employee").removeClass("btn-primary");
        $("#close-employee").addClass("btn-warning");

        $("#refreshAnnimationEmployee").removeClass("text-blue");
        $("#refreshAnnimationEmployee").addClass("text-orange");


        // reset validate
        $("input").removeClass("valid");
        $("input").removeClass("error");
        $(".error").css("display", "none");

        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");

        $.ajax({
            type: 'POST',
            url: '/ajax/employee/loademployeebyid',
            dataType: 'json',
            data: {
                'id': id
            },
            success: function (data, textStatus, xhr) {
                $("#employeeInfo").find("input[name = 'nameEmployee']").val(data.name);
                $("#employeeInfo").find("input[name = 'codeEmployee']").val(data.code);
                $("#employeeInfo").find("input[name = 'phoneNumber']").val(data.phoneNumber);
                $("#employeeInfo").find("input[name = 'idCardNo']").val(data.idCardNo);
                $("#employeeInfo").find("input[name = 'address']").val(data.address);
                $("#employeeInfo").find("select[name = 'gender']").val(data.gender);
                $("#employeeInfo").find("select[name = 'position']").val(data.position);
                $("#employeeInfo").find("select[name = 'shift']").val(data.shift);

                if (data.yearOfBirth) {
                    var yearOfBirth = convertDateFormat(data.yearOfBirth.date);
                    $("#employeeInfo").find("input[name = 'yearOfBirth']").val(yearOfBirth);
                }
                if (data.dayToWork) {
                    var dayToWork = convertDateFormat(data.dayToWork.date);
                    $("#employeeInfo").find("input[name = 'dayToWork']").val(dayToWork);
                }

            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        })
    });


    // select pop-up add or update
    $("#add-or-update-employee").on('click', function () {
        if ($("#employeeInfo").find("input[type = 'hidden']").val() == "addEmployee") {     
            addEmployee();
        }
        else {
            updateEmployee();
        }
    });


    // Delete
    deleteEmployee();


})