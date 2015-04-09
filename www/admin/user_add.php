<?php
include("header.php");
    if(isset($_POST['submit_form'])){
        $error = '';
        $user_name = getpost('user_name');

        $item = $db->select_one("users","username like '".$user_name."'");
        $item_compare = $item['username'];
        if($user_name == $item_compare ){
            echo $error .= "Выберите другой логин, такой логин у нас уже существует!";
            die();
        }

        $full_name = getpost('full_name');
        $email = getpost('email');
        $user_type = getpost('user_type');
        $phone = getpost('phone');

        $password = getpost('password');
        $password2 = getpost('password2');
        if($password != $password2){
            echo $error .= "Пароли не совпадают попробуйте еще раз!";
        }
        $user_password = md5($password);
        $insert = array(
            "username" => $user_name,
            "password" => $user_password,
            "user_type" => $user_type,
            "status" => 1,
            "full_name" => $full_name,
            "email" => $email,
            "phone" => $phone
        );
        $result = $db->insert("users",$insert);
        redirect("user_list.php","js");
    }
?>
<!-- Navigation -->
<?php
include("nav.php");
?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Добавить пользователя</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default col-lg-6 no-padding">
                <form role="form" action="user_add.php" method="post">
                    <div class="form-group input-group">
                        <span class="input-group-addon">Логин </span>
                        <input type="text" class="form-control" name="user_name" required="required">
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">ФИО </span>
                        <input type="text" class="form-control" name="full_name">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Email </span>
                        <input type="email" class="form-control" name="email">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Телефон </span>
                        <input type="text" class="form-control" name="phone">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Пароль </span>
                        <input type="password" class="form-control" name="password" required="required">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Повторите пароль </span>
                        <input type="password" class="form-control" name="password2" required="required">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Тип пользователя </span>
                        <select class="form-control" id="sel1" name="user_type">
                            <option value="2">Жумушчу-оператор</option>
                            <option value="3">Клиент-адам</option>
                            <option value="4">Клиент-ишкана</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-default submit-btn pull-right" name="submit_form">Добавить</button>
                </form>

                <!-- /.panel-heading -->

            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

        <?php
        include("footer.php");
        ?>
