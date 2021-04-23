$(document).ready(function () {


    $("#btnBookSingleRoom").on('click', function () {
        var roomId = $(this).data('room-id');
        var roomType =$(this).data('room-type');
        changeUrl(roomId,roomType);
    })

    $("#btnBookDoubleRoom").on('click', function () {
        var roomId = $(this).data('room-id');
        var roomType =$(this).data('room-type');
        changeUrl(roomId,roomType);
    })


});

function changeUrl(roomId,roomType){
    var url = "/client/bookroom" + location.search +"&roomType="+ roomType + "&roomId="+ roomId  ;
    $('a').attr("href", url);
}