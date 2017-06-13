$().ready(function () {
    $('#FileInput').on('change', function (e) {
        prepareUpload(e);
    });
    $('#AddModal').on('hidden.bs.modal', function () {
        CloseModal();
    });
});

function prepareUpload(event) {
    var files = event.target.files;
    if (typeof window.FileReader !== 'undefined') {
        reader = new FileReader();
        //event onload s'execute à la fin de la function chargeFiles
        reader.onload = function (event) {
            $("#AddPhoto").attr('src', event.target.result); // show image tumbnail
        };
        reader.readAsDataURL(files[0]);
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
            $('#msg').delay(1000).fadeOut('slow');
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
            $('#msg').delay(1000).fadeOut('slow');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function GetMyRecipes() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'myrecipes=true',
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $(".YesNo").append("<button type=\"button\" class=\"close\" onclick=RemoveRecipe(this,3) data-dismiss=\"modal\">&times;</button>");
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function RemoveRecipe(cross, IsIndexhome) {
    var id = $(cross).closest(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {remove: id, indexhome: IsIndexhome},
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $('#msg').append("<div class=\"alert alert-success col-md-12\"role=\"alert\">recipe deleted successfully</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}

/**
 * When star is hovered change icon to full star
 * @param {<i>} tag
 */
function OnHoverChangeIcon(tag) {
    $(tag).removeClass();
    $(tag).addClass("glyphicon glyphicon-star pull-left");
}
/**
 * When star is not hovered change icon to empty star
 * @param {<i>} tag
 */
function OnHoverOutChangeIcon(tag) {
    $(tag).addClass("glyphicon glyphicon-star-empty pull-left");
    $(tag).RemoveClass();
}
function Favorite(tag) {
    var id = $(tag).attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'favorite=' + id,
//        beforeSend: function () {
//            $(".loader").css("background", "#FFF url(loaderIcon.gif) no-repeat 165px");
//        },
        success: function () {
            $(tag).removeClass();
            $(tag).addClass("glyphicon glyphicon-star pull-left");
            $(tag).on('click', function () {
                UnFavorite(tag);
            });
            $(tag).attr('onmouseover', '');
            $(tag).attr('onmouseout', '');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function UnFavorite(tag) {
    var id = $(tag).attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'Unfavorite=' + id,
        success: function () {
            $(tag).removeClass();
            $(tag).addClass("glyphicon glyphicon-star-empty pull-left");
            $(tag).on('click', function () {
                Favorite(tag);
            });
            $(tag).attr('onmouseover', 'OnHoverChangeIcon(this)');
            $(tag).attr('onmouseout', 'OnHoverOutChangeIcon(this)');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}

function AddComment(button) {
    var idR = $(button).closest(".collapse").attr("id");
    var comment = $(button).closest(".collapse").find("input[name=comment]").val();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {commenttext: comment, idRecipe: idR},
        success: function (data) {
            $("body").html("");
            $("body").html(data);
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function RemoveComment(close) {
    var id = $(close).closest("article").find(".Usercomment").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'idComment=' + id,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function FilterByType(link) {
    var type = $(link).text();
    if (type == "All") {
        var type = "";
    }
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'type=' + type,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $('#dropdownMenu1').dropdown();
            $('#dropdownMenu2').dropdown();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}


