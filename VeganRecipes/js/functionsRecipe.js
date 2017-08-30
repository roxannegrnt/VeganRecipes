/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: DbConnect.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */
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
/**
 * Show preview of image in hit box
 * @param {type} event click on hitbox add
 */
function prepareUpload(event) {
    var files = event.target.files;
    if (typeof window.FileReader !== 'undefined') {
        reader = new FileReader();
        reader.onload = function (event) {
            $("#AddPhoto").attr('src', event.target.result); // show image tumbnail
        };
        reader.readAsDataURL(files[0]);
    }
}
/**
 * Activates dropdown filters
 */
function onLoadDropdown() {
    $('#dropdownMenu1').dropdown();
    $('#dropdownMenu2').dropdown();

}
/**
 * Empty modal signin if closed
 */
function EmptyModalSignin() {
    $('#myModal').modal('hide').data('bs.modal', null);
    $("#signinE").html("");
}
/**
 * Empty modal signup if closed
 */
function EmptyModalSignup() {
    $('#SignUp').modal('hide').data('bs.modal', null);
    $("#signupE").html("");
}
/**
 * Empty insert modal on close
 */
function CloseModal() {
    $("#AddModal input").val("");
    $("#AddModal textarea").val("");
    $(".errorModal").html("");
    window.location = "index.php";
}
/**
 * Ajax call to get recipes to validate
 */
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't get recipes to validate, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Ajax call to validate recipe
 * @param {button} tick clicked button
 */
function ValidateRecipe(tick) {
    //Get id of recipe according to button clicked
    var id = $(tick).closest(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "index.php",
        data: 'validate=' + id,
        success: function (data) {
            $("body").html("");
            $("body").html(data);
            $('#msg').append("<div class=\"alert alert-success\"role=\"alert\">recipe validated successfully</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
            $("input[name=searchbyNotValidated]").val(true);
            onLoadDropdown();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't validate recipe, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Ajax call to get users recipes
 */
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't get my recipes, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Ajax call to get users favorite recipes
 */
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't get my favorites, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Remove selected recipe
 * @param {button} cross clicked button
 * @param {int} IsIndexhome current view on index
 */
function RemoveRecipe(cross, IsIndexhome) {
    //Get id of recipe by button clicked
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
            $('#msg').append("<div class=\"alert alert-success col-md-12\"role=\"alert\">recipe deleted successfully</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
            onLoadDropdown();
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't get remove recipe, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
function EditRecipe(edit){
    var id=$(edit).closest(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "CallAjax.php",
        data: "edit="+id,
        async: true,
        success: function (data) {
            //$('body').html(data);
            $('#editModal .modal-body').html(data);
            $('#editModal').modal('show');
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't get edit recipe, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * When star is hovered change icon to full star
 * @param {<i>} tag clicked star
 */
function OnHoverChangeIcon(tag) {
    $(tag).attr('class', '');
    $(tag).addClass("glyphicon glyphicon-star pull-left");
}
/**
 * When star is not hovered change icon to empty star
 * @param {<i>} tag clicked star
 */
function OnHoverOutChangeIcon(tag) {
    $(tag).attr('class', '');
    $(tag).addClass("glyphicon glyphicon-star-empty pull-left");
    
}
/**
 * Add recipe to favorites
 * @param {button} tag clicked star
 */
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't set recipe as favorite, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * remove recipe from favorites
 * @param {button} tag clicked star
 */
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't unfavorite recipe, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Adds a comment to recipe
 * @param {button} button clicked button
 */
function AddComment(button) {
    //gets id of recipe according to clicked button
    var idR = $(button).closest(".collapse").attr("id");
    //Get value of comment
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't add comment to recipe, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Remove comment from recipe
 * @param {button} close clicked button
 */
function RemoveComment(close) {
    //gets id of comment according to clicked button
    var id = $(close).closest("article").find(".Usercomment").attr("id");
    //gets id of recipe according to clicked button
    var idR = $(close).closest(".Allcomments").parent().find(".YesNo").attr("id");
    $.ajax({
        type: "POST",
        url: "CallAjax.php",
        data: {idComment: id, idR: idR},
        success: function (data) {
            $(close).closest(".Allcomments").html(data);
        },
        error: function (error) {
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't remove comment on recipe, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Filters reipces
 * @param {<ul>} e clicked list
 * @returns {undefined}
 */
function FilterByType(e) {
    //Get filter
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
    //Gets criterea of current recipes
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't filter, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}
/**
 * Call Ajax for search autocomplete
 */
function CallTitles() {
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
                $("#suggestion-box").show();
                $("#suggestion-box").html(data);

            }
        });
    });
}
/**
 * To select title of recipe
 * @param {string} val value of search
 * @returns {undefined}
 */
function selectTitle(val) {
    $("#search-box").val(val);
    $("#suggestion-box").hide();
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
            $('#msg').append("<div class=\"alert alert-danger\"role=\"alert\">Can't get searched recipes, please try again later</div>").fadeIn('slow');
            $('#msg').delay(1000).fadeOut('slow');
        }
    });
}



