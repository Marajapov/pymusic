<?php
    include('../config.php');
    include('header.php');
    if($_POST['submit']){
        $name = getpost('name');
        $email = getpost('email');
        $phone = getpost('phone');
        $username = getpost('username');
        $password = md5(getpost('password'));
        $select_type = getpost('select_type');
        $all = $db->select("users","username='".$username."' AND password='".$password."'");
        if($all){
            echo "User already exists";
        }else
        {
            if($select_type == "operator"){
                $usertype = 2;
            }elseif($select_type == "company"){
                $usertype = 3;
            }elseif($select_type == "user"){
                $usertype = 4;
            }elseif($select_type == "super"){
                $usertype = 5;
            }
            $newUser = array(
                "full_name"=>$name,
                "email"=>$email,
                "phone"=>$phone,
                "username"=>$username,
                "password"=>$password,
                "user_type"=>$usertype
            );

            $allUsers = $db->insert("users", $newUser);

            if ($allUsers) {
                redirect('index.php','js');
            } else {
                echo "User already exists";
            }
        }
    }
?>

<?php
include('header_menu.php');
?>

<div class="container-fluid" style="margin-top: 9%">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="margin-left: 41px">Add a new user</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="addUser.php">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Аты</label>
                            <div class="col-md-6">
                                <input required = "required" type="text" class="form-control" name="name">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">email</label>
                            <div class="col-md-6">
                                <input required = "required" type="email" class="form-control" name="email" placeholder="user@example.com">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Телефон</label>
                            <div class="col-md-6">
                                <input required = "required" type="text" class="form-control" name="phone" placeholder="0555 50 51 50">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Username</label>
                            <div class="col-md-6">
                                <input required = "required" type="text" class="form-control" name="username" placeholder="username">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Password</label>
                            <div class="col-md-6">
                                <input required = "required" type="password" class="form-control" name="password"  placeholder="**********">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">User type</label>
                            <div class="col-md-6">
                                <select required="required" class="form-control" name="select_type">
                                    <option value="user">Клиент-адам</option>
                                    <option value="company">Клиент-ишкана</option>
                                    <option value="super">Клиент-суперадам</option>
                                    <option value="operator">Жумушчу-оператор</option>
                                </select>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-primary col-md-3 col-md-offset-7" name="submit" value="Add"/>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
