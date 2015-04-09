<?php
include("header.php");
$singer = $db->select("artist");
?>

    <!-- Button trigger modal -->




    <!-- Navigation -->
<!--    <link rel="stylesheet" href="../css/style.css">-->
    <script src="../css/bootstrap-3.3.2-dist/js/script_for_menu.js"></script>
    <link rel="stylesheet" href="../css/cssmenu.css">
    <script src="bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!--    <script src="../css/bootstrap-3.3.2-dist/js/script_for_submit.js"></script>-->
<!--    <script src="../css/bootstrap-3.3.2-dist/js/jquery-ui.js"></script>-->
<!--    <link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/jquery-ui.min.css"/>-->
<?php
include("nav.php");
?>

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="page-header">Пользователи</h4>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Список всех пользователей
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover tex">
                                <thead>
                                <tr>
                                    <th class="col-lg-2">Логин</th>
                                    <th class="col-lg-2">ФИО</th>
                                    <th class="col-lg-2">Email</th>
                                    <th class="col-lg-2">Телефон</th>
                                    <th class="col-lg-2">Manage tie</th>
                                    <th class="col-lg-2">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                        <?php
                                $table = $db->select("users","user_type<>1 AND status=1");
                                foreach($table as $item){
                                $user_id = $item['id'];
                        ?>

                        <script>
                            $(function() {
                                $( "#from<?php echo $user_id; ?>" ).datepicker({
                                    changeMonth: true,
                                    selectOtherMonths: true,
                                    showOtherMonths: true,
                                    numberOfMonths: 1,
                                    onClose: function( selectedDate ) {
                                        $( "#to<?php echo $user_id; ?>" ).datepicker( "option", "minDate", selectedDate );
                                    }
                                });
                                $( "#to<?php echo $user_id; ?>" ).datepicker({
                                    changeMonth: true,
                                    selectOtherMonths: true,
                                    showOtherMonths: true,
                                    numberOfMonths: 1,
                                    onClose: function( selectedDate ) {
                                        $( "#from<?php echo $user_id; ?>" ).datepicker( "option", "maxDate", selectedDate );
                                    }
                                });
                            });
                        </script>


                                <tr class="odd gradeX">
                                    <td><?=$item['username'];?></td>
                                    <td><?=$item['full_name'];?></td>
                                    <td><?=$item['email'];?></td>
                                    <td class="text-info"><?=$item['phone'];?></td>

                                    <td class="col-md-1">
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
                                    <td>
                                        <a href="user_edit.php?id=<?=$item['id'];?>" class="btn btn-info col-lg-5">Изменить</a>
                                        <a onclick="return confirm('Вы уверены?')" href="user_delete.php?id=<?=$item['id'];?>" class="btn btn-info col-lg-5" >Удалить</a>
                                    </td>
                                </tr>
                        <?php
                                } // end of foreach(userlist);
                        ?>
                                </tbody>
                            </table>
                        </div><!-- dataTable_wrapper -->

                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->



    <?php
    include("footer.php");
    ?>