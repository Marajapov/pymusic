<?php
    include('../config.php');

    if($_GET['id']){
        $id = getget('id');
        $item = $db->select_one("users","id='".$id."'");
        $delete_id = $item['id'];
        $update = array(
            "status" => 0
        );
        $db->update("users",$update,"id='".$delete_id."'");
        redirect("user_list.php","js");
    }
?>