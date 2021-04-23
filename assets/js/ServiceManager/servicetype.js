function validateServiceTypeForm() {
    let serviceTypeForm = $("#serviceTypeInfo");

    if ($("#serviceTypeInfo").length) {
        let validator = serviceTypeForm.validate({
            errorClass: "has-error",
            validClass: "has-success",
            rules: {
                nameServiceType: {
                    required: true,
                    minlength: 2
                },
                codeServiceType: {
                    required: true,
                    minlength: 2
                }

            }
        });

        return validator.valid();
    }
    return true;
}

function loadServiceType() {
    var data = "";
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/service/loadservicetype',
        data,
        dataType: 'json',
    })
        .done(function (result) {
            var info_data = '';
            $("#serviceTypes tr:has(td)").remove();

            $.each(result, function (key, value) {
                info_data += '<tr>';
                info_data += '<td>' + value.code + '</td>';
                info_data += '<td>' + value.name + '</td>';
                info_data += '<td>  <button type="button" class="btn btn-warning btn-xs edit-button-serviceType" data-servicetype-id="' + value.id + '"  data-toggle="modal" data-target="#modal-addServiceType-updateServiceType"><span class="fa fa-wrench"></span></button>  <button type="button" class="btn btn-danger btn-xs del-button-serviceType"   data-servicetype-id="' + value.id + '"   data-servicetype-name="' + value.name + '"  data-toggle="modal" data-target="#modal-del-serviceType"><span class="fa fa-trash-o"></span></button> </td>'
                info_data += '</tr>';
            });
            $('#serviceTypes').append(info_data);

        });
}

function addServiceType() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");

    if (!validateServiceTypeForm()) {
        $("#add-or-update-serviceType").text("Lưu");
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#serviceTypeInfo").serialize();

    $.ajax({
        type: 'POST',
        url: '/ajax/manager/service/addservicetype',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            $("#resultServiceType").addClass('text-red');
            $("#resultServiceType").removeClass("hidden");
            switch (xhr.status) {
                case 500: {
                    $("#resultServiceType").text("Bạn không thể thêm loại dịch vụ này!");
                    break;
                }
                case 422: {
                    $("#resultServiceType").text("Bạn không có quyền truy cập chức năng này!");
                    break;
                }
            }
            $("#modal-addServiceType-updateServiceType .overlay").removeClass('show').addClass("hide");
        },
        success: function (data, textStatus, xhr) {
            switch (xhr.status) {
                case 201: {
                    $("#resultServiceType").removeClass('text-red');
                    $("#resultServiceType").removeClass('hidden');
                    $("#resultServiceType").addClass("success");
                    $("#resultServiceType").text("Thêm loại dịch vụ thành công !");
                    setTimeout(function () { $("#resultServiceType").text(""); }, 1000);
                    setTimeout(function () { $("#modal-addServiceType-updateServiceType").modal('hide'); }, 1000);
                    loadServiceType();
                    break;
                }
                default:
                    break;
            }
        },
    })

}

function updateServiceType() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");
    if (!validateServiceTypeForm()) {
        $("#add-or-update-serviceType").text("Cập nhật");
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#serviceTypeInfo").serialize();
    data += '&id=' + id;
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/service/updateservicetype',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            $("#resultServiceType").addClass('text-red');
            $("#resultServiceType").removeClass("hidden");
            switch (xhr.status) {
                case 500: {
                    $("#resultServiceType").text("Bạn không thể cập nhật loại dịch vụ này!");
                    break;
                }
                case 422: {
                    $("#resultServiceType").text("Bạn không có quyền truy cập chức năng này!");
                    break;
                }
            }
            $("#modal-addServiceType-updateServiceType .overlay").removeClass('show').addClass("hide");
        },
        success: function (data, textStatus, xhr) {
            switch (xhr.status) {
                case 201: {
                    $("#resultServiceType").removeClass('text-red');
                    $("#resultServiceType").removeClass('hidden');
                    $("#resultServiceType").addClass("success");
                    $("#resultServiceType").text("Thêm loại dịch vụ thành công !");
                    setTimeout(function () { $("#resultServiceType").text(""); }, 1000);
                    setTimeout(function () { $("#modal-addServiceType-updateServiceType").modal('hide'); }, 1000);
                    loadServiceType();
                    break;
                }
                default:
                    break;
            }
        },

    })

}

function deleteServiceType() {
    $("#serviceTypes").on('click', '.del-button-serviceType', function () {
        var id = $(this).data("servicetype-id");

        var nameServiceTypeTable = $(this).data("servicetype-name");
        $('.info-del-serviceType').html(nameServiceTypeTable);
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");

        $("#confirm-del-serviceType").on('click', function () {
            $(".overlay").removeClass("hide");
            $(".overlay").addClass("show");

            $.ajax({
                type: 'POST',
                url: '/ajax/manager/service/deleteservicetype',
                dataType: 'json',
                data: {
                    'id': id,
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#resultDelServiceType").addClass('text-red');
                    $("#resultDelServiceType").removeClass("hidden");
                    switch (xhr.status) {
                        case 500: {
                            $("#resultDelServiceType").text("Bạn không thể xóa dịch vụ này!");
                            break;
                        }
                        case 422: {
                            $("#resultDelServiceType").text("Bạn không có quyền truy cập chức năng này!");
                            break;
                        }
                    }
                    $("#modal-del-serviceType .overlay").removeClass('show').addClass("hide");
                },
                success: function (data, textStatus, xhr) {
                    switch (xhr.status) {
                        case 201: {
                            $("#resultDelServiceType").removeClass("hidden");
                            $("#resultDelServiceType").removeClass('text-red');
                            $("#resultDelServiceType").addClass("success");
                            $("#resultDelServiceType").text("Xóa thành công!");
                            $("#modal-del-serviceType .overlay").removeClass('show').addClass("hide");
                            loadServiceType();
                            break;
                        }
                        default:
                            break;
                    }
                }
            });
        })

    })


}

$(document).ready(function () {
    validateServiceTypeForm();

    // value Add
    $("#btn-add-serviceType").on('click', function () {
        $("#serviceTypeInfo").find("input[type = 'hidden']").val('addServiceType');
        $("#add-or-update-serviceType").text("Lưu");

        $("#add-or-update-serviceType").removeClass("btn-warning");
        $("#add-or-update-serviceType").addClass("btn-primary");

        $("#close-service-type").removeClass("btn-warning");
        $("#close-service-type").addClass("btn-primary");

        $("#refreshAnnimationServiceType").addClass("text-blue");
        $("#refreshAnnimationServiceType").removeClass("text-orange");

        // reset validate
        $("input").removeClass("valid");
        $("input").removeClass("error");
        $(".error").css("display", "none");

        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");

        $("#serviceTypeInfo")[0].reset();

    });



    // value Update
    $("#serviceTypes").on('click', '.edit-button-serviceType', function () {
        id = $(this).data("servicetype-id");

        $("#add-or-update-serviceType").text("Cập nhật");
        $("#serviceTypeInfo").find("input[type = 'hidden']").val('updateServiceType');

        $("#add-or-update-serviceType").removeClass("btn-primary");
        $("#add-or-update-serviceType").addClass("btn-warning");

        $("#close-service-type").removeClass("btn-primary");
        $("#close-service-type").addClass("btn-warning");

        $("#refreshAnnimationServiceType").removeClass("text-blue");
        $("#refreshAnnimationServiceType").addClass("text-orange");


        // reset validate
        $("input").removeClass("valid");
        $("input").removeClass("error");
        $(".error").css("display", "none");

        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");

        $.ajax({
            type: 'POST',
            url: '/ajax/manager/service/loadservicetypebyid',
            dataType: 'json',
            data: {
                'id': id
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#resultServiceType").addClass('text-red');
                $("#resultServiceType").removeClass("hidden");
                switch (xhr.status) {
                    case 500: {
                        $("#resultServiceType").text("Bạn không thể xóa loại dịch vụ này!");
                        break;
                    }
                    case 422: {
                        $("#resultServiceType").text("Bạn không có quyền truy cập chức năng này!");
                        break;
                    }
                }
                $("#modal-addServiceType-updateServiceType .overlay").removeClass('show').addClass("hide");
            },
            success: function (data, textStatus, xhr) {
                $("#serviceTypeInfo").find("input[name = 'nameServiceType']").val(data.name);
                $("#serviceTypeInfo").find("input[name = 'codeServiceType']").val(data.code);
                $("#serviceTypeInfo").find("select[name = 'active']").val(data.active);
            }
        })
    });


    // select pop-up add or update
    $("#add-or-update-serviceType").on('click', function () {
        if ($("#serviceTypeInfo").find("input[type = 'hidden']").val() == "addServiceType") {
            addServiceType();
        }
        else {
            updateServiceType();
        }
    });


    // Delete
    deleteServiceType();


})