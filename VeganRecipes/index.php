<?php
session_start();
require './FonctionIndex.php';
require './CRUD/DbConnect.php';
$alert = "";
$DB = new DbConnect();
$types = $DB->GetTypes();
if (isset($_REQUEST["login"])) {
    $exist = $DB->GetRegistration($_REQUEST["user"], sha1($_REQUEST["pwd"]));
    if (!empty($exist)) {
        $_SESSION["uid"] = $exist["IdUtilisateur"];
        $_SESSION["IsAdmin"] = $exist["IsAdmin"];
    } else {
        $alert = "Oops... There must be an error with your username or your password";
    }
}
if (isset($_REQUEST["Add"])) {
    $ingredients=FormatIngredients($_REQUEST["ingredients"]);
    $param=VerficationAdd($_REQUEST["title"], $ingredients, $_REQUEST["recipe"], $_REQUEST["type"]);
    $img = VerifyImg($_FILES);
    $unique = uniqid("FILE_");
    if (move_uploaded_file($_FILES['upload']['tmp_name'], "upload/" . $unique)) {
        array_push($param, $unique);
        $DB->InsertRecipe($param,$_SESSION["uid"]);
    }
    
}
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
                        echo $alert;
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
                            <input class="col-md-7 col-md-offset-3 frm" type="text" name="title" placeholder="Title">
                            <img class="col-md-4" id="ImgProfil" src="upload/imgdefaut.png" value="" alt=""/>
                            <textarea class="col-md-9 frm" rows="6" cols="15" name="ingredients">- List of Ingredients</textarea>
                            <select name="type">
                                <?php
                                foreach ($types as $key => $value) {
                                    echo "<option>" . $value["NomType"] . "</option>";
                                }
                                ?>
                            </select>
                            <textarea class="col-md-12 frm" rows="6" cols="20" name="recipe">Description de la recette</textarea>
                            <button type="submit" class="btn btn-primary btn-block" name="Add">Add Recipe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        //Pour garder la modal ouverte si erreure
        KeepModalOpen($alert);
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

            </section>
        </div>
    </body>
    <footer></footer>
</html>
