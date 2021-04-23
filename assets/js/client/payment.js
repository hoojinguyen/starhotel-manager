$(document).ready(function () {


    $("#btnPayment").on('click', function () {
        var url = "/client/finish" + location.search;
        window.location.href = url;
    })
    
});

