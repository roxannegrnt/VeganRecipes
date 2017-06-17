$().ready(function () {
    $(".filterRecipes li").on("click", function (e) {
        FilterByType(e);
    });
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
function onLoadDropdown() {
    $('#dropdownMenu1').dropdown();
    $('#dropdownMenu2').dropdown();

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
            $("input[name=searchbyNotValidated]").val(true);
            onLoadDropdown();
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
            $("input[name=searchbyNotValidated]").val(true);
            onLoadDropdown();
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
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $(".YesNo").append("<button type=\"button\" class=\"close\" onclick=RemoveRecipe(this,3) data-dismiss=\"modal\">&times;</button>");
            $("input[name=SearchByUserRecipes]").val(true);
            onLoadDropdown();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function GetMyFav() {
    var Fav = "Favorites";
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'myFav=true',
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $(".alldropdown").hide();
            onLoadDropdown();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function RemoveRecipe(cross, IsIndexhome) {
    console.log($(cross).closest(".YesNo").closest("article").html());
    var id = $(cross).closest(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "CallAjax.php",
        data: {remove: id, indexhome: IsIndexhome},
        success: function () {
            if (IsIndexhome == 0) {
                $(cross).closest(".YesNo").closest("article").remove();
                $(cross).closest(".YesNo").remove();
            }
            else {
                $(cross).closest(".YesNo").parent().closest("article").remove();
            }
            $('#msg').append("<div class=\"alert alert-success col-md-12\"role=\"alert\">recipe deleted successfully</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
            onLoadDropdown();
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
    $(tag).attr('class', '');
    $(tag).addClass("glyphicon glyphicon-star pull-left");
}
/**
 * When star is not hovered change icon to empty star
 * @param {<i>} tag
 */
function OnHoverOutChangeIcon(tag) {
    $(tag).attr('class', '');
    $(tag).addClass("glyphicon glyphicon-star-empty pull-left");
    
}
function Favorite(tag) {
    var id = $(tag).attr("id");
    $(tag).attr('disabled', true);
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'favorite=' + id,
        success: function () {
           $(tag).attr('class', '');
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
           $(tag).attr('class', '');
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
        url: "CallAjax.php",
        data: {commenttext: comment, idRecipe: idR},
        success: function (data) {
            $(button).closest(".collapse").parent().find(".Allcomments").html("");
            $(button).closest(".collapse").parent().find(".Allcomments").html(data);
            $(button).closest(".collapse").find("input[name=comment]").val("");
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function RemoveComment(close) {
    var id = $(close).closest("article").find(".Usercomment").attr("id");
    var idR = $(close).closest(".Allcomments").parent().find(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "CallAjax.php",
        data: {idComment: id, idR: idR},
        success: function (data) {
            $(close).closest(".Allcomments").html(data);
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function FilterByType(e) {
    var FilterRecipes = $(e.target).closest("ul").attr("id");
    var type = "";
    var sort = "";
    if (FilterRecipes === "MealTypes") {
        type = $(e.target).text();
        sort = $("input[name=filterSort]").val();
    }
    else {
        sort = $(e.target).text();
        type = $("input[name=filterType]").val();
    }
    var searchKeyWord = $("input[name=searchKeyWord]").val();
    var isMyRecipes=$("input[name=SearchByUserRecipes]").val();
    var isNotValid=$("input[name=searchbyNotValidated]").val();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {AjaxFilter: true, Filtertype: type, FilterDate: sort, searchKeyWord: searchKeyWord, isMyRecipes:isMyRecipes,isNotValid:isNotValid},
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $("#dropdownMenu1").text((type !== "" ? type : "Filter By"));
            $("#dropdownMenu1").append("<span class=\"caret\"></span>");
            $('#dropdownMenu2').text((sort !== "" ? sort : "Sort By"));
            $("#dropdownMenu2").append("<span class=\"caret\"></span>");
             $("input[name=filterType]").val(type);
              $("input[name=filterSort]").val(sort);
              $("input[name=searchKeyWord]").val(searchKeyWord);
              $("input[name=SearchByUserRecipes]").val(isMyRecipes);
              $("input[name=searchbyNotValidated]").val(isNotValid);
            onLoadDropdown();
            if (isMyRecipes) {
                $(".YesNo").append("<button type=\"button\" class=\"close\" onclick=RemoveRecipe(this,3) data-dismiss=\"modal\">&times;</button>");
            }
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
            url: "CallAjax.php",
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
    var valeur = $("#search-box").val();
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'search=' + valeur,
        async: true,
        beforeSend: function () {
            $("section").html("");
            $("section").append("<img class=\"col-lg-offset-6\" src=\"upload/loaderIcon.gif\" id=\"loader\">");
        },
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $("input[name=searchKeyWord]").val(valeur);
            onLoadDropdown();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">" + error + "</div>").fadeIn('slow'); //also show a success message 
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}



