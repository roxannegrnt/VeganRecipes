$().ready(function () {
    $("#ImgProfil").click(function () {
        ChangerAvatar();
    });
});
function ChangerAvatar() {
    $("#frmAjout").append("<div class=\"HideInput\"><input type=\"file\" class=\"form-control\" name=\"upload\" id=\"imgRecipe\" accept=\"image/*\"><div>");
    $(".HideInput").hide();
    $("#imgRecipe").trigger("click");
}
function GetRecipesToValidate() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'tovalidate=true',
        success: function (data) {
            $("body").html("");
            $("body").html(data);
        }
    });
}
function ValidateRecipe() {
    var id = $(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'validate=' + id,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
        }
    });
}
function RemoveRecipe() {
   var id = $(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'remove=' + id,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
        }
    });
}


