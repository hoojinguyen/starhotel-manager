$(document).ready(function () {

    if ($("#bookingCode").val() !== "") {
        $("#listRoomCheckin").addClass("hidden");
    }

    $('.btnCheckinLive').on('click', function () {
        var bookingCode = $(this).data("booking-code");
        $("#bookingCode").val(bookingCode);
        $("#btnSearchCode").trigger("click");
        $("#listRoomCheckin").addClass("hidden");
    });

    inputDateTimePicker();
    getInfoBookRoomFromCode();

    var keyCode = $("#bookingCode").val();
    if (keyCode != "") {
        $("#btnSearchCode").trigger("click");
    }

    confirmCheckIn();

    cancelBookRoom();

    // Click find room empty
    $(".btnChangeRoom").on("click", function () {
        $(".overlay").removeClass("hide");
        $(".overlay").addClass("show");
        $("#noData").html("");
        $("tbody").css("opacity", "0.5");

        var dayCheckin = $(this).data("day-checkin");
        var dayCheckout = $(this).data("day-checkout");
        var roomType = $(this).data("room-type");
        var idRoomType = $(this).data("room-type-id");
        var idBookingRoom = $(this).data("booking-room-id");
        var idRoom = $(this).data("room-id");
        var idBill = $(this).data("bill-id");
        $("#inputDayCheckin").val(dayCheckin);
        $("#inputDayCheckout").val(dayCheckout);
        $("#idRoomOld").val(idRoom);
        $("#idRoomTypeOld").val(idRoomType);
        $("#inputIdBookingRoom").val(idBookingRoom);
        $("#inputIdBill").val(idBill);

        findRoomEmpty(dayCheckin, dayCheckout, roomType);
    })

    // Chose room and change 
    $("#btnSaveChoseEmptyRoom").on("click", function () {
        var idRoomTypeNew = $("input[name='chooseRoom']:checked").data('room-type');
        var idRoomTypeOld = $("#idRoomTypeOld").val();
        var idRoomNew = $("input[name='chooseRoom']:checked").data('room-id');
        var idRoomOld = $("#idRoomOld").val();
        var dayCheckin = $("#inputDayCheckin").val();
        var dayCheckout = $("#inputDayCheckout").val();
        var idBookingRoom = $("#inputIdBookingRoom").val();
        var idBill = $("#inputIdBill").val();
        changeRoom(dayCheckin, dayCheckout, idBookingRoom, idRoomOld, idRoomNew, idBill, idRoomTypeNew, idRoomTypeOld);
    })

    checkGuest();
    searchGuest();

    $('#modal-addGuestInRoom').on('show.bs.modal', function (event) {
        loadInfoGuestToTable();
    })


    $("#btnAddGuestInRoom").on("click", function () {
        addGuestAndGuestInRoom();
    })
})


function formatDate(date) {
    var date1 = new Date(date);
    var radix = 10;
    var dateConverted = '';
    if (parseInt(date1.getMonth(), radix) < 9) {
        dateConverted = date1.getDate() + '-' + '0' + (parseInt(date1.getMonth() + 1, radix)).toString() + '-' + date1.getFullYear();
    }
    else {
        dateConverted = date1.getDate() + '-' + (parseInt(date1.getMonth() + 1, radix)).toString() + '-' + date1.getFullYear();
    }

    return dateConverted;
}


function validateInfoGuest() {
    let infoGuest = $("#infoGuest");
    if ($("#infoGuest").length) {
        let validator = infoGuest.validate({
            errorClass: "has-error",
            // validClass: "has-success",
            rules: {
                name: {
                    required: true,
                    minlength: 3,
                },
                gender: {
                    required: true
                },
                phoneNumber: {
                    required: true,
                    digits: true,
                },
                idCardNo: {
                    required: true,
                    digits: true,
                },
                idCardIssueDate: {
                    required: true
                },
                idCardIssuePlace: {
                    required: true
                },
                yearOfBirth: {
                    required: true
                },


            }
        });

        return validator.form();
    }
    return true;
}

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

function getInfoBookRoomFromCode() {
    $("#btnSearchCode").on('click', function () {
        $("#refreshButtonSearch").removeClass("hide");
        $("#listRoomCheckin").addClass("hidden");
        var bookingCode = $("#bookingCode").val();
        $.ajax({
            type: 'POST',
            url: '/ajax/room/getinfobookroom',
            data:
            {
                'bookingCode': bookingCode,
            },
            datatype: 'json',
            success: function (data, textStatus, xhr) {
                // dataJson = JSON.parse(data);
                $("#refreshButtonSearch").addClass("hide");
                $("#infoBookRoom").removeClass("hidden");
                $("#infoBookRoom").find("#lblNameContact").text(data[0].nameContact);
                $("#infoBookRoom").find("#lblPhoneNumberContact").text(data[0].phoneNumber);
                $("#infoBookRoom").find("#lblEmailContact").text(data[0].email);
                $("#infoBookRoom").find("#lblNote").text(data[0].note);
                $("#infoBookRoom").find("#lblNameRoom").text(data[0].nameRoom);
                $("#infoBookRoom").find("#lblRoomType").text(data[0].roomType);
                $("#infoBookRoom").find("#lblDayCheckin").text(data[0].dayCheckin);
                $("#infoBookRoom").find("#lblDayCheckout").text(data[0].dayCheckout);
                $("#infoBookRoom").find("#lblTimeArrival").text(data[0].timeArrival);
                $("#infoBookRoom").find("#lblNumGuest").text(data[0].numGuest);

                var info_data = '';
                $("#tableListCheckInRoom tr:has(td)").remove();
                $.each(data, function (key, value) {
                    info_data += '<tr>';
                    info_data += '<td>' + bookingCode + '</td>';
                    info_data += '<td >' + value.nameRoom + '</td>';
                    info_data += '<td >' + value.dayCheckin + '</td>';
                    info_data += '<td >' + value.timeArrival + '</td>';
                    if (value.status == 1) {
                        info_data += '<td id="statusCheckIn">' + "Chờ nhận phòng" + '</td>';
                        info_data += '<td id="selectButton"> <button type="button" class="btn btn-primary btn-sm margin-r-5" id="btnCheckIn" data-booking-room-id="' + value.bookingId + '" data-toggle="modal" data-target="#modal-CheckIn">Nhận phòng</button>';
                        info_data += '<button type="button" class="btn btn-warning btn-sm" id="btnOpenModalAddGuest"  data-booking-room-id="' + value.bookingId + '" data-toggle="modal" data-target="#modal-addGuestInRoom">Thêm thông tin khách hàng</button>' + '</td>';
                    } else if (value.status == 2) {
                        info_data += '<td id="statusCheckIn">' + "Đã nhận phòng" + '</td>';
                        info_data += '<td id="selectButton"> <button type="button" class="btn btn-warning btn-sm" id="btnOpenModalAddGuest"  data-booking-room-id="' + value.bookingId + '" data-toggle="modal" data-target="#modal-addGuestInRoom">Thêm thông tin khách hàng</button>' + '</td>';
                    } else if (value.status == 0) {
                        info_data += '<td class="text-bold text-red">' + "Phòng bị hủy" + '</td>';
                        info_data += '<td>' + '</td>';
                    }
                    info_data += '</tr>';
                });

                $('#tableListCheckInRoom').append(info_data);

            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                $("#refreshButtonSearch").addClass("hide");
                $("#infoBookRoom").addClass("hidden");
                $("#notFoundBookingRoom").removeClass("hide");
                setTimeout(function () { $("#notFoundBookingRoom").addClass("hide"); }, 3000);
            }


        })

    })
}

function confirmCheckIn() {
    $("#confirmCheckIn").on("click", function () {
        $("#refreshButtonConfirm").removeClass("hide");
        var idBookingRoom = $("#btnCheckIn").data("booking-room-id");
        $.ajax({
            type: 'POST',
            url: '/ajax/room/confirmcheckin',
            data:
            {
                'idBookingRoom': idBookingRoom,
            },
            datatype: 'json',
            success: function (data, textStatus, xhr) {

                $("#refreshButtonConfirm").addClass("hide");
                $("#modal-CheckIn").modal('hide');
                $("#checkInSuccess").removeClass("hide");
                setTimeout(function () { $("#checkInSuccess").addClass("hide"); }, 3000);
                $("#btnCheckIn").remove();
                $("#statusCheckIn").text("Đã nhận phòng");

            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
                $("#refreshButtonConfirm").addClass("hide");
                $("#checkInFail").removeClass("hide");
                setTimeout(function () { $("#checkInFail").addClass("hide"); }, 3000);
            }


        })
    })

}

function cancelBookRoom() {

    $(".btnCancelBookRoom").on("click", function () {
        var codeBooking = $(this).data("booking-code");
        var idBookingRoom = $(this).data("booking-room-id");
        $("#displayCodeBooking").text(codeBooking);
        $("#inputIdBookingRoom").val(idBookingRoom);

    })

    $("#confirmCancel").on("click", function () {
        var idBookingRoom = $("#inputIdBookingRoom").val();
        $("#refreshButtonCancel").removeClass("hide");
        $.ajax({
            type: 'POST',
            url: '/ajax/room/cancelbookroom',
            data:
            {
                'idBookingRoom': idBookingRoom,
            },
            datatype: 'json',
            success: function (data, textStatus, xhr) {

                $("#refreshButtonCancel").addClass("hide");
                $("#modal-cancel").modal('hide');
                $("#lblSuccess").removeClass("hide");
                $("#lblNotification").text("Hủy phòng thành công !");
                setTimeout(function () {
                    $("#lblSuccess").addClass("hide");
                    location.reload(true);
                }, 2000);

            },
            error: function (xhr, textStatus, errorThrown) {
                console.log(errorThrown);
            }


        })
    })

}

function checkGuest() {
    $("#strangerGuest").on("click", function () {
        $("#searchGuest").addClass("hidden");
        $("#labelSearchIdCard").addClass("hidden");

        $("#searchGuest").removeAttr('readonly');
        $("#name").removeAttr('readonly');
        $('#gender').removeAttr('disabled');
        $("#yearOfBirth").removeAttr('readonly');
        $("#phoneNumber").removeAttr('readonly');
        $("#address").removeAttr('readonly');
        $("#idCardNo").removeAttr('readonly');
        $("#idCardIssuePlace").removeAttr('readonly');
        $("#idCardIssueDate").removeAttr('disabled');
        $("#idCardExpiryDate").removeAttr('disabled');

        $("#infoGuest")[0].reset();


        $("#modal-addGuestInRoom").find("#valueParams").val("stranger");

    })
    $("#familiarGuest").on("click", function () {

        $("#searchGuest").removeClass("hidden");
        $("#labelSearchIdCard").removeClass("hidden");

        $("#name").attr('readonly', 'readonly');
        $('#gender').attr('disabled', 'disabled');
        $("#yearOfBirth").attr('readonly', 'readonly');
        $("#phoneNumber").attr('readonly', 'readonly');
        $("#address").attr('readonly', 'readonly');
        $("#idCardNo").attr('readonly', 'readonly');
        $("#idCardIssuePlace").attr('readonly', 'readonly');
        $("#idCardIssueDate").attr('disabled', 'disabled');
        $("#idCardExpiryDate").attr('disabled', 'disabled');


        $("#infoGuest")[0].reset();

        $("#modal-addGuestInRoom").find("#valueParams").val("familiar");


    })
}

function searchGuest() {
    if ($("#searchGuest").length) {
        var cache = {};
        $("#searchGuest").autocomplete({
            minLength: 0,
            source: function (request, response) {
                var term = request.term;
                if (term in cache) {
                    response(cache[term]);
                    return;
                }
                $.ajax({
                    type: 'POST',
                    url: '/ajax/room/getguestbyterm',
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function (data) {
                        cache[term] = data;
                        response(data);
                        console.log(data);

                    }
                });
            },
            select: function (event, ui) {
                console.log(ui.item);
                // $("#modalOrderRoom").find("#idGuest").val(ui.item.id);
                $("#searchGuest").val(ui.item.name);
                $('#name').val(ui.item.name);
                $('#gender').val(ui.item.gender);
                $('#yearOfBirth').val(ui.item.yearOfBirth);
                $('#phoneNumber').val(ui.item.phoneNumber);
                $('#address').val(ui.item.address);
                $('#idCardNo').val(ui.item.idCardNo);
                $('#idCardIssueDate').val(ui.item.idCardIssueDate);
                $('#idCardIssuePlace').val(ui.item.idCardIssuePlace);


                if (ui.item.idCardExpiryDate) {
                    var expiryDate = formatDate(ui.item.idCardExpiryDate.date);
                    $('#idCardExpiryDate').val(expiryDate);
                }
                if (ui.item.idCardIssueDate) {
                    var issueDate = formatDate(ui.item.idCardIssueDate.date);
                    $('#idCardIssueDate').val(issueDate);
                }

                return false;
            }
        }).autocomplete("instance")._renderItem = function (ul, item) {
            return $("<li>")
                .append("<div>" + item.name + "</div>")
                .appendTo(ul);
        };

        $("#searchGuest").autocomplete("option", "appendTo", "#infoGuest");
    }

}

function addGuestAndGuestInRoom() {
    $("#refreshButtonAddGuest").removeClass("hide");
    var data = $("#infoGuest").serialize();
    var idBookingRoom = $("#btnOpenModalAddGuest").data("booking-room-id");
    data += '&idBookingRoom=' + idBookingRoom;

    if (!validateInfoGuest()) {
        $("#refreshButtonAddGuest").addClass("hide");
        return 0;
    }

    $.ajax({
        type: 'POST',
        url: '/ajax/room/addguestandguestinroom',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            console.log("Error");
            $("#refreshButtonAddGuest").addClass("hide");
            $("#addGuestFail").removeClass("hide");
            setTimeout(function () { $("#addGuestFail").addClass("hide"); }, 3000);
        },
        success: function (data, textStatus, xhr) {
            $("#refreshButtonAddGuest").addClass("hide");
            $("#addGuestSuccess").removeClass("hide");
            setTimeout(function () { $("#addGuestSuccess").addClass("hide"); }, 3000);
            $("#infoGuest")[0].reset();

            var info_data = '';
            info_data += '<tr>';
            info_data += '<td>' + data.guestId.name + '</td>';
            info_data += '<td >' + data.guestId.gender + '</td>';
            info_data += '<td >' + data.guestId.yearOfBirth + '</td>';
            info_data += '<td >' + data.guestId.idCardNo + '</td>';
            info_data += '<td >' + data.guestId.phoneNumber + '</td>';
            info_data += '<td >' + data.guestId.address + '</td>';
            info_data += '</tr>';
            $('#tableListGuestInRoom').append(info_data);


            $("#notDataGuest").remove();


        },

    })

}

function loadInfoGuestToTable() {
    var idBookingRoom = $("#btnOpenModalAddGuest").data("booking-room-id");
    $.ajax({
        type: 'POST',
        url: '/ajax/room/getinfoguestinroom',
        data:
        {
            'idBookingRoom': idBookingRoom,
        },
        datatype: 'json',
        success: function (data, textStatus, xhr) {
            console.log(data);

            var info_data = '';
            $("#tableListGuestInRoom tr:has(td)").remove();
            $.each(data, function (key, value) {
                info_data += '<tr>';
                info_data += '<td>' + value.name + '</td>';
                info_data += '<td >' + value.gender + '</td>';
                info_data += '<td >' + value.yearOfBirth + '</td>';
                info_data += '<td >' + value.idCardNo + '</td>';
                info_data += '<td >' + value.phoneNumber + '</td>';
                info_data += '<td >' + value.address + '</td>';
                info_data += '</tr>';
            });
            $('#tableListGuestInRoom').append(info_data);

        },
        error: function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
            // $("#refreshButtonConfirm").addClass("hide");
            // $("#checkInFail").removeClass("hide");
            // setTimeout(function () { $("#checkInFail").addClass("hide"); }, 3000);
            var info_data = '';
            info_data += '<tr>';
            info_data += '<td colspan="6" class="text-center" id="notDataGuest">' + "Chưa có dữ liệu ..." + '</td>';
            info_data += '</tr>';
            $('#tableListGuestInRoom').append(info_data);
        }


    })

}

function findRoomEmpty(dayCheckin, dayCheckout, roomType) {
    var data = "dayCheckin=" + dayCheckin + "&dayCheckout=" + dayCheckout + "&roomType=" + roomType;

    $.ajax({
        type: 'POST',
        url: '/ajax/room/findroomempty',
        dataType: 'json',
        data,
        error: function (xhr, textStatus, errorThrown) {
            var info_data = '';
            $("#tableRoomEmpty tr:has(td)").remove();
            info_data += '<tr>';
            info_data += '<td colspan="7" class="text-center">' + '<p> Không có phòng trống  ... </p>' + '</td>';
            info_data += '</tr>';

            $('#tableRoomEmpty').append(info_data);
            $(".overlay").removeClass("show");
            $(".overlay").addClass("hide");
            $("tbody").css("opacity", "1");
            $("#btnSaveChoseEmptyRoom").attr("disabled", true);
        },
        success: function (data, textStatus, xhr) {
            var info_data = '';
            $("#tableRoomEmpty tr:has(td)").remove();
            $.each(data, function (key, value) {
                info_data += '<tr>';
                info_data += '<td>' + value.roomName + '</td>';
                info_data += '<td >' + value.roomType + '</td>';
                info_data += '<td>' + '<input type="radio" name="chooseRoom"  data-room-type="' + value.idRoomType + '" data-room-id="' + value.id + '" data-room-name="' + value.roomName + '"> <br>' + '</td>';
                info_data += '</tr>';

            });
            $('#tableRoomEmpty').append(info_data);
            $(".overlay").removeClass("show");
            $(".overlay").addClass("hide");
            $("tbody").css("opacity", "1");


        },
    });


}

function changeRoom(dayCheckin, dayCheckout, idBookingRoom, idRoomOld, idRoomNew, idBill, idRoomTypeNew, idRoomTypeOld) {
    var data = "dayCheckin=" + dayCheckin + "&dayCheckout=" + dayCheckout +
        "&idBookingRoom=" + idBookingRoom + "&idRoomOld=" + idRoomOld + "&idRoomNew=" + idRoomNew +
        "&idBill=" + idBill + "&idRoomTypeNew=" + idRoomTypeNew + "&idRoomTypeOld=" + idRoomTypeOld;

    $.ajax({
        type: 'POST',
        url: '/ajax/room/changeroom',
        dataType: 'json',
        data,
        error: function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);

        },
        success: function (data, textStatus, xhr) {
            $("#modal-findEmptyRoom").modal('hide');
            $("#lblSuccess").removeClass("hide");
            $("#lblNotification").text("Đổi phòng thành công !");
            setTimeout(function () {
                $("#lblSuccess").addClass("hide");
                location.reload(true);
            }, 2000);
        },
    });
}


