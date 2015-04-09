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
                <h4 class="page-header">Редакторы</h4>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Список всех редакторов
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th class="col-lg-2">Имя</th>
                                    <th class="col-lg-2">Фамилия</th>
                                    <th class="col-lg-2">Email</th>
                                    <th class="col-lg-2">Пароль</th>
                                    <th class="col-lg-2">Категория</th>
                                    <th class="col-lg-2">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd gradeX">
                                    <td>Редактор1</td>
                                    <td>Редакторович1</td>
                                    <td>n-editor@gmail.com</td>
                                    <td>1qaz</td>
                                    <td class="text-info">Новости</td>
                                    <td>
                                        <button type="submit" class="btn btn-info col-lg-6">Edit</button>
                                        <button type="submit" class="btn btn-danger col-lg-6">Delete</button>
                                    </td>
                                </tr>
                                <tr class="odd gradeX">
                                    <td>Редактор2</td>
                                    <td>Редакторович2</td>
                                    <td>b-editor@gmail.com</td>
                                    <td>2wsx</td>
                                    <td class="text-warning">Баннеры</td>
                                    <td>
                                        <button type="submit" class="btn btn-info col-lg-6">Edit</button>
                                        <button type="submit" class="btn btn-danger col-lg-6">Delete</button>
                                    </td>
                                </tr>
                                <tr class="odd gradeX">
                                    <td>Редактор3</td>
                                    <td>Редакторович3</td>
                                    <td>t-editor@gmail.com</td>
                                    <td>3edc</td>
                                    <td class="text-danger">Тендеры</td>
                                    <td>
                                        <button type="submit" class="btn btn-info col-lg-6">Edit</button>
                                        <button type="submit" class="btn btn-danger col-lg-6">Delete</button>
                                    </td>
                                </tr>



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