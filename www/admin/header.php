<?php
    header('Content-Type: text/html; charset=utf-8');
?>
<!Doctype html>
<html>
    <head>
        <link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/bootstrap.css">
        <link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/jquery-ui.min.css"/>
        <link rel="stylesheet" href="../css/styles.css"/>
        <script src="../css/bootstrap-3.3.2-dist/js/script_for_menu.js"></script>
        <script src="../css/bootstrap-3.3.2-dist/js/jquery-latest.min.js"></script>

    </head>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script type="text/javascript" src="../css/bootstrap-3.3.2-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        function ajax_search(value){
            $.get("livesearch.php",{q:value},function(data){
                $("#livesearch").html(data);
            });

        }
    </script>


 <body>




