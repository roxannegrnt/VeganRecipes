$().ready(function () {
    $("#Img").click(function () {
        ChangerAvatar();
        IsImgSelected();
    });
    $('#AddModal').on('hidden.bs.modal', function () {
        CloseModal();
    });
});
function ChangerAvatar() {
    $("#frmAjout").append("<div class=\"HideInput\"><input type=\"file\" class=\"form-control\" name=\"upload\" id=\"imgRecipe\" accept=\"image/*\"><div>");
    $(".HideInput").hide();
    $("#imgRecipe").trigger("click");
}
function IsImgSelected() {
    if ($("#imgRecipe").get(0).files.length !== 0) {
        $("#Img").append("<i class=\"glyphicon glyphicon-ok\" id=\"btnImg\"></i>");
    }
    else {
        $("#btnImg").detach();
    }
}
function CloseModal() {
    $("#AddModal input").val("");
    $("#AddModal textarea").val("");
    $(".errorModal").html("");
    window.location = "index.php";
}
function GetRecipesToValidate() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'tovalidate=true',
        success: function (data) {
            $("body").html("");
            $("body").html(data);
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(5000).fadeOut('slow');
        }
    });
}
function ValidateRecipe(tick) {
    var id = $(tick).closest(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'validate=' + id,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $('#msg').append("<div class=\"alert alert-success\"role=\"alert\">recipe validated successfully</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(5000).fadeOut('slow');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(5000).fadeOut('slow');
        }
    });
}
function RemoveRecipe(cross) {
    var id = $(cross).closest(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'remove=' + id,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $('#msg').append("<div class=\"alert alert-success\"role=\"alert\">recipe deleted successfully</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(5000).fadeOut('slow');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(5000).fadeOut('slow');
        }
    });
}

/**
 * When star is hovered change icon to full star
 * @param {<i>} tag
 */
//function OnHoverChangeIcon(tag) {
//    $(tag).removeClass();
//    $(tag).addClass("glyphicon glyphicon-star");
//}
/**
 * When star is not hovered change icon to empty star
 * @param {<i>} tag
 */
//function OnHoverOutChangeIcon(tag) {
//    $(tag).addClass("glyphicon glyphicon-star-empty");
//    $(tag).RemoveClass();
//}
function Favorite(tag) {
    var id = $(tag).attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'favorite=' + id,
        success: function () {
            $(tag).addClass("glyphicon glyphicon-star");
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(5000).fadeOut('slow');
        }
    });
}


