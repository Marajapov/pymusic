<?php
include('../config.php');
    $user_id = getpost('user');
    $singer_id = getpost('singer');
    $from = getpost('from');
    $to = getpost('to');
    $from_date = date("Y-m-d",strtotime($from));
    $to_date = date("Y-m-d",strtotime($to));
    $radio = getpost('radio');
    $lifetime_flag = getpost('lifetime_flag')==1?1:0;

    $newUser_tie = array(
        "user_id" => $user_id,
        "singer_id" => $singer_id,
        "from_date" => $from_date,
        "to_date" => $to_date,
        "is_always" => $lifetime_flag,
        "radio"=>$radio
    );
    $result_insert = $db->insert("user_tie", $newUser_tie);


?>
<link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/bootstrap.css">
