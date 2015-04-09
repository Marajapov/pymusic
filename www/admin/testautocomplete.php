<?php
include('../config.php');
header('Content-type: application/json; charset=utf-8');

$all_artist = $db->select("artist");
    foreach($all_artist as $row){
        $json['artist'][]=$row;
    }
    echo json_encode($json);