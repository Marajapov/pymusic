<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Администратор
 * Date: 04.03.15
 * Time: 16:55
 * To change this template use File | Settings | File Templates.
 */
    include('../config.php');
    session_destroy();
    redirect('../index.php','js');
?>