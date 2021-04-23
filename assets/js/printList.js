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

    $('#confirm-bill').on('click',function(){
        var pageTitle = 'Hóa đơn thanh toán',
            stylesheet = '//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css',
            newWin = window.open('');
            newWin.document.write('<html><head><title>' + pageTitle + '</title>' +
            '<link rel="stylesheet" href="' + stylesheet + '">' +
            '</head><body>' + $('#billRoom')[0].outerHTML + '</body></html>');
            newWin.document.close();
            newWin.print();
            newWin.close();
            $("#modal-bill").modal('hide');
            $('#modal-checkout').modal('hide');
            location.reload();

        return false;
    })
   
});