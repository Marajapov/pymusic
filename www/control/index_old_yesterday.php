<?php
    include("user_control.php");
    include('header.php');

    $radioSql = " and 1 ";

    $timeSql = " pm.date_played >= CURDATE() ";

if(isset($_POST['search'])){
    $where = '';
    $select_radio = getpost('select_radio');

    $singer = getpost('signer');
    $from = getpost('from');
    $to = getpost('to');
    $from_date = date("Y-m-d",strtotime($from));
    $to_date = date("Y-m-d",strtotime($to));

    if ($from != "" && $to != ""){
        $timeSql = "pm.date_played >= '".$from."' and pm.date_played <= '".$to."' ";
    }
    else if ($from != "") {
        $timeSql = "pm.date_played >= '".$from."' ";
    }
    else if ($to != "") {
        $timeSql = "pm.date_played <= '".$to."' ";
    }


    // If selected radio
    if(!empty($select_radio) && !empty($singer) && !empty($from) && !empty($to)){ // Radio and Singer and Period
        $where .= "p.radio_id='".$select_radio."' AND m.artist = '".$singer."' AND p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
    }elseif(!empty($singer) && !empty($from) && !empty($to)){ // Singer and Period
        $where .= " m.artist = '".$singer."' AND p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
    }elseif(!empty($select_radio) && !empty($from) && !empty($to)){ // Radio and Period
        $where .= " p.radio_id='".$select_radio."' AND p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
    }elseif(!empty($singer) && !empty($select_radio)){ // Radio and singer
        $where .= "p.radio_id='".$select_radio."' AND  m.artist = '".$singer."'";
    }elseif(!empty($select_radio)){ // Radio
        $type = 3; // For date to be hidden
        $where .= "p.radio_id='".$select_radio."' AND p.date_played >= NOW()- INTERVAL 1 DAY";
    }elseif(!empty($from) && !empty($to)){ // Period
        $where .= "p.date_played >= '".$from_date."' AND p.date_played <='".$to_date."'";
    }elseif(!empty($singer)){  // Singer
        $where .= " m.artist = '".$singer."'";
    }

    $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE
        ".$where."
        GROUP BY m.track_id
        ORDER BY number_track DESC, p.date_played DESC";


    $radio = $db->selectpuresql($sql);

}elseif(isset($_POST['btn_today'])){
    $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played = CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

    $radio = $db->selectpuresql($sql);
}elseif(isset($_POST['btn_3'])){
    $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-3 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

    $radio = $db->selectpuresql($sql);
}elseif(isset($_POST['btn_week'])){
    $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-7 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

    $radio = $db->selectpuresql($sql);
}elseif(isset($_POST['btn_14'])){
    $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-14 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

    $radio = $db->selectpuresql($sql);
}elseif(isset($_POST['btn_month'])){
    $sql = "
        SELECT m.artist, m.song, p.date_played as p_date_played, p.time_played as p_time_played, r.id as rid, r.name as r_name, COUNT(m.track_id) as number_track, p.track_id as p_track_id
        FROM played_melody  p
        INNER JOIN melody m on p.track_id = m.track_id
        INNER JOIN radio r on p.radio_id = r.id
        WHERE p.date_played BETWEEN CURDATE()-30 AND CURDATE()
        GROUP BY m.track_id
        ORDER BY number_track DESC";

    $radio = $db->selectpuresql($sql);
}else{
        $sql_singer = "
        select count(pm.track_id) as total, a.name as artist, a.id as artist_id
        from played_melody pm, artist a,artist_melody am,melody m
        where a.id = am.artist_id
        and am.melody_id = m.id ".$timeSql.$radioSql." and m.track_id=pm.track_id
        GROUP BY a.name,a.id
        ORDER BY total desc";

    $popular_singer = $db->selectpuresql($sql_singer);

    $popular_songs = $db->selectpuresql("select count(pm.track_id) as total, m.track_id,  am.artist_id, m.song, m.id as melody_id
        from played_melody pm, artist_melody am, melody m
        where am.melody_id = m.id
        and pm.date_played >= CURDATE()
        and m.track_id=pm.track_id
        GROUP BY am.melody_id
        ORDER BY total desc");

    $popular_radios = $db->selectpuresql("select r.id as radio_id, r.name, pm.track_id, count(*) as total from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        where pm.date_played >= CURDATE()
        group by r.id, pm.track_id
        order by total desc");

    $radio_datetime = $db->selectpuresql("select r.id as radio_id, pm.track_id, pm.date_played, pm.time_played from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        where pm.date_played >= CURDATE()");

}
include_once 'report_class.php';

$report_full = array();

foreach($popular_singer as $key=>$singer_row){
    $newSinger = new Singer();
    $newSinger->singer = $singer_row["artist"];
    $newSinger->count = $singer_row["total"];
    $report_full[] = $newSinger;
    foreach($popular_songs as $key=>$song_row){
           if ($song_row["artist_id"]==$singer_row["artist_id"]){
               $newSong = new Song();
               $newSong->song = $song_row["song"];
               $newSong->count = $song_row["total"];
               $newSinger->songs[] = $newSong;
               foreach($popular_radios as $key=>$radio_row){
                   if ($song_row['track_id'] == $radio_row['track_id']){
                        $newRadio = new Radio();
                        $newRadio->radio = $radio_row['name'];
                        $newRadio->count = $radio_row['total'];
                        $newSong->radios[] = $newRadio;
                       foreach($radio_datetime as $daterow){
                           if ($daterow['track_id'] == $radio_row['track_id'] && $daterow['radio_id'] == $radio_row['radio_id'] ){
                               $newDateTime = new RadioTime();
                               $newDateTime->date = $daterow['date_played'];
                               $newDateTime->time = $daterow['time_played'];
                               $newRadio->radiotimes = $newDateTime;
                           }
                       }
                    }

               }

           }

    }
    echo "<pre>";
    print_r($report_full);
    die();
}

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
                        <div class="panel-heading">
                            <i class="fa fa-clock-o fa-fw"></i> Статистика
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form role="form" method="POST" action="index.php">
                                <div class="form-group col-md-12">
                                    <div class="col-md-12 no-padding">
                                        <select required="required" class="form-control" name="select_radio">
                                            <option value="0"> ... Выберите ... </option>
                                            <?php
                                            $radio_type = $db->select('radio');
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
                                            <label for="from">from&nbsp;</label>
                                            <input type="text" id="from" class="from form-control" name="from">
                                        </div>

                                        <div class="col-md-offset-2 col-md-5 no-padding">
                                            <label for="to">&nbsp;to&nbsp;</label>
                                            <input type="text" id="to" class="to form-control" name="to">
                                        </div>

                                    </div>

                                </div>



                                <div class="form-group col-md-12">
                                    <div class="col-md-12 no-padding">
                                        <div class="ui-widget">
                                            <select id="combobox" name="signer" class="form-control">
                                                <option value="">Select one...</option>
                                                <?php
                                                $artist = $db->select("artist");
                                                foreach($artist as $art){
                                                    ?>
                                                    <option value="<?php echo $art['name']; ?>"><?php echo $art['name']; ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>

                                        </div>
                                    </div>

                                </div>

                                <div class="form-group col-md-12">
                                    <input class="col-md-3 btn btn-warning" type="submit" name="btn_today" value="Today"/>
                                    <input class="col-md-3 btn btn-primary" type="submit" name="btn_3" value="3"/>
                                    <input class="col-md-3 btn btn-success" type="submit" name="btn_week" value="week"/>
                                    <input class="col-md-3 btn btn-danger" type="submit" name="btn_month" value="Month"/>
                                </div>

                                <div class="form-group text-right col-md-12">
                                    <input class="btn btn-default" type="submit" value="Search" name="search">
                                </div>
                            </form>




                        <div class="clear"></div>




                        </div>
                        <!-- /.panel-body -->
                    </div>

                    <div class="panel panel-default p-first">

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
                                        foreach($popular_singer as $key=>$radio_row){
                                            $key++;

                                            $artist_by_radio = $db->selectpuresql("
                                                SELECT count(*) as radio_total, r.name as radio_name
                                                FROM played_melody pm, radio r, artist a, artist_melody am, melody m
                                                where a.id='".$radio_row['artist_id']."'
                                                and a.id = am.artist_id
                                                and am.melody_id = m.id
                                                and m.track_id = pm.track_id
                                                and pm.radio_id = r.id
                                                and date_played >= curdate()
                                                group by r.name
                                                order by radio_total desc
                                            ");

                                            ?>
                                            <!-- .panel-heading -->
                                            <div class="panel-body">

                                                <div class="panel-group" id="accordion">

                                                    <div class="panel panel-default p-third">

                                                        <div class="panel-heading clearfix">

                                                            <h4 class="col-lg-1"><?=$key;?></h4>
                                                            <h4 class="panel-title col-lg-11">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key;?>" aria-expanded="false" class="collapsed"><?=$radio_row['artist'];?></a>
                                                                <span class="badge pull-right" data-toggle="modal" data-target="#myModal<?=$key;?>"><?=$radio_row['total'];?></span>
                                                            </h4>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="myModal<?=$key;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title text-center" id="myModalLabel"><?=$radio_row['artist'];?></h4>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <ul class="list-group">
                                                                                <?php
                                                                                    foreach($artist_by_radio as $artist_radio){
                                                                                ?>
                                                                                    <li class="list-group-item">
                                                                                        <?=$artist_radio['radio_name'];?>
                                                                                        <span class="badge pull-right"><?=$artist_radio['radio_total'];?></span>
                                                                                    </li>
                                                                                <?php } ?>
                                                                            </ul>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                            <button type="button" class="btn btn-primary">Save changes</button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div> <!-- MODAL end -->

                                                        </div>
                                                        <div id="collapse<?=$key;?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                            <div class="panel-body">
                                                                <ul class="list-group">
                                                                    <?php

                                                                    //$am_items = $db->select("artist_melody","artist_id='".$radio_row['artist_id']."'");

                                                                    $query_songs = "
                                                                        SELECT m.song as song, count(*) as song_total, m.id as melody_id, m.track_id as track_id
                                                                        FROM artist_melody am, played_melody pm, melody m
                                                                        where am.artist_id = '".$radio_row['artist_id']."'
                                                                        and am.melody_id=m.id
                                                                        and pm.track_id=m.track_id
                                                                        and pm.date_played >= curdate()
                                                                        group by pm.track_id
                                                                        order by song_total desc
                                                                    ";
                                                                    $am_items = $db->selectpuresql($query_songs);

                                                                    foreach($am_items as $am_item){

                                                                        $song_count=$db->select_count("played_melody","track_id='".$am_item['track_id']."' and date_played >= curdate()");

                                                                        if(empty($am_item['song'])){ ?>

                                                                            <li class="list-group-item">
                                                                                [без названия] - ID: <?=$am_item['melody_id'];?>
                                                                                <span class="badge pull-right">
                                                                                    <?=$song_count;?>
                                                                                </span>


                                                                            </li>

                                                                        <?php }else{

                                                                            ?>

                                                                            <li class="list-group-item">
                                                                                <?=$am_item['song'];?>
                                                                                <span class="badge pull-right">
                                                                                    <?=$song_count;?>
                                                                                </span>

                                                                            </li>

                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </ul>
                                                            </div>
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
                                <div class="tab-pane fade" id="profile">
                                    <div class="table">
                                        <div class="table panel p-second">
                                            <div class="panel-heading clearfix">
                                                <h4 class="col-lg-1">№</h4>
                                                <h4 class="col-lg-11">Песня</h4>
                                            </div>
                                            <?php
                                            foreach($popular_track as $id=>$row){
                                                $id++;

                                                /* $artist_by_radio = $db->selectpuresql("
                                                SELECT count(*) as radio_total, r.name as radio_name
                                                FROM played_melody pm, radio r, artist a, artist_melody am, melody m
                                                where a.id='".$row['artist_id']."'
                                                and a.id = am.artist_id
                                                and am.melody_id = m.id
                                                and m.track_id = pm.track_id
                                                and pm.radio_id = r.id
                                                and date_played >= curdate()
                                                group by r.name
                                                order by radio_total desc
                                            ");
                                                */

                                                ?>
                                                <!-- .panel-heading -->
                                                <div class="panel-body">

                                                    <div class="panel-group" id="accordion">

                                                        <div class="panel panel-default p-third">

                                                            <div class="panel-heading clearfix">

                                                                <h4 class="col-lg-1"><?=$id;?></h4>
                                                                <h4 class="panel-title col-lg-11">
                                                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$id;?>" aria-expanded="false" class="collapsed"><?=$row['song_name']." - ".$row['artist_name'];?></a>
                                                                    <span class="badge pull-right" data-toggle="modal" data-target="#myModal<?=$id;?>"><?=$row['c'];?></span>
                                                                </h4>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="myModal<?=$id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title text-center" id="myModalLabel"><?=$row['song_name'];?></h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <ul class="list-group">
                                                                                    <?php
                                                                                    foreach($artist_by_radio as $artist_radio){
                                                                                        ?>
                                                                                        <li class="list-group-item">
                                                                                            <?=$artist_radio['radio_name'];?>
                                                                                            <span class="badge pull-right"><?=$artist_radio['radio_total'];?></span>
                                                                                        </li>
                                                                                    <?php } ?>
                                                                                </ul>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                <button type="button" class="btn btn-primary">Save changes</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div> <!-- MODAL end -->

                                                            </div>
                                                            <div id="collapse<?=$id;?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                                                <div class="panel-body">
                                                                    <ul class="list-group">
                                                                        <?php

                                                                        //$am_items = $db->select("artist_melody","artist_id='".$radio_row['artist_id']."'");

                                                                        $query_songs = "
                                                                        SELECT m.song as song, count(*) as song_total, m.id as melody_id, m.track_id as track_id
                                                                        FROM artist_melody am, played_melody pm, melody m
                                                                        where am.artist_id = '".$radio_row['artist_id']."'
                                                                        and am.melody_id=m.id
                                                                        and pm.track_id=m.track_id
                                                                        and pm.date_played >= curdate()
                                                                        group by pm.track_id
                                                                        order by song_total desc
                                                                    ";
                                                                        $am_items = $db->selectpuresql($query_songs);

                                                                        foreach($am_items as $am_item){

                                                                            $song_count=$db->select_count("played_melody","track_id='".$am_item['track_id']."' and date_played >= curdate()");

                                                                            if(empty($am_item['song'])){ ?>

                                                                                <li class="list-group-item">
                                                                                    [без названия] - ID: <?=$am_item['melody_id'];?>
                                                                                    <span class="badge pull-right" data-toggle="modal" data-target="#myModal<?=$am_item['melody_id'];?>">
                                                                                    <?=$song_count;?>
                                                                                </span>

                                                                                    <!-- Modal -->
                                                                                    <div class="modal fade" id="myModal<?=$am_item['melody_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                                    <h4 class="modal-title" id="myModalLabel"><?=$selected_melody['song'];?></h4>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <ul class="list-group">
                                                                                                        <?php
                                                                                                        ?>
                                                                                                        <li class="list-group-item">
                                                                                                            <?=$radios_item['radio_name'];?>
                                                                                                            <span class="badge pull-right"><?=$song_count;?></span>
                                                                                                        </li>
                                                                                                        <?php
                                                                                                        ?>
                                                                                                    </ul>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div> <!-- MODAL end -->

                                                                                </li>

                                                                            <?php }else{

                                                                                ?>

                                                                                <li class="list-group-item">
                                                                                    <?=$am_item['song'];?>
                                                                                    <span class="badge pull-right" data-toggle="modal" data-target="#myModal<?=$am_item['melody_id'];?>">
                                                                                    <?=$song_count;?>
                                                                                </span>
                                                                                    <!-- Modal -->
                                                                                    <div class="modal fade" id="myModal<?=$am_item['melody_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                                    <h4 class="modal-title" id="myModalLabel"><?=$selected_melody['song'];?></h4>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <ul class="list-group">
                                                                                                        <?php
                                                                                                        ?>
                                                                                                        <li class="list-group-item">
                                                                                                            <?=$radios_item['radio_name'];?>
                                                                                                            <span class="badge pull-right"><?=$song_count;?></span>
                                                                                                        </li>
                                                                                                        <?php
                                                                                                        ?>
                                                                                                    </ul>
                                                                                                </div>
                                                                                                <div class="modal-footer">
                                                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                                                    <button type="button" class="btn btn-primary">Save changes</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>

                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </ul>
                                                                </div>
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