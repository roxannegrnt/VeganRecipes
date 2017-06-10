$(document).ready(function () {
    if ((/[^A-Za-z0-9'-°/():,.~:]/gi.test($("#descrip").val())) || ($("#descrip").val() === "")) {
            $('textareaD').addClass('has-error');
        }
});

