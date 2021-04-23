function formatMoney(amount, decimalCount = 2, thousands = ",") {
    try {
        decimalCount = Math.abs(decimalCount);
        decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

        const negativeSign = amount < 0 ? "-" : "";

        let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
        let j = (i.length > 3) ? i.length % 3 : 0;

        return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + " VNĐ";
    } catch (e) {
        console.log(e)
    }
}

function validateAllInfo() {
    let infoContact = $("#findAndBookRoom");
    if ($("#findAndBookRoom").length) {
        let validator = infoContact.validate({
            errorClass: "has-error",
            // validClass: "has-success",
            rules: {
                nameContact: {
                    required: true,
                },
                phoneNumberContact: {
                    required: true,
                    digits: true
                },
                expectedCheckinDay: {
                    required: true,

                },
                expectedCheckoutDay: {
                    required: true,

                },
                nameRoom: {
                    required: true,
                },


            }
        });

        return validator.form();
    }
    return true;
}

function validateInfoRequire() {
    let infoRequire = $("#findAndBookRoom");
    if ($("#findAndBookRoom").length) {
        let validator = infoRequire.validate({
            errorClass: "has-error",
            // validClass: "has-success",
            rules: {
                expectedCheckinDay: {
                    required: true,
                },
                expectedCheckoutDay: {
                    required: true,
                }

            }
        });

        return validator.form();
    }
    return true;
}

// function inputDateTimePicker() {
//     $("#dayCheckin").datetimepicker({
//         timepicker: false,
//         format: 'Y-m-d'

//     });
//     $("#dayCheckout").datetimepicker({
//         timepicker: false,
//         format: 'Y-m-d'

//     });

//     $("#timeArrival").datetimepicker({
//         datepicker: false,
//         format: 'H:m',

//     });
// }

function cancel() {
    $("#findAndBookRoom")[0].reset();
    $("#tableDayRate tr:has(td)").remove();
    $("#tableRoomEmpty tr:has(td)").remove();
    var strRoomEmpty = '<tbody>' + '<td colspan="7" class="text-center">' + '<p id="noData">	Chưa có dữ liệu ... </p>' + '<div class="overlay hide">' + '<i class="fa fa-refresh fa-spin fa-2x"></i>' + '	</div>' + '</td>' + '</tbody>';
    var strPriceAndDay = '<tbody>' + '<td colspan="7" class="text-center">' + '<p id="noData">	Chưa có dữ liệu ... </p>' + '<div class="overlay hide">' + '<i class="fa fa-refresh fa-spin fa-2x"></i>' + '	</div>' + '</td>' + '</tbody>';
    $('#tableRoomEmpty').append(strRoomEmpty);
    $('#tableDayRate').append(strPriceAndDay);



}


function findRoomEmpty() {
    if (!validateInfoRequire()) {
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        $("#noData").html("Chưa có dữ liệu ...");
        $("tbody").css("opacity", "1")
        return 0;
    }

    var data = $("#findAndBookRoom").serialize();
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


function bookRoom() {
    if (!validateAllInfo()) {
        $(".overlay").removeClass("show");
        $(".overlay").addClass("hide");
        return 0;
    }
    $("#refreshButtonBookRoom").removeClass("hide");

    var data = $("#findAndBookRoom").serialize();
    $.ajax({
        type: 'POST',
        url: '/ajax/room/savebookroom',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            console.log("Error");
            $("#refreshButtonBookRoom").addClass("hide");
            $("#bookRoomFail").removeClass("hide");
            setTimeout(function () { $("#bookRoomFail").addClass("hide"); }, 3000);
        },
        success: function (data, textStatus, xhr) {
            sendEmail();
            $("#refreshButtonBookRoom").addClass("hide");
            $("#bookRoomFail").addClass("hide");
            $("#bookRoomSucces").removeClass("hide");
        },

    });

}

function sendEmail() {
    var data = $("#findAndBookRoom").serialize();
    $.ajax({
        type: 'GET',
        url: '/admin/mail',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        success: function (data, textStatus, xhr) {
            // console.log("send mail Success!");
        },

    });
}

function displayDateAndPrice() {
    var data = '';
    var dayCheckin = $("#findAndBookRoom").find("#dayCheckin").val();
    var dayCheckout = $("#findAndBookRoom").find("#dayCheckout").val();
    var roomId = $("input:checked").data('room-id');
    data += 'roomId=' + roomId + '&dayCheckin=' + dayCheckin + '&dayCheckout=' + dayCheckout;

    $.ajax({
        type: 'POST',
        url: '/ajax/quotation/day',
        dataType: 'json',
        data
    })
        .done(function (result) {
            var info_data = '';
            $("#tableDayRate tr:has(td)").remove();
            let totalAmount = 0;
            $.each(result, function (key, value) {
                var amount = formatMoney(value.amount);
                info_data += '<tr>';
                info_data += '<td >' + value.date + '</td>';
                info_data += '<td >' + amount + '</td>';
                info_data += '</tr>';

                totalAmount += value.amount;
            });


            info_data += '<td class="text-bold">' + ' Tổng chi phí:' + '</td> ';
            info_data += '<td class="text-bold">' + formatMoney(totalAmount) + '</td>';
            $('#tableDayRate').append(info_data);
            $("#modal-findEmptyRoom").modal('hide');

            $(".overlay").removeClass("show");
            $(".overlay").addClass("hide");
            $("tbody").css("opacity", "1")
        });
}

// function changeRentType() {
//     $('#rentType').on('change', function () {
//         var val = this.value;
//         if (val == 1) {
//             $("#expectedCheckinDay").removeAttr("disabled");
//             $("#expectedCheckoutDay").removeAttr("disabled");

//             $("#expectedCheckinTime").attr("disabled", true);
//             $("#expectedCheckoutTime").attr("disabled", true);
//             clearText()

//         }
//         else {
//             $('#expectedCheckinTime').removeAttr("disabled");
//             $('#expectedCheckoutTime').removeAttr("disabled");
//             clearText()
//         }
//     });
// }

// function clearText() {
//     $("#expectedCheckinTime").text("");
//     $("#expectedCheckoutTime").text("");
//     $("#expectedCheckinDay").text("");
//     $("#expectedCheckoutDay").text("");
// }



$(document).ready(function () {
    $(document).on('focus', ':input', function () {
        $(this).attr('autocomplete', 'off');
    });


    $(".overlay").removeClass("show");
    $(".overlay").addClass("hide");

    // inputDateTimePicker();

    // Click find room empty
    $("#btnFindRoomEmpty").on("click", function () {
        $(".overlay").removeClass("hide");
        $(".overlay").addClass("show");
        $("#noData").html("");
        $("tbody").css("opacity", "0.5");
        findRoomEmpty();
    })


    // Chose room want to booking
    $("#btnSaveChoseEmptyRoom").on("click", function () {
        var nameRoom = $("input:checked").data('room-name');
        var idRoom = $("input:checked").data('room-id');
        var idRoomType = $("input:checked").data('room-type');
        $("#nameRoom").val(nameRoom);
        $("#idRoom").val(idRoom);
        $("#roomType").val(idRoomType);
        displayDateAndPrice();
    })

    // Booking
    $("#btnBookRoom").on("click", function () {
        bookRoom();
        // $("#bookRoomSucces").removeClass("hide");

    })

    // Cancel and reload page
    $("#btnCancelBookRoom").on("click", function () {
        location.reload(true);
    })

    // Confirm or no confirm redirct page checkin
    $("#btnNoRedirectCheckin").on("click", function () {
        $("#bookRoomSucces").addClass("hide");
        location.reload(true);
    })
    $("#btnRedirectCheckInPage").on("click", function () {
        $("#bookRoomSucces").addClass("hide");
        var bookingCode = $("#findAndBookRoom").find("#bookingCode").val();
        var url = "/admin/room/checkin?bookingCode=" + bookingCode;
        window.location.href = url;

    })



})