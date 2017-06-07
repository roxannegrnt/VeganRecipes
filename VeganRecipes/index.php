<?php
require './lib/FonctionAffichageIndex.php';
require_once './controllerIndex.php';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="ISO-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Home</title>
        <link href="bootstrap-3.3.7-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet">
        <script type="text/javascript" src="js/JQuery.js"></script>
        <script type="text/javascript" src="bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="js/functionsRecipe.js"></script>
        <link href="css/override.css" type="text/css" rel="stylesheet">
    </head>
    <header>

        <nav class="navbar navbar-default" id="nav">
            <a class="navbar-brand" href="index.php">B</a>
            <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
                <div class="navbar-form navbar-left frmSearch" role="search">
                    <input type="text" name="search" id="search-box" class="form-control" placeholder="Search">
                    <button type="submit" class="btn btn-default" data-backdrop="static" name="SubmitSearch" onclick="SubmitSearch()">Submit</button>
                    <div id="suggesstion-box"></div>
                </div>
                <ul class="nav navbar-nav navbar-right" id="action">
                    <?php
                    if (isset($_SESSION["IsAdmin"])) {
                        SignedIn($_SESSION["IsAdmin"]);
                    } else {
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
                                <input type="text" name="user" class="form-control" placeholder="Username">
                            </section>
                            <section class="form-group">
                                <input type="password" name="pwd" class="form-control" placeholder="Password">
                            </section>
                            <button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
                        </form>
                        <?php
                        echo $signin_error;
                        ?>
                    </div>
                    <div class="modal-footer">
                        <a class="signup">Sign up</a>
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
                    <div class="modal-body">
                        <form action="index.php" method="POST" id="frmAjout" enctype="multipart/form-data">
                            <div class="RoundButton">
                            <button type="button" id="ImgProfil" class="btn btn-default btn-circle btn-xl"><i class="glyphicon glyphicon-camera"></i></button>
                            </div>
                            <input class="frm form-control" type="text" name="title" placeholder="Title">
                            <textarea class=" frm form-control" rows="8" name="ingredients" placeholder="List of Ingredients"></textarea>
                            <select name="type" class="form-control frm">
                                <?php
                                foreach ($types as $key => $value) {
                                    echo "<option>" . $value["NomType"] . "</option>";
                                }
                                ?>
                            </select>
                            <textarea class="frm form-control" rows="5" name="recipe" placeholder="Description de la recette"></textarea>
                            <button type="submit" class="btn btn-primary btn-block frm" name="Add">Add Recipe</button>
                            <?php
                            echo $img_error;
                            echo $add_error;
                            ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        //Pour garder la modal ouverte si erreure
        KeepModalOpen($signin_error, "myModal");
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
                    <ul class="dropdown-menu dropdown-menu-right" id="dropdown1" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Starter</a></li>
                        <li><a href="#">Main</a></li>
                        <li><a href="#">Dessert</a></li>
                    </ul>
                </div>
                <div class="dropdown col-md-2 ">
                    <button class="btn btn-link dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        Options
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right" id="dropdown2" aria-labelledby="dropdownMenu1">
                        <li><a href="#">Last added</a></li>
                        <li><a href="#">Oldest post</a></li>
                    </ul>
                </div>
            </div>
            <section>
                <?php
                foreach ($recipes as $key => $value) {
                    //Shorter description for basic viewing
                    $descripShort = RestrictLengthDescrip($value["Description"]);
echo<<<affichage
                    <article class="well col-md-6 col-md-offset-3">
                    <div class="pull-right star">
                    <i class="glyphicon glyphicon-star-empty" id="Star$value[IdRecette]" onmouseover=OnHoverChangeIcon(this) onmouseout=OnHoverOutChangeIcon(this)></i>
                    </div>
   <img class="col-md-3" src="upload/$value[NomFichierImg]" id="imgrecipe">
                    <h3 class="col-md-9">$value[Titre]</h3>
                    <p class="col-md-9 descrip">
                     $descripShort
                     <a data-toggle="modal" data-target="#$value[IdRecette]" >Read more...</a>
                    </p>
                    <button type="button" class="btn btn-primary pull-right" class="Getcomment" data-toggle="collapse" data-target="#collapseComment$value[IdRecette]" aria-expanded="false" aria-controls="collapseExample">Ajouter un commentaire</button>
<div class="collapse" id="collapseComment$value[IdRecette]">
               <input type="text" class="form-control" name="comment">
                   <input class="btn btn-default" type="submit" name="sendComment">
        </div>
        </article>
        <div class="modal fade" id="$value[IdRecette]" role="dialog">
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
    </body>
    <footer></footer>
</html>
