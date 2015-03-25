<?php
include('../config.php');
if($_POST['submit']){
	$username = $_POST["username"];
	$password = md5($_POST["password"]);

//    $user = $db->select_one("users","username='".$username."' AND password='".$password."'");
    $user = $db->select_one("users","username='".$username."' OR phone='".$username."' OR email='".$username."' AND password='".$password."'");

    if($user){
        if ($user['user_type']==1){
        session_start();
        $_SESSION['user_type'] = $user['user_type'];
        redirect('index.php','js');
        }
    }else{
        echo "Invalid username or password";
    }

}

?>

<head>
    <link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/bootstrap.css">
</head>
<style>
    body{
        background: url("/min_kyal.jpg");
        background-repeat: no-repeat;
        background-position: 12%;
        background-size: 19%;
    }
</style>


    <div class="container-fluid" style="margin-top: 12%">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 style="margin-left: 41px">Вход в систему</h4>
                    </div>
                    <div class="panel-body">
                        <form class="form-horizontal" role="form" method="POST" action="login.php">

                            <div class="form-group">
                                <label class="col-md-4 control-label">Username/Email/Телефон</label>
                                <div class="col-md-6">
                                    <input required = "required" type="text" class="form-control" name="username">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-4 control-label">Password</label>
                                <div class="col-md-6">
                                    <input required = "required" type="password" class="form-control" name="password"  placeholder="**********">
                                </div>
                            </div>

                            <input type="submit" class="btn btn-primary col-md-offset-8" name="submit" value="Login"/>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>