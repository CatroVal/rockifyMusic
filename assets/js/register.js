$(document).ready(function() {
    $("#hideLogin").click(function() {
        console.log("log in ha sido presionado");
        $("#loginForm").hide();
        $("#registerForm").show();
    });

    $("#hideRegister").click(function() {
        console.log("sign in ha sido presionado");
        $("#loginForm").show();
        $("#registerForm").hide();
    });
});
