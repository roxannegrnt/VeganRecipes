<?php
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
        <link href="css/override.css" type="text/css" rel="stylesheet">
    </head>
    <header>

        <nav class="navbar navbar-default" id="nav">
            <a class="navbar-brand" href="index.php">B</a>
            <div class = "collapse navbar-collapse" id = "example-navbar-collapse">
                <div class="navbar-form navbar-left frmSearch" role="search">
                    <input type="text" name="search" id="search-box" class="form-control" placeholder="Search">
                    <button type="submit" class="btn btn-default" name="SubmitSearch" onclick="SubmitSearch()">Submit</button>
                    <div id="suggesstion-box"></div>
                </div>
                <ul class="nav navbar-nav navbar-right" id="action">
                    <li><a class="active" href="index.php"><span class="glyphicon glyphicon-home"></span></a></li>
                    <li><a data-toggle="modal" data-target="#myModal"><span class="glyphicon glyphicon-user"></span></a></li>
                </ul>
            </div>
        </nav>
    </header>
    <body>
        <div class="modal fade" id="myModal" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h2 class="modal-title">Login</h2>
                    </div>
                    <div class="modal-body">
                        <section class="form-group">
                            <input type="text" name="user" class="form-control" placeholder="Username">
                        </section>
                        <section class="form-group">
                            <input type="password" name="pwd" class="form-control" placeholder="Password">
                        </section>
                        <button type="button" onclick="IndexConnected();" class="btn btn-primary btn-block" data-dismiss="modal">Login</button>
                    </div>
                    <div class="modal-footer">
                        <a class="signup">Sign up</a>
                    </div>
                </div>
            </div>
        </div>
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
