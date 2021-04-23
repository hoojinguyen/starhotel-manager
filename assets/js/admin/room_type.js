function ValidateRoomType() {
    let form = $("#roomTypeInfo");

    let validatorRoomType = form.validate({
        //errorClass: "has-error",
        //validClass: "has-success",
        rules: {
            nameRoomType: {
                required: true,
                minlength: 6
            },
            price: {
                required: true,
                range: [10000, 100000000]
            },
            numOfPeopleStay: {
                required: true,
                range: [1, 100]
            },
            codeRoomType: {
                required: true,
                minlength: 2
            }

        },
    })

    if (!validatorRoomType.form())
        return false;
    else {
        return true;
    }

}

function loadTypeRoom() {
    var data="";
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/room/loadroomtype',
        data,
        dataType: 'json',
    })
        .done(function (result) {
            var info_data = '';
            $("#roomTypes tr:has(td)").remove();
          
            $.each(result, function (key, value) {
                var price = formatMoney(value.price);
                info_data += '<tr>';
                info_data += '<td>' + value.code + '</td>';
                info_data += '<td>' + value.name + '</td>';
                info_data += '<td >' + value.numOfPeopleStay + '</td>';
                info_data += '<td>' + value.active + '</td>';
                info_data += '<td class="text-center"><button type="button" class="btn btn-warning btn-sm edit-roomtype" data-toggle="modal" data-target="#modal-addRoomType-updateRoomType" data-roomtype-id="' +  value.id + '"><span class="fa fa-wrench"></span></button> '
			    info_data += '<button type="button" class="btn btn-danger btn-sm delele-roomtype" data-toggle="modal" data-target="#modal-del-roomtype" data-roomtype-id="' +  value.id + '" data-roomtype-name="'+ value.name +'"><span class="fa fa-trash-o"></span></button> </td>'
                info_data += '</tr>';
            });
            $('#roomTypes').append(info_data);

        });
};

function addRoomType() {
        $(".overlay").removeClass("hide");
        $(".overlay").addClass("show");

        if (!ValidateRoomType()) {
            $(".overlay").removeClass("show");
            $(".overlay").addClass("hide");
            return 0;
        }
        var data = $("#roomTypeInfo").serialize();
       
        $.ajax({
            type: 'POST',
            url: '/ajax/manager/room/addroomtype',
            data,
            dataType: 'json',
            error: function (xhr, textStatus, errorThrown) {
                $("#resultRoomType").addClass('text-red');
                $("#resultRoomType").removeClass("hidden");
                switch (xhr.status) {
                    case 500: {
                        $("#resultRoomType").text("Thêm không thành công!");
                        break;
                    }
                    case 422: {
                        $("#resultRoomType").text("Bạn không có quyền truy cập chức năng này!");
                        break;
                    }
                }
                $("#modal-addRoomType-updateRoomType .overlay").removeClass('show').addClass("hide");
            },
            success: function (data, textStatus, xhr) {
                switch (xhr.status) {
                    case 201: {
                        $("#resultRoomType").removeClass('text-red');
                        $("#resultRoomType").removeClass('hidden');
                        $("#resultRoomType").addClass("success");
                        $("#resultRoomType").text("Thêm thành công !");
                        setTimeout(function () { $("#resultRoomType").text(""); }, 1000);
                        break;
                    }
                    default:
                        break;
                }
                setTimeout(function () { location.reload(); }, 1000);
            }
        });
}


function updateRoomType() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");

    if (!ValidateRoomType()) {
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#roomTypeInfo").serialize();
    data += '&id=' + id;
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/room/updateroomtype',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            $("#resultRoomType").addClass('text-red');
            $("#resultRoomType").removeClass("hidden");
            switch (xhr.status) {
                case 500: {
                    $("#resultRoomType").text("Cập nhật không thành công!");
                    break;
                }
                case 422: {
                    $("#resultRoomType").text("Bạn không có quyền truy cập chức năng này!");
                    break;
                }
            }
            $("#addRoomType-updateRoomType .overlay").removeClass('show').addClass("hide");
        },
        success: function (data, textStatus, xhr) {
            switch (xhr.status) {
                case 201: {
                    $("#resultRoomType").removeClass('text-red');
                    $("#resultRoomType").removeClass('hidden');
                    $("#resultRoomType").addClass("success");
                    $("#resultRoomType").text("Cập nhật thành công !");
                    setTimeout(function () { $("#resultRoomType").text(""); }, 1000);
                    break;
                }
                default:
                    break;
            }
            setTimeout(function () { location.reload(); }, 1000);
        }
    });


}


function deleteRoomType() {
    $("#roomTypes").on('click', '.del-roomtype', function () {
      
        id = $(this).data("roomtype-id");
        console.log(id);
        
        var nameRoomType = $(this).data("roomtype-name");
        console.log(nameRoomType);
        
       
        $('.info-del-roomtype').html(nameRoomType);

    })

    $("#confirm-del-roomtype").on('click', function () {
        $.ajax({
            type: 'POST',
            url: '/ajax/manager/room/deleteroomtype',
            dataType: 'json',
            data: {
                'id': id,
            },
            error: function (xhr, textStatus, errorThrown) {
				$("#result-del-roomtype").addClass('text-red');
				$("#result-del-roomtype").removeClass("hidden");
				switch (xhr.status) {
					case 500: {
						$("#result-del-roomtype").text("Xóa không thành công!");
						break;
					}
					case 422: {
						$("#result-del-roomtype").text("Bạn không có quyền truy cập chức năng này!");
						break;
					}
				}
				$("#modal-del-roomtype .overlay").removeClass('show').addClass("hide");
			},
			success: function (data, textStatus, xhr) {
				switch (xhr.status) {
					case 201: {
						$("#result-del-roomtype").removeClass('text-red');
						$("#result-del-roomtype").removeClass('hidden');
						$("#result-del-roomtype").addClass("success");
						$("#result-del-roomtype").text("Xóa thành công!");
						$("#modal-del-roomtype  .overlay").removeClass('show').addClass("hide");
						break;
					}
					default:	
						break;
				}
				setTimeout(function () { location.reload(); }, 1000);
			}
        });
    

    })
}



$(document).ready(function () {
    $(function () {

       
        // value Add
        $("#btn-add-roomtype").on('click', function(){
            $("#roomTypeInfo").find("input[type = 'hidden']").val('addRoomType'); 
            $("#add-or-update-roomType").text("Lưu");

            $("#add-or-update-roomType").removeClass("btn-warning");
            $("#add-or-update-roomType").addClass("btn-primary");

            $("#close-room-type").removeClass("btn-warning");
            $("#close-room-type").addClass("btn-primary");

             // reset validate
             $("input").removeClass("valid");
             $("input").removeClass("error");
             $(".error").css("display", "none");
             
            $("#roomTypeInfo")[0].reset();
    
        });



        // value Update
        $("#roomTypes").on('click', '.edit-roomtype', function () {
            id = $(this).data("roomtype-id");
            console.log(id);
            
            $("#add-or-update-roomType").text("Cập nhật");
            $("#roomTypeInfo").find("input[type = 'hidden']").val('updateRoomType');

            $("#add-or-update-roomType").removeClass("btn-primary");
            $("#add-or-update-roomType").addClass("btn-warning");

            $("#close-room-type").removeClass("btn-primary");
            $("#close-room-type").addClass("btn-warning");

             // reset validate
             $("input").removeClass("valid");
             $("input").removeClass("error");
             $(".error").css("display", "none");
    
            $.ajax({
                type: 'POST',
                url: '/ajax/manager/room/loadroomtypebyid',
                dataType: 'json',
                data: {
                    'id': id
                },
                success: function (data, textStatus, xhr) {
                    $("#roomTypeInfo").find("input[name = 'nameRoomType']").val(data.name);
                    $("#roomTypeInfo").find("input[name = 'price']").val(data.price);
                    $("#roomTypeInfo").find("input[name = 'numOfPeopleStay']").val(data.maxPeople);
                    $("#roomTypeInfo").find("input[name = 'codeRoomType']").val(data.code);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#resultRoomType").addClass('text-red');
                    $("#resultRoomType").removeClass("hidden");
                    switch (xhr.status) {
                        case 500: {
                            $("#resultFresultRoomTypeloor").text("Cập nhật không thành công!");
                            break;
                        }
                        case 422: {
                            $("#resultRoomType").text("Bạn không có quyền truy cập chức năng này!");
                            break;
                        }
                    }
                    $("#modal-modal-addRoomType-updateRoomType .overlay").removeClass('show').addClass("hide");
                }
            })
        });


        // select pop-up add or update
        $("#add-or-update-roomType").on('click', function () {
            if ($("#roomTypeInfo").find("input[type = 'hidden']").val() == "addRoomType") {
                addRoomType();
            }
            else {
                updateRoomType();
            }
        });


        // Delete
        deleteRoomType();

    });






})
