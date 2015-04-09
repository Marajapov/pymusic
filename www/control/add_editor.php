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
                    <h4 class="page-header">Добавить редактора</h4>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
            <div class="col-lg-12">
            <div class="panel panel-default col-lg-offset-3 col-lg-6 no-padding">
                <form role="form">
                    <div class="form-group input-group">
                        <span class="input-group-addon">Имя </span>
                        <input type="text" class="form-control">
                    </div>
                    <div class="form-group input-group">
                        <span class="input-group-addon">Фамилия </span>
                        <input type="text" class="form-control">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Email </span>
                        <input type="email" class="form-control">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Пароль </span>
                        <input type="password" class="form-control">
                    </div>

                    <div class="form-group input-group">
                        <span class="input-group-addon">Категория </span>
                        <select class="form-control" id="sel1">
                            <option selected disabled hidden>-выберите категорию-</option>
                            <option>Банеры</option>
                            <option>Новости</option>
                            <option>Тендеры</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info submit-btn pull-right">Добавить</button>
                </form>

            <!-- /.panel-heading -->

            </div>
            <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->

    <?php
        include("footer.php");
    ?>