<?php
include '../config.php';
include('header.php');
$showUsers = $db->select("users","user_type<>1");
$singer = $db->select("artist");




?>


<link rel="stylesheet" href="../css/style.css">
<script src="../css/bootstrap-3.3.2-dist/js/script_for_menu.js"></script>
<link rel="stylesheet" href="../css/cssmenu.css">

<script src="../css/bootstrap-3.3.2-dist/js/script_for_submit.js"></script>
<script src="../css/bootstrap-3.3.2-dist/js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/jquery-ui.min.css"/>

<?php
include('header_menu.php');
?>

<h1 style = "text-align: center">Список пользователей</h1> <br>

<table class="table table-hover tex" style="width: 95%; margin: 0 auto">
    <thead>
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>User type</th>
<!--        <th>Change</th>-->
        <th>Manage tie</th>
    </tr>
    </thead>
    <tbody>
    <?php
//    $singer = $db->select("singer");
    if ($showUsers)
    {
        foreach($showUsers as $row){
            echo
            '<tr>
                <td class="col-md-1">'.$row['id'].'</td>
                <td class="col-md-2">'.$row['username'].'</td>';
                switch($row['user_type']){
                    case 0:
                        echo
                        '<td class="col-md-2">inactive</td>';
                        break;
                    case 2:
                        echo
                        '<td class="col-md-2">жумушчу-оператор</td>';
                        break;
                    case 3:
                        echo
                        '<td class="col-md-2">клиент-ишкана</td>';
                        break;
                    case 4:
                        echo
                        '<td class="col-md-2">клиент-адам</td>';
                        break;
                    case 5:
                        echo
                        '<td class="col-md-2">клиент-суперадам</td>';
                        break;
                }
//            echo '<td class="col-md-2">
//                    <select style="border: 0px" name="select_action">
//                        <option value="redakt">изменить</option>
//                        <option value="delete">удалить</option>
//                    </select>
//                  </td>';
            ?>


                <td class="col-md-2">
                    <div id='cssmenu'>
                        <ul>
                            <li class='has-sub'><a href="#">manage tie</a>
                                <ul>
                                    <li>

                                        <?php include('user_tie_manager.php'); ?>

                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </td>
            </tr>


        <?php
        }
    }

    ?>
    </tbody>
</table>
