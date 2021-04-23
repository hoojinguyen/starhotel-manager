$(document).ready(function () {

    validateServiceForm();
    loadService();
    // value Add
    $("#btn-add-service").on('click', function(){
        $("#serviceInfo").find("input[type = 'hidden']").val('addService'); 
        $("#add-or-update-service").text("Lưu");

        $("#add-or-update-service").removeClass("btn-warning");
        $("#add-or-update-service").addClass("btn-primary");

        $("#close-service").removeClass("btn-warning");
        $("#close-service").addClass("btn-primary");

        $("#refreshAnnimationService").addClass("text-blue");
        $("#refreshAnnimationService").removeClass("text-orange");

         // reset validate
         $("input").removeClass("valid");
         $("input").removeClass("error");
         $(".error").css("display", "none");

         $(".overlay").removeClass("show");
         $(".overlay").addClass("hide");
         
        $("#serviceInfo")[0].reset();

    });



    // value Update
    $("#services").on('click', '.edit-button-service', function () {
        id = $(this).data("service-id");
        
        $("#add-or-update-service").text("Cập nhật");
        $("#serviceInfo").find("input[type = 'hidden']").val('updateService');

        $("#add-or-update-service").removeClass("btn-primary");
        $("#add-or-update-service").addClass("btn-warning");

        $("#close-service").removeClass("btn-primary");
        $("#close-service").addClass("btn-warning");

        $("#refreshAnnimationService").removeClass("text-blue");
        $("#refreshAnnimationService").addClass("text-orange");


         // reset validate
         $("input").removeClass("valid");
         $("input").removeClass("error");
         $(".error").css("display", "none");

         $(".overlay").removeClass("show");
         $(".overlay").addClass("hide");

         $("#resultService").text("");

        $.ajax({
            type: 'POST',
            url: '/ajax/manager/service/loadservicebyid',
            dataType: 'json',
            data: {
                'id': id
            },
            success: function (data, textStatus, xhr) {          
                $("#serviceInfo").find("input[name = 'nameService']").val(data[0].name);
                $("#serviceInfo").find("input[name = 'price']").val(data[0].price);
                $("#serviceInfo").find("select[name = 'serviceType']").val(data[0].serviceTypeId);
                $("#serviceInfo").find("select[name = 'status']").val(data[0].status);
                $("#serviceInfo").find("select[name = 'unit']").val(data[0].unit);
                $("#serviceInfo").find("input[name = 'codeService']").val(data[0].code);
            },
            error: function (xhr, textStatus, errorThrown) {
                $("#resultService").addClass('text-red');
                $("#resultService").removeClass("hidden");
                switch (xhr.status) {
                    case 500: {
                        $("#resultDelService").text("Cập nhật không thành công!");
                        break;
                    }
                    case 422: {
                        $("#resultService").text("Bạn không có quyền truy cập chức năng này!");
                        break;
                    }
                }
                $("#modal-addService-updateService .overlay").removeClass('show').addClass("hide");
            }
        })
    });


    // select pop-up add or update
    $("#add-or-update-service").on('click', function () {
        $("#resultService").text("");
        if ($("#serviceInfo").find("input[type = 'hidden']").val() == "addService") {
            addService(); 
        }
        else {
            updateService();
        }
    });


    // Delete
    deleteService();
})

function validateServiceForm() {
    let serviceForm = $("#serviceInfo");

    if ($("#serviceInfo").length) {
        let validator = serviceForm.validate({
            errorClass: "has-error",
            validClass: "has-success",
            rules: {
                nameService: {
                    required: true,
                    minlength: 2
                },
                codeService: {
                    required: true,
                    minlength: 2
                },
                price:{
                    required: true,
                    minlength:4
                }

            }
        });

        return validator.valid();
    }
    return true;
}

function loadService() {
    var data="";
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/service/loadservice',
        data,
        dataType: 'json',
    })
        .done(function (result) {
            var info_data = '';
            $("#services tr:has(td)").remove();
            
            $.each(result, function (key, value) {
            
                info_data += '<tr>';
                info_data += '<td>' + value.code + '</td>';
                info_data += '<td>' + value.nameService + '</td>';
                info_data += '<td>' + value.price + '</td>';
                info_data += '<td>' + value.nameServiceType + '</td>';
                info_data += '<td>' + value.unit + '</td>';
                // info_data += '<td>' + value.status + '</td>';
                if( value.active !=0 ){
                    info_data += '<td>  <button type="button" class="btn btn-warning btn-xs edit-button-service"  data-service-id="' + value.id+ '"  data-toggle="modal" data-target="#modal-addService-updateService"><span class="fa fa-wrench"></span></button> <button type="button" class="btn btn-danger btn-xs del-button-service"  data-service-id="' + value.id+ '"   data-service-name="' + value.nameService+ '"   data-toggle="modal" data-target="#modal-del-service"><span class="fa fa-trash-o"></span></button> </td>';
                }
                else {
                    info_data += '<td>  <button type="button" class="btn btn-warning btn-xs edit-button-service disabled"  data-service-id="' + value.id+ '"  data-toggle="modal" data-target="#modal-addService-updateService"><span class="fa fa-wrench"></span></button> <button type="button" class="btn btn-danger btn-xs del-button-service disabled"  data-service-id="' + value.id+ '"   data-service-name="' + value.nameService+ '"   data-toggle="modal" data-target="#modal-del-service"><span class="fa fa-trash-o"></span></button> </td>';

                }
                info_data += '</tr>';
            });
            $('#services').append(info_data);

        });
}



function addService() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");

    if (!validateServiceForm()) {
        $("#add-or-update-service").text("Lưu");
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#serviceInfo").serialize();

    

    $.ajax({
        type: 'POST',
        url: '/ajax/manager/service/addservice',
        data,
        dataType: 'json',
       error: function (xhr, textStatus, errorThrown) {
            $("#resultService").addClass('text-red');
            $("#resultService").removeClass("hidden");
            switch (xhr.status) {
                case 500: {
                    $("#resultDelService").text("Thêm mới không thành công!");
                    break;
                }
                case 422: {
                    $("#resultService").text("Bạn không có quyền truy cập chức năng này!");
                    break;
                }
            }
            $("#modal-addService-updateService .overlay").removeClass('show').addClass("hide");
        },
        success: function (data, textStatus, xhr) {
            switch (xhr.status) {
                case 201: {
                    $("#resultService").removeClass('text-red');
                    $("#resultService").removeClass('hidden');
                    $("#resultService").addClass("success");
                    $("#resultService").text("Thêm dịch vụ thành công !");
                    setTimeout(function () { $("#resultService").text(""); }, 1000);
                    setTimeout(function () { $("#modal-addService-updateService").modal('hide'); }, 1000);
                    loadService();
                    break;
                }
                default:
                    break;
            }
        },
    })

}

function updateService() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");
    if (!validateServiceForm()) {
        $("#add-or-update-service").text("Cập nhật");
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    $("#resultService").text("");

    var data = $("#serviceInfo").serialize();
    data += '&id=' + id;
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/service/updateservice',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            $("#resultService").addClass('text-red');
            $("#resultService").removeClass("hidden");
            switch (xhr.status) {
                case 500: {
                    $("#resultDelService").text("Cập nhật không thành công!");
                    break;
                }
                case 422: {
                    $("#resultService").text("Bạn không có quyền truy cập chức năng này!");
                    break;
                }
            }
            $("#modal-addService-updateService .overlay").removeClass('show').addClass("hide");
        },
        success: function (data, textStatus, xhr) {
            switch (xhr.status) {
                case 201: {
                    $("#resultService").removeClass('text-red');
                    $("#resultService").removeClass('hidden');
                    $("#resultService").addClass("success");
                    $("#resultService").text("Cập nhật thành công !");
                    setTimeout(function () { $("#resultService").text(""); }, 1000);
                    setTimeout(function () { $("#modal-addService-updateService").modal('hide'); }, 1000);
                    loadService();
                    break;
                }
                default:
                    break;
            }
        },
        
    })

}

function deleteService() {
    $("#services").on('click', '.del-button-service', function () {
        var id = $(this).data("service-id");
        var nameServiceTable = $(this).data("service-name");
        $('.info-del-service').html(nameServiceTable);

        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide"); 
   
        $("#resultDelService").text("");

        $("#confirm-del-service").on('click', function () {
            $(".overlay").removeClass("hide");
            $(".overlay").addClass("show");
            $.ajax({
                type: 'POST',
                url: '/ajax/manager/service/deleteservice',
                dataType: 'json',
                data: {
                    'id': id,
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#resultDelService").addClass('text-red');
                    $("#resultDelService").removeClass("hidden");
                    switch (xhr.status) {
                        case 500: {
                            $("#resultDelService").text("Xóa không thành công!");
                            break;
                        }
                        case 422: {
                            $("#resultDelService").text("Bạn không có quyền truy cập chức năng này!");
                            break;
                        }
                    }
                    $("#modal-del-service .overlay").removeClass('show').addClass("hide");
                },
                success: function (data, textStatus, xhr) {
                    switch (xhr.status) {
                        case 201: {
                            $("#resultDelService").removeClass('text-red');
                            $("#resultDelService").removeClass('hidden');
                            $("#resultDelService").addClass("success");
                            $("#resultDelService").text("Xóa thành công!");
                            $("#modal-del-service .overlay").removeClass('show').addClass("hide");
                            loadService();
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

