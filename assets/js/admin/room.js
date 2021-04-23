function validateRoom() {
    let form = $("#roomInfo");

    let validator = form.validate({
        //errorClass: "has-error",
        //validClass: "has-success",
        rules: {
            nameRoom: {
                required: true,
                minlength: 6
            }

        },
    })

    if (!validator.form())
        return false;
    else {
        return true;
    }

}

function loadRoom() {
    var data="";
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/room/loadroom',
        data,
        dataType: 'json',
    })
        .done(function (result) {
            var info_data = '';
            $("#rooms tr:has(td)").remove(); 
            $.each(result, function (key, value) {
                info_data += '<tr>';
                info_data += '<td>' + value.nameRoom + '</td>';
                info_data += '<td >' + value.floor + '</td>';
                info_data += '<td >' + value.roomType + '</td>';
                info_data += '<td class="text-center"><button type="button" class="btn btn-warning btn-xs edit-room" data-toggle="modal" data-target="#modal-addRoom-updateRoom" data-room-id="' +  value.id + '"><span class="fa fa-wrench"></span></button> '
                info_data += '<button type="button" class="btn btn-danger btn-xs delele-room" data-toggle="modal" data-target="#modal-del-room" data-room-id="' +  value.id + '" data-room-name="'+ value.nameRoom +'"><span class="fa fa-trash-o"></span></button> </td>'    
                info_data += '</tr>';
            });
            $('#rooms').append(info_data);

        });
}

function addRoom(){
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");
    if (!validateRoom()) {
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#roomInfo").serialize();

    $.ajax({
        type: 'POST',
        url: '/ajax/manager/room/addroom',
        data,
        dataType:'json',
        error: function (xhr, textStatus, errorThrown) {
			$("#resultRoom").addClass('text-red');
			$("#resultRoom").removeClass("hidden");
			switch (xhr.status) {
				case 500: {
					$("#resultRoom").text("Thêm không thành công!");
					break;
				}
				case 422: {
					$("#resultRoom").text("Bạn không có quyền truy cập chức năng này!");
					break;
				}
			}
			$("#modal-addRoom-updateRoom .overlay").removeClass('show').addClass("hide");
		},
		success: function (data, textStatus, xhr) {
			switch (xhr.status) {
				case 201: {
					$("#resultRoom").removeClass('text-red');
					$("#resultRoom").removeClass('hidden');
					$("#resultRoom").addClass("success");
					$("#resultRoom").text("Thêm thành công !");
                    setTimeout(function () { $("#resultRoom").text(""); }, 1000);
                    setTimeout(function () { $("#modal-addRoom-updateRoom").modal('hide'); }, 1000);
                    loadRoom();
					break;
				}
				default:
					break;
			}
        }
    });
}

function updateRoom() {
    $(".overlay").removeClass("hide");
    $(".overlay").addClass("show");
    if (!ValidateRoomType()) {
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    var data = $("#roomInfo").serialize();
   
    $.ajax({
        type: 'POST',
        url: '/ajax/manager/room/updateroom',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
			$("#resultRoom").addClass('text-red');
			$("#resultRoom").removeClass("hidden");
			switch (xhr.status) {
				case 500: {
					$("#resultRoom").text("Cập nhật không thành công!");
					break;
				}
				case 422: {
					$("#resultRoom").text("Bạn không có quyền truy cập chức năng này!");
					break;
				}
			}
			$("#modal-addRoom-updateRoom .overlay").removeClass('show').addClass("hide");
		},
		success: function (data, textStatus, xhr) {
			switch (xhr.status) {
				case 201: {
					$("#resultRoom").removeClass('text-red');
					$("#resultRoom").removeClass('hidden');
					$("#resultRoom").addClass("success");
					$("#resultRoom").text("Câp nhật thành công !");
                    setTimeout(function () { $("#resultFloor").text(""); }, 1000);
                    setTimeout(function () { $("#modal-addRoom-updateRoom").modal('hide'); }, 1000);
                    loadRoom();
					break;
				}
				default:
					break;
			}
		},
    });
}

function deleteRoom() {

    $("#rooms").on('click', '.del-room', function () {
        var nameRoom = $(this).data("room-name");
        $('.info-del-room').html(nameRoom);
        console.log(id);
        var id = $(this).data("room-id");
        $("#idRoomDel").val(id);
    })

    $("#confirm-del-room").on('click', function () {
        var id=$("#idRoomDel").val();
        $.ajax({
            type: 'POST',
            url: '/ajax/manager/room/deleteroom',
            dataType: 'json',
            data: {
                'id': id,
            },
            error: function (xhr, textStatus, errorThrown) {
				$("#result-del-room").addClass('text-red');
				$("#result-del-room").removeClass("hidden");
				switch (xhr.status) {
					case 500: {
                        $("#result-del-room").text("Xóa không thành công!");
                        setTimeout(function () { $("#result-del-room").text(""); }, 1000);
						break;
					}
					case 422: {
						$("#result-del-room").text("Bạn không có quyền truy cập chức năng này!");
						break;
					}
				}
				$("#modal-addRoom-updateRoom .overlay").removeClass('show').addClass("hide");
			},
			success: function (data, textStatus, xhr) {
				switch (xhr.status) {
					case 201: {
						$("#result-del-room").removeClass('text-red');
						$("#result-del-room").removeClass('hidden');
						$("#result-del-room").addClass("success");
                        $("#result-del-room").text("Xóa thành công!");
                        setTimeout(function () { $("#result-del-room").text(""); }, 1000);
						$("#modal-del-room .overlay").removeClass('show').addClass("hide");
						break;
					}
					default:	
						break;
				}
                setTimeout(function () {  loadRoom(); }, 1000);
                $("#modal-del-room").modal('hide');
            }
            

        });
    })
}

$(document).ready(function(){
    
    $(function(){
          // value Add
          $("#btn-add-room").on('click', function(){
            $("#roomInfo").find("input[type = 'hidden']").val('addRoom'); 
            $("#add-or-update-room").text("Lưu");

            $("#add-or-update-room").removeClass("btn-warning");
            $("#add-or-update-room").addClass("btn-primary");

            $("#close-room").removeClass("btn-warning");
            $("#close-room").addClass("btn-primary");

             // reset validate
             $("input").removeClass("valid");
             $("input").removeClass("error");
             $(".error").css("display", "none");

            $("#roomInfo")[0].reset();
    
        });



        // value Update
        $("#rooms").on('click', '.edit-room', function () {
            var id = $(this).data("room-id");
            
            $("#add-or-update-room").text("Cập nhật");
            $("#roomInfo").find("input[type = 'hidden']").val('updateRoom');

            $("#add-or-update-room").removeClass("btn-primary");
            $("#add-or-update-room").addClass("btn-warning");

            $("#close-room").removeClass("btn-primary");
            $("#close-room").addClass("btn-warning");

             // reset validate
             $("input").removeClass("valid");
             $("input").removeClass("error");
             $(".error").css("display", "none");
          
    
            $.ajax({
                type: 'POST',
                url: '/ajax/manager/room/loadroombyid',
                dataType: 'json',
                data: {
                    'id': id
                },
                success: function (data, textStatus, xhr) {
                    console.log(data[0]);
                    
                    data = data[0];
                    console.log(data.id);
                    $("#roomInfo").find("input[name = 'nameRoom']").val(data.name);
                    $("#roomInfo").find("select[id = 'roomType']").val(data.roomTypeId);
                    $("#roomInfo").find("select[id = 'floor']").val(data.floorId);
                    $("#roomInfo").find("select[name = 'statusRoom']").val(data.status); 
                    $("#roomInfo").find("input[name = 'idRoom']").val(data.id);
                },
                error: function (xhr, textStatus, errorThrown) {
                    $("#resultRoom").addClass('text-red');
					$("#resultRoom").removeClass("hidden");
					switch (xhr.status) {
						case 500: {
							$("#resultRoom").text("Cập nhật không thành công!");
							break;
						}
						case 422: {
							$("#resultRoom").text("Bạn không có quyền truy cập chức năng này!");
							break;
						}
					}
					$("#modal-addRoom-updateRoom .overlay").removeClass('show').addClass("hide");
                }
            })
        });


        $("#add-or-update-room").on('click', function () {
            if ($("#roomInfo").find("input[type = 'hidden']").val() == "addRoom") {
                addRoom();
            }
            else {
                updateRoom();
            }
        });

        deleteRoom();
    });
})