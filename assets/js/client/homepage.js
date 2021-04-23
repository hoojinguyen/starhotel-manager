$(document).ready(function () {


    $("#checkRoom").on('click', function () {
        var dayCheckin = $("#dayCheckin").val();
        var dayCheckout = $("#dayCheckout").val();
        var adult = $("#adult").val();
        var child = $("#child").val();
        var url = "/client/roomlistings?access=true" + "&dayCheckin=" + dayCheckin + "&dayCheckout=" + dayCheckout + "&adult=" + adult + "&child=" + child;

        if (dayCheckin == "" || dayCheckout == "") {
            $("#dayCheckin").datepicker('show');  
        }
        else {
            $('a').attr("href", url);
        }


    })


});