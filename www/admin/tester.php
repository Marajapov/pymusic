<?php
include("user_control.php");
include('header.php');


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
        ORDER BY total desc";

//die($sql_singer);

$popular_singer = $db->selectpuresql($sql_singer);


///////////// -- for popular songs /////////
$sql_songs = "select count(pm.track_id) as total, m.track_id,  am.artist_id, m.song, m.id as melody_id, m.artist as artist_name
        from played_melody pm, artist_melody am, melody m
        where am.melody_id = m.id
        and am.melody_id = m.id
        ".$artist_id."
        and ".$timeSql.
    " ".$radio_js."
        and m.track_id=pm.track_id
        GROUP BY am.melody_id,am.artist_id,m.song
        ORDER BY total desc, m.song asc";

//die($sql_songs);
$popular_songs = $db->selectpuresql($sql_songs);




include('nav.php');
?>

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
            <form role="form" method="POST" action="index.php">
                <div class="form-group col-md-12">
                    <div class="col-md-12 no-padding">
                        <select required="required" class="form-control" name="select_radio">

                            <?php
                            $radio_type = $db->select('radio');?>
                            <option value="0"> -Все радио- </option>

                            <?php
                            foreach($radio_type as $radio_type_row){
                                echo '<option value="'.$radio_type_row['id'].'">'.$radio_type_row['name'].'</option>';
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
            <tr>
                <th>Радио</th>
                <th>Исполнитель</th>
                <th>Начало</th>
                <th>Конец</th>
            </tr>
            </thead>
            <tr style="cursor: default;">

                <td><?php if(!empty($select_radio)){
                        $radio_name = $db->select_one("radio","id='".$select_radio."'");
                        echo $radio_name['name'];}else{echo "Все радио";}?>
                </td>
                <td>
                    <?php if(!empty($button_view)){
                        $artist_name = $db->select_one("artist","id='".$button_view."'");
                        echo $artist_name['name'];}else{echo "Все испольнители";}?>
                </td>
                <td>
                    <?php if(!empty($from_date)){
                        echo $from_date;}else{echo "Сначала дня";}?>
                </td>
                <td>
                    <?php if(!empty($to_date)){
                        echo $to_date;}else{echo "До настоящего момента";}?>
                </td>
            </tr>
        </table>
    </div>

    <div class="panel-body pb-first">

        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="active col-md-6 no-padding">
                <a class="text-right" href="#home" data-toggle="tab" aria-expanded="true">По исполнителям</a>
            </li>
            <li class=" col-md-6 no-padding">
                <a href="#profile" data-toggle="tab" aria-expanded="false"> По названиям песен</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade active in" id="home">
                <div class="table panel p-second">
                    <div class="panel-heading clearfix">
                        <h4 class="col-lg-1">№</h4>
                        <h4 class="col-lg-11">Певец</h4>
                    </div>


                    <?php
                    foreach($popular_singer as $key=>$singer){
                        $key++;
                        ?>

                        <!-- .panel-heading -->
                        <div class="panel-body">

                            <div class="panel-group" id="accordion">

                                <div class="panel panel-default p-third">

                                    <div class="panel-heading clearfix">
                                        <h4 class="col-lg-1"><?=$key;?>.</h4>
                                        <h4 class="panel-title col-lg-11">
                                            <a onclick="return ajax_singer(<?php echo $singer['artist_id'];?>,<?php echo $from_js;?>, <?php echo $to_js;?>,<?php echo $radio_zero;?>);" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $singer['artist_id'];?>" aria-expanded="false" class="collapsed"><?=$singer['artist'];?></a>
                                            <span class="badge pull-right"><?=$singer['total'];?></span>
                                        </h4>
                                        <div id="collapse<?php echo $singer['artist_id'];?>" class="panel-collapse collapse" aria-expanded="false">

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- .panel-body -->





                    <?php
                    } // end singer foreach()
                    ?>

                </div>
            </div>
            <div class="tab-pane fade" id="profile">
                <div class="table">
                    <div class="table panel p-second">
                        <div class="panel-heading clearfix">
                            <h4 class="col-lg-1">№</h4>
                            <h4 class="col-lg-11">Песня</h4>
                        </div>
                        <?php  /////////////////////////////////////////////////////////////////         POPULAR SONGS ////////////////////////////
                        foreach($popular_songs as $unique=>$song_row){
                            $unique++;

                            ?>
                            <!-- .panel-heading -->
                            <div class="panel-body">

                                <div class="panel-group" id="accordion">

                                    <div class="panel panel-default p-third">

                                        <div class="panel-heading clearfix">

                                            <h4 class="col-lg-1"><?=$unique;?></h4>
                                            <h4 class="panel-title col-lg-11">
                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $song_row['melody_id'];?>" aria-expanded="false" class="collapsed"><?=$song_row['song'];?> - <?php echo $song_row['artist_name'];?></a>
                                                <span class="badge pull-right"><?=$song_row['total'];?></span>

                                            </h4>

                                            <div id="collapse<?php echo $song_row['melody_id'];?>" class="panel-collapse collapse" aria-expanded="false">
                                                <?php
                                                $track_id = $song_row['track_id'];

                                                $sql_radio = "
                                                                        select radio as radio_name, radio_id as radio_id, date_played as date_played, track_id, count(*) as total
                                                                        from played_melody
                                                                        where ".$timeSql_radio."
                                                                        ".$radio_radio."
                                                                        and track_id ='".$track_id."'
                                                                        group by radio_id, track_id
                                                                        order by total desc";

                                                //die($sql_radio);
                                                $radio_list = $db->selectpuresql($sql_radio);

                                                ?>
                                                <div class="panel-body">
                                                    <ul class="list-group">
                                                        <?php   foreach($radio_list as $collapse=>$radio){
                                                            $collapse++;
                                                            $radio_id = $radio['radio_id'];
                                                            ?>
                                                            <li style="cursor: pointer;" class="list-group-item" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $collapse;?>" aria-expanded="false" class="collapsed">
                                                                <strong><?php echo $radio['radio_name']." - ";?></strong>
                                                                <span><?php echo $from_date;?></span>
                                                                <span><?php echo $to_date;?></span>
                                                                                    <span class="badge pull-right">
                                                                                    <?php echo $radio['total'];?>
                                                                                    </span>
                                                            </li>

                                                            <div id="collapse<?php echo $collapse;?>" class="panel-collapse collapse" aria-expanded="false">
                                                                <?php
                                                                $sql_for_time = "
                                                                                select time_played, track_id, date_played
                                                                                from played_melody
                                                                                where ".$timeSql_radio."
                                                                                and radio_id=".$radio_id."
                                                                                and track_id ='".$track_id."'
                                                                                order by time_played asc
                                                                                ";
                                                                //die($sql_for_time);
                                                                $sql_times = $db->selectpuresql($sql_for_time);?>
                                                                <div class="panel-body">
                                                                    <?php
                                                                    foreach($sql_times as $time){?>
                                                                        <ul class="list-group">
                                                                            <li class="list-group-item">
                                                                                <span><?php echo $time['time_played'];?></span>
                                                                                <span class="badge pull-right">1</span>
                                                                            </li>
                                                                        </ul>
                                                                    <?php }
                                                                    ?>
                                                                </div><!-- end of panel-body after sql_for_time -->

                                                            </div><!-- end of collapse $collapse; -->


                                                        <?php   } ?>
                                                    </ul>
                                                </div>
                                            </div><!-- end of collapse song_row ['melody_id']; -->
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- .panel-body -->

                        <?php
                        }
                        ?>

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