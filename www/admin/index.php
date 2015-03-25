<?php
    include("user_control.php");
    include('header.php');

    $radioSql = " and 1 ";

    $timeSql = "  1 "; // pm.date_played >= CURDATE() ";


    $where = '';
    $select_radio = getpost('select_radio');

    $singer = getpost('singer');
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


    $sql_singer = "
        select count(pm.track_id) as total, a.name as artist, a.id as artist_id
        from played_melody pm, artist a,artist_melody am,melody m
        where a.id = am.artist_id
        and am.melody_id = m.id and ".$timeSql.$radioSql." and m.track_id=pm.track_id
        GROUP BY a.name,a.id
        ORDER BY total desc";

    $popular_singer = $db->selectpuresql($sql_singer);

    $popular_songs = $db->selectpuresql("select count(pm.track_id) as total, m.track_id,  am.artist_id, m.song, m.id as melody_id
        from played_melody pm, artist_melody am, melody m
        where am.melody_id = m.id
        and ".$timeSql.$radioSql."
        and m.track_id=pm.track_id
        GROUP BY am.melody_id,am.artist_id,m.song
        ORDER BY total desc");

    $popular_radios = $db->selectpuresql("select r.id as radio_id, r.name, pm.track_id, count(*) as total from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        where ".$timeSql.$radioSql."
        group by r.id, pm.track_id
        order by total desc");

    $radio_datetime = $db->selectpuresql("select r.id as radio_id, pm.track_id, pm.date_played, pm.time_played
        from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        where ".$timeSql.$radioSql."");

     $singer_radio = $db->selectpuresql("select r.id as radio_id, r.name as radio, a.name as artist, a.id as artist_id, count(*) as total
        from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        INNER JOIN melody m ON m.track_id = pm.track_id
        INNER JOIN artist_melody am ON am.melody_id = m.id
        INNER JOIN artist a ON a.id = am.artist_id
        where ".$timeSql.$radioSql."
        group by r.id, a.id
        order by total desc");

    $report_datetime = $db->selectpuresql("select r.id as radio_id, r.name as radio, a.id as artist_id, pm.date_played, pm.time_played, m.song
        from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        INNER JOIN melody m ON m.track_id = pm.track_id
        INNER JOIN artist_melody am ON am.melody_id = m.id
        INNER JOIN artist a ON a.id = am.artist_id
        where ".$timeSql.$radioSql);

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

        foreach($singer_radio as $key=>$singer_radio_row){
            if ($singer_radio_row["artist_id"]==$singer_row["artist_id"]){
                $newRadioReport = new RadioReports();
                $newRadioReport->radio = $singer_radio_row["radio"];
                $newRadioReport->radio_id = $singer_radio_row["radio_id"]."-99";
                $newRadioReport->count = $singer_radio_row["total"];
                $newSinger->radioReports[] = $newRadioReport;
                foreach($report_datetime as $repdaterow){
                    if ($repdaterow['artist_id'] == $singer_radio_row['artist_id'] && $repdaterow['radio_id'] == $singer_radio_row['radio_id'] ){
                        $newReportDateTime = new RadioReportTime();
                        $newReportDateTime->date = $repdaterow['date_played'];
                        $newReportDateTime->time = $repdaterow['time_played'];
                        $newReportDateTime->song = $repdaterow['song'];
                        $newRadioReport->radioreporttimes[] = $newReportDateTime;
                        unset($report_datetime[$key]);
                    }
                }
                unset($singer_radio[$key]);
            }
        }

        foreach($popular_radios as $key=>$radio_row){
            if ($song_row['track_id'] == $radio_row['track_id']){
                $newRadio = new Radio();
                $newRadio->radio = $radio_row['name'];
                $newRadio->count = $radio_row['total'];
                $newSong->radios[] = $newRadio;
                foreach($radio_datetime as $key=>$daterow){
                    if ($daterow['track_id'] == $radio_row['track_id'] && $daterow['radio_id'] == $radio_row['radio_id'] ){
                        $newDateTime = new RadioTime();
                        $newDateTime->date = $daterow['date_played'];
                        $newDateTime->time = $daterow['time_played'];
                        $newRadio->radiotimes[] = $newDateTime;
                        unset($radio_datetime[$key]);
                    }
                }
                unset($popular_radios[$key]);
            }
        }

        unset($popular_songs[$key]);
        }
    }

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
                                            <select id="combobox" name="singer" class="form-control">
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

                                        foreach($report_full as $key=>$singer){
                                            $key++;
                                            if ($key!=137) continue;

                                            ?>
                                            <!-- .panel-heading -->
                                            <div class="panel-body">

                                                <div class="panel-group" id="accordion">

                                                    <div class="panel panel-default p-third">

                                                        <div class="panel-heading clearfix">

                                                            <h4 class="col-lg-1"><?=$key;?></h4>
                                                            <h4 class="panel-title col-lg-11">
                                                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$key;?>" aria-expanded="false" class="collapsed"><?=$singer->singer;?></a>
                                                                <span class="badge pull-right" data-toggle="modal" data-target="#myModal<?=$key;?>"><?=$singer->count;?></span>
                                                            </h4>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="myModal<?=$key;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">

                                                                        <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title text-center" id="myModalLabel"><?=$singer->singer;?></h4>
                                                                        </div>

                                                                        <div class="modal-body">

                                                                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                                                <?php
                                                                                    foreach($singer->radioReports as $radioReport){
                                                                                        ?>

                                                                                        <div class="panel panel-default">

                                                                                            <div class="panel-heading" role="tab" id="heading<?=$radioReport->radio_id;?>">
                                                                                                <h4 class="panel-title">
                                                                                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$radioReport->radio_id;?>" aria-expanded="false" aria-controls="collapse<?=$radioReport->radio_id;?>">
                                                                                                        <?=$radioReport->radio;?>
                                                                                                    </a>
                                                                                                    <span class="badge pull-right"><?=$radioReport->count;?></span>
                                                                                                </h4>
                                                                                            </div>

                                                                                            <div id="collapse<?=$radioReport->radio_id;?>" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="heading<?=$radioReport->radio_id;?>">
                                                                                                <div class="panel-body">
                                                                                                    <ul class="list-group">
                                                                                                        <?php

                                                                                                        foreach($radioReport->radioreporttimes as $repdatetime){  ?>

                                                                                                            <li class="list-group-item">
                                                                                                                <?=$repdatetime->song;?>
                                                                                                                <?=$repdatetime->date;?>
                                                                                                                <span class="badge pull-right">
                                                                                                                    <?=$repdatetime->time;?>
                                                                                                                </span>

                                                                                                            </li>

                                                                                                        <?php } ?>
                                                                                                    </ul>
                                                                                                </div>
                                                                                            </div>

                                                                                        </div>
                                                                                    <?php } ?>
                                                                            </div>
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



                                                                    foreach($singer->songs as $song){  ?>

                                                                            <li class="list-group-item">
                                                                                <?=empty($song->song)?"[без названия]":$song->song;?>
                                                                                <span class="badge pull-right">
                                                                                    <a href="#" class="badge pull-right"><?=$song->count;?></a>
                                                                                </span>

                                                                            </li>

                                                                       <?php } ?>
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
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$unique;?>" aria-expanded="false" class="collapsed"><?=$song_row['song'];?></a>
                                        <span class="badge pull-right" data-toggle="modal" data-target="#myModal<?=$unique;?>"><?=$song_row['total'];?></span>
                                    </h4>

                                                                <!-- Modal -->
                                                                <div class="modal fade" id="myModal<?=$id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                <h4 class="modal-title text-center" id="myModalLabel"><?=$song_row['song'];?></h4>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <ul class="list-group">
                                                                                    <?php
                                                                                    //foreach($artist_by_radio as $artist_radio){
                                                                                        ?>
                                                                                        <li class="list-group-item">
                                                                                            <?php //$artist_radio['radio_name'];?>
                                                                                            <span class="badge pull-right"><? //$psong['radio_total'];?></span>
                                                                                        </li>
                                                                                    <?php // } ?>
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


                                                                        //foreach($am_items as $am_item){

                                                                            //$song_count=$db->select_count("played_melody","track_id='".$am_item['track_id']."' and date_played >= curdate()");

                                                                            if(empty($am_item['song'])){ ?>

                                                                                <li class="list-group-item">
                                                                                    [без названия] - ID: melody id
                                                                                    <span class="badge pull-right" data-toggle="modal" data-target="#myModal">
                                                                                    <?=$song_count;?>
                                                                                </span>

                                                                                    <!-- Modal -->
                                                                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                                    <h4 class="modal-title" id="myModalLabel"> melody song</h4>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <ul class="list-group">
                                                                                                        <?php
                                                                                                        ?>
                                                                                                        <li class="list-group-item">
                                                                                                            radio name
                                                                                                            <span class="badge pull-right">song count</span>
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

                                                                                    <span class="badge pull-right" data-toggle="modal" data-target="#myModal">

                                                                                </span>
                                                                                    <!-- Modal -->
                                                                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                                                        <div class="modal-dialog">
                                                                                            <div class="modal-content">
                                                                                                <div class="modal-header">
                                                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                                                    <h4 class="modal-title" id="myModalLabel">radio song</h4>
                                                                                                </div>
                                                                                                <div class="modal-body">
                                                                                                    <ul class="list-group">
                                                                                                        <?php
                                                                                                        ?>
                                                                                                        <li class="list-group-item">
                                                                                                            radioname
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
                                                                            //}
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