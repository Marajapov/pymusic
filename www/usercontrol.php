<?php
	require 'config.php';
	if (!isset($_SESSION['user_type']) && $_SESSION['user_type']<>1) redirect('stat/login.php','js');
?>