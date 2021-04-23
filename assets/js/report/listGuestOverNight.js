$(document).ready(function(){
    $('#printList').on('click',function(){
        var pageTitle = 'Danh Sách Khách hàng lưu trú qua đêm',
            stylesheet = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
            newWin = window.open('');
            newWin.document.write('<html><head><title>' + pageTitle + '</title>' +
            '<link rel="stylesheet" href="' + stylesheet + '">' +
            '</head><body>' + $('.table')[0].outerHTML + '</body></html>');
            newWin.document.close();
            newWin.print();
            newWin.close();
        return false;
    })

    


$("#btnSearchDateExport").on('click',function(){
   var searchTime = $("#dateExportList").val();
   $.ajax({
    type: 'POST',
    url: '/ajax/room/loadinfoguestovernight',
    dataType: 'json',
    data: {
        'searchTime': searchTime
    },
    error: function (xhr, textStatus, errorThrown) {
        var info_data = '';
        $("#listGuestOverNight tr:has(td)").remove();
            info_data += '<tr>';
            info_data += '<td colspan="12" class="text-center">' + '<p> Không có khách qua đêm trong khoảng thời gian này  ... </p>' + '</td>';
            info_data += '</tr>';

        $('#listGuestOverNight').append(info_data);
    },
    success: function (data, textStatus, xhr) {
        
        var info_data = '';
        $("#listGuestOverNight tr:has(td)").remove();
        $.each(data, function (key, value) {
            info_data += '<tr>';
            info_data += '<td>' + value.nameGuest + '</td>';
            info_data += '<td >' + value.idCardNo + '</td>';
            info_data += '<td >' + value.address + '</td>';
            info_data += '<td >' + value.nameRoom + '</td>';
            info_data += '<td >' + value.dayCheckin + '</td>';
            info_data += '<td >' + value.dayCheckout + '</td>';
            info_data += '</tr>';

        });
        $('#listGuestOverNight').append(info_data);
        
        
    },
});
})
inputDateTimePicker();
   
});

function inputDateTimePicker() {
    $("#dateExportList").datetimepicker({
        timepicker: false,
        format: 'd-m-Y'
    });
}