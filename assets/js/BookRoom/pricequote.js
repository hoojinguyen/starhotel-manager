$(document).ready(function(){
    console.log($("#rentInfo").find("#rentType").val());
    $(".overlay").css('display', 'none')
    checkRentType();
    $("#rentInfo").find("#rentType").on('change', function(){
        checkRentType();
    })

    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass   : 'iradio_minimal-blue'
      })

    validateRequest();

    $("#quotationButton").on('click', function(){

        if(!validateRequest()){
            return false;
        }
        data = $("#rentInfo").serialize();
        if(data.indexOf("isAfter23h") == -1){
            data += "&isAfter23h=off"
        }
        if(data.indexOf("isWeekend") == -1){
            data += "&isWeekend=off"
        }
        var rentType = $("#rentType").val();
        $(".overlay").css('display', 'block')
        $.ajax({
            type: 'POST',
            dataType:'json',
            url: '/ajax/room/pricequote',
            data,
            success: function(data){
                dataJson = JSON.parse(data);

                if(rentType == 1){
                    $("#byHour").removeClass("hide");
                    $("#byDayOrNight").addClass("hide");

                    $("#firstHourPrice").text(formatMoney(dataJson.firstHour));
                    $("#nextHourPrice").text(formatMoney(dataJson.nextHour));
                    $("#surchargeHourPrice").text(formatMoney(dataJson.surcharge));
                    $("#totalHourPrice").text(formatMoney(dataJson.total));

                }
                else{
                    $("#byHour").addClass("hide");
                    $("#byDayOrNight").removeClass("hide");

                    $("#dayOrNightPrice").text(formatMoney(dataJson.priceDayOrNight));
                    $("#earlyPrice").text(formatMoney(dataJson.earlyHour));
                    $("#otHourPrice").text(formatMoney(dataJson.OTHour));
                    $("#surchargeDayOrNightPrice").text(formatMoney(dataJson.surcharge));
                    $("#totalDayOrNightPrice").text(formatMoney(dataJson.total));

                }


                $(".overlay").css('display', 'none')
            }
        })


        console.log(data);
    })
})

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

function checkRentType(){
    if ($("#rentType").val() == "1"){
        $("#hours").css("display", 'block');        
        $(".extraHour").css('display','none') 
        $("#isAfter23h").css('display','block')
        //$("#priceList #changeText").text("Sau 23h")        

    } else {
        $(".extraHour").css('display','block')   
        $("#isAfter23h").css('display','none')

        //$("#priceList #changeText").text("Cuối tuần")        
        $("#hours").css("display", 'none');
    }
}

function validateRequest(){
    let validator = $("#rentInfo").validate({
        rules: {
            people: {
                min: 1,
                required: true
            },
            OTHour: {
                min: 0,
                required: true
            },
            earlyHour: {
                min: 0,
                required: true
            },
            hours: {
                min: 1,
                required: true
            }
        },
        messages: {
            people: "Phải có ít nhất 1 người !",
            OTHour: "Giờ lố không hợp lệ !",
            earlyHour: "Giờ vào sớm không hợp lệ !",
            hours: "Phải thuê ít nhất 1 giờ !"
        }
    })
    if(validator.form()){
        return true;
    } else {
        return false;
    }
}
