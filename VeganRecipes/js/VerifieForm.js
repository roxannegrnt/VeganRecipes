$(document).ready(function () {
    $('frmAjout').bind('input propertychange', function () {
        if ((/[^A-Za-z0-9'-°/():,.~:]/gi.test($("#descrip").val())) || ($("#descrip").val() === "")) {
            $('.textareaD').addClass('has-error');
        }
        if ((/[^A-Za-z0-9'-°/():,.~:]/gi.test($("#descrip").val())) || ($("#descrip").val() === "")) {
            $('.textareaD').addClass('has-error');
        }
        if ((/[^A-Za-z]/gi.test($("#title").val())) || ($("#title").val() === "")) {
            $('.inputT').addClass('has-error');
        }
        else {
            $('.inputT').removeClass('has-error');
        }
    });
});

