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
function GetRecipesToValidate(){
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


