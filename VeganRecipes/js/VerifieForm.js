$(document).ready(function () {
    if ((/[^A-Za-z0-9'-°/():,.~:]/gi.test($("#descrip").val())) || ($("#descrip").val() === "")) {
            $('#descrip').addClass('has-error');
        }
});

