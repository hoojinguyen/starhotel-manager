$(document).ready(function () {

    $("#btnBookRoomNow").on('click', function () {
        if ($("#nameContact").val() !== "" && $("#emailContact").val() !== "" && $("#phoneNumberContact").val() !== "") {
            bookRoom();
        }
    })


    $("#btnApplyDiscount").on('click', function () {
        checkDiscount();
    })


});

function bookRoom() {
    var data = $("#bookRoomNow").serialize();

    var timeArrival = $("#timeArrival").val();
    var dayCheckin = $("#dayCheckin").val();
    var dayCheckout = $("#dayCheckout").val();
    var bookingCode = $("#bookingCode").val();
    var emailContact = $("#emailContact").val();
    var roomType = $("#roomType").val();
    var roomId = $("#idRoom").val();
    var valueDiscount = $("#valueDiscount").val();
    var url = "/client/payment?access=true" + 
    "&dayCheckin=" + dayCheckin + "&dayCheckout=" + dayCheckout + "&timeArrival=" + timeArrival + 
     "&bookingCode=" + bookingCode + "&emailContact=" + emailContact + "&valueDiscount=" + valueDiscount +
     "&roomType=" + roomType + "&roomId=" + roomId;

    $.ajax({
        type: 'POST',
        url: '/client/ajax/savebookroom',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
            alert("Đặt phòng không thành công!")
        },
        success: function (data, textStatus, xhr) {
            sendEmail();

            window.location.href = url;
        },

    });
}

function sendEmail() {
    var data = $("#bookRoomNow").serialize();
    $.ajax({
        type: 'GET',
        url: '/admin/mail',
        data,
        dataType: 'json',
        error: function (xhr, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        success: function (data, textStatus, xhr) {
            console.log("send mail Success!");
        },

    });
}

function checkDiscount() {
    var discount = $("#discount").val();
    var priceTotal = $("#priceTotal").val();

    var data = 'discount=' + discount + "&priceTotal=" + priceTotal ;
    $.ajax({
        type: 'POST',
        url: '/client/ajax/checkdiscount',
        dataType: 'json',
        data,
        error: function (xhr, textStatus, errorThrown) {
            $("#hideDiscount").addClass("hidden");
            $("#lblNotExitsDiscount").removeClass("hidden");

            $("#valueDiscount").val("");
            setTimeout(function () {
                $("#lblNotExitsDiscount").addClass("hidden");
            }, 3000);
        },
        success: function (data, textStatus, xhr) {
            $("#hideDiscount").removeClass("hidden");
   
            $("#lblValueDiscount").text(data.value);
            $("#lblPriceDiscount").text(data.priceDiscountVND);
            $("#lblTotalPriceDisplay").text(data.priceTotalVND);
            
            $("#valueDiscount").val(data.value);
        },
    });
}

