$().ready(function () {
    CallTitles();
    $('#FileInput').on('change', function (e) {
        prepareUpload(e);
    });
    $('#AddModal').on('hidden.bs.modal', function () {
        CloseModal();
    });
    $('#myModal').on('hidden.bs.modal', function () {
        EmptyModalSignin();
    });
    $('#SignUp').on('hidden.bs.modal', function () {
        EmptyModalSignup();
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
function onLoad() {
    $('#dropdownMenu1').dropdown();
    $('#dropdownMenu2').dropdown();
    $('.collapse').collapse("toggle");
}
function EmptyModalSignin() {
    $('#myModal').modal('hide').data('bs.modal', null);
    $("#signinE").html("");
}
function EmptyModalSignup() {
    $('#SignUp').modal('hide').data('bs.modal', null);
    $("#signupE").html("");
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
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
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
            if (IsIndexhome == 3) {
                $(".YesNo").append("<button type=\"button\" class=\"close\" onclick=RemoveRecipe(this,3) data-dismiss=\"modal\">&times;</button>");
            }
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
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
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
        async: true,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            onLoad();
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
            onLoad();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function FilterByType(link) {
    var type = $(link).text();
    var date = null;
    switch (type) {
        case "All":
            type = "All";
            break;
        case "Last added":
            date = type;
            type = null;
            break;
        case "Oldest post":
            date = type;
            type = null;
            break;
    }
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {Filtertype: type, date: date},
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $("#dropdownMenu1").text((type !== null ? type : "Filter By"));
            $("#dropdownMenu1").append("<span class=\"caret\"></span>");
            $('#dropdownMenu2').text((date !== null ? date : "Filter By"));
            $("#dropdownMenu2").append("<span class=\"caret\"></span>");
            onLoad();

        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function CallTitles() {
    // call AJAX for autocomplete 
    $("#search-box").keyup(function () {
        $.ajax({
            type: "POST",
            url: "ajaxcallAutocomplete.php",
            data: 'keyword=' + $(this).val(),
            async: true,
            beforeSend: function () {
                $("#search-box").addClass(".loading");
            },
            success: function (data) {
                $("#suggesstion-box").show();
                $("#suggesstion-box").html(data);
            }
        });
    });
}
/**
 * To select title of recipe
 */
function selectTitle(val) {
    $("#search-box").val(val);
    $("#suggesstion-box").hide();
}
/**
 * Sends complete search and returns results
 */
function SubmitSearch() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'search=' + $("#search-box").val(),
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            onLoad();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}



