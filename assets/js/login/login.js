$(document).ready(function(){
    Validate();
    $("#loginBtn").on('click', function(){
        Validate();
    })
})

function Validate() {
    $("#login-form").validate({
        errorClass: "error",
        successClass: "success",
        rules:{
            email: {
                required: true,
                minlength: 6,
                email: true,
            },
            password:{
                required: true,
                minlength: 6,
            }
        }
    })
}