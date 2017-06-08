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
            $("#Errors").append("<div class=\"alert alert-sucess\">Recipe added with sucess</div>");
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
            $("#Errors").append("<div class=\"alert alert-sucess\">Recipe removed with sucess</div>");
            $("body").html(data);
        }
    });
}

/**
 * When star is hovered change icon to full star
 * @param {<i>} tag
 */
function OnHoverChangeIcon(tag) {
    $(tag).removeClass("glyphicon glyphicon-star-empty");
    $(tag).addClass("glyphicon glyphicon-star");
}
/**
 * When star is not hovered change icon to empty star
 * @param {<i>} tag
 */
function OnHoverOutChangeIcon(tag) {
    $(tag).addClass("glyphicon glyphicon-star-empty");
    $(tag).RemoveClass("glyphicon glyphicon-star");
}


