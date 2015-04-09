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
    $timeSql = " pm.date_played >= CURDATE()";
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


///////////// -- for popular songs /////////
$sql_songs = "select count(pm.track_id) as total, m.track_id,  am.artist_id, m.song, m.id as melody_id, a.name as artist_name
        from played_melody pm, artist_melody am, melody m,artist a
        where am.melody_id = m.id
        and am.melody_id = m.id
        and am.artist_id = a.id
        ".$artist_id."
        and ".$timeSql.
    " ".$radio_js."
        and m.track_id=pm.track_id
        GROUP BY am.melody_id,am.artist_id,m.song
        ORDER BY total desc, m.song asc limit 50";

//die($sql_songs);
$popular_songs = $db->selectpuresql($sql_songs);




include('nav.php');
?>
    <script type="text/javascript" src="js/jquery.canvasjs.min.js" defer></script>
    <script type="text/javascript">
        window.onload = function () {

//Better to construct options first and then pass it as a parameter
            var options = {
                theme: "theme4",
                title: {
                    text: "Popular singer"
                },
                animationEnabled: true,
                width:900,
                legend: {
                    verticalAlign: "bottom",
                    horizontalAlign: "left",
                    fontSize: 15,
                    fontColor: "black"
                },
                
                data: [
                    {
                        type: "column", //change it to line, area, bar, pie, etc
                        showInLegend: true,
                        legendText: "Исполнители",
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
            };
            var options2 = {
                title: {
                    text: "Popular songs"
                },
                animationEnabled: true,
                width:1200,
                legend: {
                    horizontalAlign: "right"  // "center" , "right"
                    //     verticalAlign: "center",  // "top" , "bottom"

                },
                data: [
                    {
                        type: "column", //change it to line, area, bar, pie, etc
                        dataPoints: [
                            <?php
                                foreach ($popular_songs as $row){

                                    echo
                                    '{ label: "'.$row['song'].'", y: '.$row['total'].' },';

                                }
                            ?>
                        ]
                    }
                ]
            };
            $("#chartContainer").CanvasJSChart(options);
            $("#chartContainer2").CanvasJSChart(options2);

        }
    </script>


    <div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h4 class="page-header">Статистика</h4>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
    <div class="col-lg-12 main-page clearfix">

    <div class="panel panel-default col-lg-6 col-lg-offset-3 no-padding">


        <div class="panel-heading" data-toggle="collapse" data-target="#statistics" style="cursor: pointer">
            <i class="fa fa-clock-o fa-fw"></i> Статистика <i class="fa fa-caret-down col-md-offset-9"></i>
        </div>
        <!-- /.panel-heading -->
        <div class="panel-body collapse" id="statistics">
            <form role="form" method="POST" action="statistics_by_songs.php">
                <div class="form-group col-md-12">
                    <div class="col-md-12 no-padding">
                        <select required="required" class="form-control" name="select_radio">

                            <?php
                            $radio_type = $db->select('radio');?>
                            <option value="0"> -Все радио- </option>

                            <?php

                            foreach($radio_type as $radio_type_row){
                                $selected_radio_string = "";
                                if ($select_radio == $radio_type_row['id']) $selected_radio_string = "selected";
                                echo '<option value="'.$radio_type_row['id'].'" '.$selected_radio_string.'>'.$radio_type_row['name'].'</option>';
                            }

                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-12">

                    <div class="col-md-12 no-padding">

                        <div class="col-md-5 no-padding">
                            <label for="from">С&nbsp;</label>
                            <?php if(!empty($from_date)){
                                echo '<input type="text" id="from" class="from form-control" value="'.$from_date.'" name="from">';
                            }else{?>
                                <input type="text" id="from" class="from form-control" name="from">
                            <?php } ?>

                        </div>

                        <div class="col-md-offset-2 col-md-5 no-padding">
                            <label for="to">&nbsp;По&nbsp;</label>
                            <?php if(!empty($to_date)){
                                echo '<input type="text" id="to" class="from form-control" value="'.$to_date.'" name="to">';
                            }else{?>
                                <input type="text" id="to" class="from form-control" name="to">
                            <?php } ?>

                        </div>

                    </div>

                </div>



                <div class="form-group col-md-12">
                    <div class="col-md-12 no-padding">
                        <div class="ui-widget">
                            <label for="singer">Исполнитель<br/>
                                <select id="combobox" name="singer" class="form-control">
                                    <option value="">Select one...</option>
                                    <?php
                                    $artist = $db->select("artist");
                                    foreach($artist as $art){
                                        ?>
                                        <option value="<?php echo $art['id']; ?>"><?php echo $art['name']; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </label>

                        </div>
                    </div>

                </div>
                <input type="hidden" name="hidden1" id="btn_1"/>
                <input type="hidden" name="hidden2" id="btn_2"/>
                <input type="hidden" name="hidden3" id="btn_3"/>
                <input type="hidden" name="hidden4" id="btn_4"/>


                <div class="form-group text-left col-md-12">
                    <input class="btn btn-danger" type="submit" value="Поиск" name="search">
                </div>

                <input type="hidden" id="last_tab" name="last_tab"/>
            </form>




            <div class="clear"></div>




        </div>
        <!-- /.panel-body -->
    </div>

    <div class="panel panel-default col-lg-6 col-lg-offset-3 no-padding">
        <div class="panel-body" style="padding: 0">
            <div class="">
                <?php if(!empty($_POST['hidden1'])){ $success1 = "success"; }else{ $success1 = "default"; } if(!empty($_POST['hidden2'])){ $success2 = "success"; }else{ $success2 = "default"; } if(!empty($_POST['hidden3'])){ $success3 = "success"; }else{ $success3 = "default"; } if(!empty($_POST['hidden4'])){ $success4 = "success"; }else{ $success4 = "default"; }
                ?>
                <input onclick="today_button_clicked()" class="col-md-3 btn btn-<?php echo $success1;?>" type="button" name="btn_today" value="Сегодня"/>
                <input onclick="three_button_clicked()" class="col-md-3 btn btn-<?php echo $success2;?>" type="button" name="btn_3" value="За 3 дня"/>
                <input onclick="week_button_clicked()" class="col-md-3 btn btn-<?php echo $success3;?>" type="button" name="btn_week" value="Неделя"/>
                <input onclick="month_button_clicked()" class="col-md-3 btn btn-<?php echo $success4;?>" type="button" name="btn_month" value="Месяц"/>
            </div>
        </div>
    </div>

    <div class="panel panel-default p-first" style="margin-top: 40px;">

        <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading" style="text-align: center">Параметры поиска</div>
            <div class="panel-body">

            </div>

            <!-- Table -->
            <table class="table">
                <thead>
                <tr style="cursor: default;">

                    <th><?php
                        if(!empty($select_radio)){
                            $radio_name = $db->select_one("radio","id='".$select_radio."'");
                            echo $radio_name['name'];}else{echo "Все радио";}?>
                    </th>
                    <th>
                        <?php if(!empty($button_view)){
                            $artist_name = $db->select_one("artist","id='".$button_view."'");
                            echo $artist_name['name'];}else{echo "Все испольнители";}?>
                    </th>
                    <th>
                        <?php if(!empty($from_date)){
                            echo $from_date;}else{echo "Сначала дня";}?>
                    </th>
                    <th>
                        <?php if(!empty($to_date)){
                            echo $to_date;}else{echo "До настоящего момента";}?>
                    </th>
                </tr>
                </thead>

            </table>
        </div>

        <div class="panel-body pb-first">

            <!-- Nav tabs -->



            <ul class="nav nav-tabs">
                <li class="col-md-6 no-padding <?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 1))echo 'active';?>" onclick="tab1_clicked()">
                    <a class="text-right" href="#home" data-toggle="tab" aria-expanded="<?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 1)){echo 'true';}else{echo 'false';}?>">По исполнителям</a>
                </li>
                <?php if($_SESSION['user_type'] == 1){?>
                    <li class=" col-md-6 no-padding <?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 2))echo 'active';?>" id="tab2" onclick="tab2_clicked()">
                        <a href="#profile" data-toggle="tab" aria-expanded="<?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 2)){echo 'true';}else{echo 'false';}?>"> По названиям песен</a>
                    </li>
                <?php
                }
                ?>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane fade <?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 1)){echo 'in active';}else{echo '';}?>" id="home">
                    <div class="table panel p-second">
                        <div class="panel-heading clearfix">
                            <h4 class="col-lg-12">Исполнители</h4>
                        </div>
                            <!-- .panel-heading -->
                            <div class="panel-body">
                                    <div class="panel panel-default p-third">
                                        <div id="chartContainer" style="height: 400px; width: 100%;"></div>
                                    </div>
                            </div>

                    </div>
                </div>

                <div class="tab-pane fade <?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 2)){echo 'in active';}else{echo '';}?>" id="profile">
                    <div class="table">
                        <div class="table panel p-second">
                            <div class="panel-heading clearfix">
                                <h4 class="col-lg-12">Графика популярные песни</h4>
                            </div>
                                <!-- .panel-heading -->
                                <div class="panel-body">

                                    <div class="panel-group" id="accordion">

                                        <div class="panel panel-default p-third">

                                            <div class="panel-heading clearfix">

                                                <div id="chartContainer2" style="height: 400px; width: 100%;"></div>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>



    </div>

    </div>
    <!-- /.col-lg-8 -->

    </div>
    <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->




<?php
include('footer.php');
?>