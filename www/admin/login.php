<?php
include('../config.php');

if($_POST['submit']){
    $username = $_POST["username"];
    $password = $_POST["password"];

//    $user = $db->select_one("users","username='".$username."' AND password='".$password."'");
    $user = $db->select_one("users","username='".$username."' OR email like'".$username."' OR phone like'".$username."' AND password='".$password."'");

    if($user){
        if (($user['status']==1) && $user['user_type'] == 1){
            session_start();
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['user_name'] = $user['username'];
            redirect('index.php','js');
        }
        if(($user['status'] == 1) && $user['user_type'] == 3){
            session_start();
            $_SESSION['user_type'] = $user['user_type'];
            $_SESSION['userid']    = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            redirect('../control/index.php','js');
        }
    }else{
        echo "Invalid username or password";
    }

}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Please Sign In</h3>
                </div>
                <div class="panel-body">
                    <form role="form" action="login.php" method="post">
                        <fieldset>
                            <div class="form-group">
                                <input class="form-control" placeholder="Username/Email/Телефон" name="username" autofocus>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            </div>
<!--                            <div class="checkbox">-->
<!--                                <label>-->
<!--                                    <input name="remember" type="checkbox" value="Remember Me">Remember Me-->
<!--                                </label>-->
<!--                            </div>-->
                            <!-- Change this to a button or input when using this as a form -->
                            <input type="submit" class="btn btn-lg btn-success btn-block" name="submit" value="Login"/>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="bower_components/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>

<!-- Custom Theme JavaScript -->
<script src="dist/js/sb-admin-2.js"></script>

</body>

</html>
