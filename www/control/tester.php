<?php
include("user_control.php");
include('header.php');

// last tab
if(!empty($_POST['last_tab'])){
    $_SESSION['last_tab'] = $_POST['last_tab'];
}

if(!empty($_POST['last_tab'])){
    $_SESSION['last_tab'] = $_POST['last_tab'];
}

$where = '';

// radio
$radio_js = 0;
$select_radio = $_POST['select_radio'];
if(empty($select_radio)){
    $radio_js = 0;
    if($radio_js == 0){
        $radio_zero = 0;
    }
    $radio_js = ' and 1';
    $radio_radio = " and 1";
}else{
    $radio_js = " and pm.radio_id=".$select_radio;
    $radio_radio = " and radio_id=".$select_radio;
    $radio_zero = $select_radio;
}

// from date
$from = $_POST['from'];
if(empty($_POST['from'])){
    $from_js = 0;
}else{
    $from_js = strtotime($from);
    $from_date = date("Y-m-d",$from_js);
}

// to date
$to = $_POST['to'];
if(empty($_POST['to'])){
    $to_js = 0;
}else{
    $to_js = strtotime($to);
    $to_date = date("Y-m-d",$to_js);
}

if ($from != "" && $to != ""){
    $timeSql = "pm.date_played >= '".$from_date."' and pm.date_played <= '".$to_date."' ";
    $timeSql_radio = "date_played >= '".$from_date."' and date_played <= '".$to_date."' ";

}
else if ($from != "") {
    $timeSql = "pm.date_played >= '".$from_date."' ";
    $timeSql_radio = "date_played >= '".$from_date."' ";
}
else if ($to != "") {
    $timeSql = "pm.date_played <= '".$to_date."' ";
    $timeSql_radio = "date_played <= '".$to_date."' ";
}else{
    $timeSql = " pm.date_played >= '2015-03-31'";
    $timeSql_radio = " date_played >= CURDATE()";
}

$artist_id = $_POST['singer'];

if(empty($artist_id)){
    $artist_id = ' ';
}else{
    $button_view = $artist_id;
    $artist_id = " and am.artist_id = ".$artist_id;

}


$sql_singer = "
        select count(pm.track_id) as total, a.name as artist, a.id as artist_id
        from played_melody pm, artist a,artist_melody am,melody m
        where a.id = am.artist_id
        and am.melody_id = m.id
        ".$artist_id."
        and ".$timeSql."
        ".$radio_js."
        and m.track_id=pm.track_id
        GROUP BY a.name,a.id
        ORDER BY total desc limit 50";

//die($sql_singer);
$popular_singer = $db->selectpuresql($sql_singer);
?>




<!DOCTYPE HTML>
<html>
<head>
    <script type="text/javascript">
        window.onload = function () {
            var chart = new CanvasJS.Chart("chartContainer", {
                theme: "theme4",//theme1
                title:{
                    text: "Basic Column Chart - CanvasJS"
                },
                dataSeries: "{x}",
                animationEnabled: false,   // change to true
                width:800,
                data: [
                    {
                        // Change type to "bar", "splineArea", "area", "spline", "pie",etc.
                        type: "stepLine",
                        dataPoints: [
                            <?php
                           foreach ($popular_singer as $row){
                               echo
                               '{ label: "'.$row['artist'].'", y: '.$row['total'].' },';

                           }
                       ?>
                        ]
                    }
                ]
            });
            chart.render();

        }
    </script>
    <script type="text/javascript" src="js/jquery.canvasjs.min.js" defer></script>
</head>
<body>
<div id="chartContainer" style="height: 300px; width: 100%;"></div>
</body>
</html>