<?php
/**
 * Project: VeganRecipes
 * Author: Roxanne Grant
 * Page: index.php
 * Date: Juin 2017
 * Copyright: TPI 2017 - Roxanne Grant © 2017
 */
require_once './controllerIndex.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link href="bootstrap/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/JQuery.js"></script>
        <script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/functionsRecipe.js"></script>
        <link href="css/override.css" type="text/css" rel="stylesheet">
    </head>
    <header>

        <nav class="navbar navbar-default" id="nav">
            <a class="navbar-brand" href="index.php"><img src="upload/VeganYums.png" style="height: 300%;margin-top:-20px;"></a>
            <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
                <div class="navbar-form navbar-left frmSearch" role="search">
                    <input type="text" name="search" id="search-box" class="form-control col-lg-8" placeholder="Search">
                    <button type="submit" class="btn btn-default" data-backdrop="static" name="SubmitSearch" onclick="SubmitSearch()">Submit</button>
                    <div id="suggestion-box"></div>
                </div>
                <ul class="nav navbar-nav navbar-right" id="action">
                    <?php
                    //If admin is signed in show nav for admin
                    //Otherwise show nav for user
                    if (isset($_SESSION["IsAdmin"])) {
                        SignedIn($_SESSION["IsAdmin"]);
                        $IsAdmin = $_SESSION["IsAdmin"];
                    } else {
                        $IsAdmin = "";
                        echo<<<affichage
        <li><a class="active" href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
        <li><a data-toggle="modal" data-keyboard="false" data-target="#myModal"><span class="glyphicon glyphicon-user"></span></a></li>
affichage;
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>
    <body>
        <div class="modal fade" id="myModal" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Login</h2>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" method="POST">
                            <section class="form-group">
                                <input type="text" name="user" class="form-control" placeholder="Username*" required>
                            </section>
                            <section class="form-group">
                                <input type="password" name="pwd" class="form-control" placeholder="Password*" required>
                            </section>
                            <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                        </form>
                         <div id="signinE">
                        <?php
                        echo $signin_error;
                        ?>
                         </div>
                    </div>
                    <div class="modal-footer">
                        <a data-toggle="modal" onclick="EmptyModalSignin()" data-target="#SignUp" class="signup">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="SignUp" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Sign up</h2>
                    </div>
                    <div class="modal-body">
                        <form action="index.php" method="POST">
                            <section class="form-group">
                                <input type="text" name="Newuser" class="form-control" placeholder="Username*" required>
                            </section>
                            <section class="form-group">
                                <input type="password" name="Newpwd" class="form-control" placeholder="Password*" required>
                            </section>
                             <section class="form-group">
                                <input type="password" name="confirmation" class="form-control" placeholder="Confirmation*" required>
                            </section>
                            <button type="submit" class="btn btn-primary btn-block" name="signup">Sign up</button>
                        </form>
                        <div id="signupE">
                        <?php
                       echo $signup_error;
                        ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a data-toggle="modal" onclick="EmptyModalSignup()" data-target="#myModal" class="signup">Sign in</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="AddModal" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Add Recipe</h2>
                    </div>
                    <form action="index.php" method="POST" id="frmAjout" enctype="multipart/form-data">
                        <div class="modal-body">
                            <label for="FileInput" id="labelImg">
                                <img class="col-lg-offset-5" id="AddPhoto" alt="Add a picture" src="upload/add.ico">
                            </label>
                            <input type="file" class="col-xs-3 form-control-file" accept="image/*" name="upload" id="FileInput">
                            <section class="form-group inputT">
                                <input class="frm form-control" type="text" name="title" id="title" placeholder="Title" value="<?php echo (empty($parameters["title"])) ? "" : $parameters["title"] ?>">
                            </section>
                            <textarea class=" frm form-control" rows="8" id="LIngredients" name="ingredients" placeholder="List of Ingredients" ><?php echo (empty($parameters["ingredients"])) ? "" : $parameters["ingredients"] ?></textarea>
                            <select name="type" class="form-control frm">
                                <?php
                                //Show types from DB
                                foreach ($types as $key => $value) {
                                    echo "<option>" . $value["NomType"] . "</option>";
                                }
                                ?>
                            </select>
                            <section class="form-group textareaD">
                                <textarea class="frm form-control" rows="5" name="recipe" id="descrip" placeholder="Description de la recette"><?php echo (empty($parameters["descrip"])) ? "" : $parameters["descrip"] ?></textarea>
                            </section>
                            <button type="submit" class="btn btn-primary btn-block frm" name="Add">Add Recipe</button>
                            <div class="errorModal">
                                <?php
                                echo $img_error;
                                echo $add_error;
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Edit Recipe</h2>
                    </div>
                    <form action="index.php" method="POST" id="frmAjout" enctype="multipart/form-data">
                        <div class="modal-body">
                         <div class="errorModal">
                                <?php
                                echo $img_error;
                                echo $add_error;
                                ?>
                            </div>   
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        //Keep modal open if error in modal
        KeepModalOpen($signin_error, "myModal");
        KeepModalOpen($signup_error, "SignUp");
        KeepModalOpen($img_error, "AddModal");
        KeepModalOpen($add_error, "AddModal");
        ?>
        <div id="ContainsPage" class="container-fluid col-md-12">
            <div class="alldropdown col-md-12">
                <div class="dropdown col-md-2 col-md-offset-4">
                    <button class="btn btn-link dropdown-toggle  " type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Filter by
                        <span class="caret"></span>
                    </button>
                    <ul id="MealTypes" class="dropdown-menu dropdown-menu-right filterRecipes" id="dropdown1" aria-labelledby="dropdownMenu1">
                        <li><a >Starter</a></li>
                        <li><a>Main</a></li>
                        <li><a>Dessert</a></li>
                    </ul>
                </div>
                <div class="dropdown col-md-2 ">
                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown">
                        Sort by
                        <span class="caret"></span>
                    </button>
                    <ul id="DateTypes" class="dropdown-menu dropdown-menu-right filterRecipes" id="dropdown2" aria-labelledby="dropdownMenu1">
                        <li><a>Last added</a></li>
                        <li><a>Oldest post</a></li>
                    </ul>
                </div>
            </div>
            <section>
                <div class="col-lg-5" id="msg">
                    <?php echo $add_success; echo $none_error; ?>
                </div>
                <?php
                foreach ($recipes as $key => $value) {
                    //Shorter description for basic viewing
                    $descripShort = RestrictLengthDescrip($value["Description"]);
                    if ($IndexHome == 0) {
                        //show page for validation
                        IndexAdmin($value, $descripShort);
                    } else {
                        //Get favorites for this user
                        $Isfav = IsFav($value, $favorite);
                        //Get all comments for this recipe
                        $comments = ShowComment($value, $comment);
                        //Show index home
                        IndexHome($value, $descripShort, $Isfav, $comments, $IsAdmin);
                    }
                    echo<<<affichage
                    <div class="modal fade $value[IdRecette]" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">$value[Titre]</h2>
                    </div>
                    <div class="modal-body modalRecipe">
affichage;
                    echo ListIngredients($value["Ingredient"]);
                    echo<<<affichage
                         <p class="col-md-12">
                        $value[Description]
                        </p>
                    </div>
                </div>
            </div>
        </div>
affichage;
                }
                ?>

            </section>
        </div>
        <input type="hidden" name="searchKeyWord" value="">
        <input type="hidden" name="SearchByUserRecipes" value="">
        <input type="hidden" name="searchbyNotValidated" value="">
        <input type="hidden" name="filterType" value="">
        <input type="hidden" name="filterSort" value="">
    </body>
    <footer></footer>
</html>
