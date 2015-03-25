<?php
include("header.php");
?>

    <!-- Navigation -->
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
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="col-lg-2">Логин</th>
                                    <th class="col-lg-2">ФИО</th>
                                    <th class="col-lg-2">Email</th>
                                    <th class="col-lg-2">Телефон</th>
                                    <th class="col-lg-2">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                        <?php
                                $table = $db->select("users","user_type<>1 AND status=1");
                                foreach($table as $item){
                        ?>
                                <tr class="odd gradeX">
                                    <td><?=$item['username'];?></td>
                                    <td><?=$item['full_name'];?></td>
                                    <td><?=$item['email'];?></td>
                                    <td class="text-info"><?=$item['phone'];?></td>
                                    <td>
                                        <a href="user_edit.php?id=<?=$item['id'];?>" class="btn btn-info col-lg-6">Edit</a>
                                        <a href="user_delete.php?id=<?=$item['id'];?>" class="btn btn-info col-lg-6" onclick="confirm('Вы уверены?')">Delete</a>
                                    </td>
                                </tr>
                        <?php
                                }
                        ?>




                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->

                        <!-- /.panel-body -->
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