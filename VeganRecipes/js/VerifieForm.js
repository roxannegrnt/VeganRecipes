$(document).ready(function () {
    $('#title').keydown(function(event){
        if ((/[^A-Za-z0-9'-°/():,.~:]/gi.test($("#descrip").val())) || ($("#descrip").val() === "")) {
            $('.textareaD').css('border-color','red');
        }
        if ((/[^A-Za-z0-9'-°/():,.~:]/gi.test($("#descrip").val())) || ($("#descrip").val() === "")) {
            $('.textareaD').css('border-color','red');
        }
        if ((/[^A-Za-z]/gi.test($("#title").val())) || ($("#title").val() === "")) {
            $('.inputT').css('border-color','red');
        }
        else {
            $('.inputT').removeClass('has-error');
        }
    });
});

