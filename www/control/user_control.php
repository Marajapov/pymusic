<?php
require '../config.php';
if (!isset($_SESSION['user_type'])) redirect('login.php','js');
?>