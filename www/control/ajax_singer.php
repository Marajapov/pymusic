<?php
 include('../config.php');
    if($_GET['str']){

        $singer_id = $_GET['str'];
        $from = $_GET['from'];
        $to = $_GET['to'];

        $radio = $_GET['radio'];

        if(isset($_GET['radio']) && ($_GET['radio'] != 0)){
            $radio = "and pm.radio_id =". $_GET['radio'];
            $radio_radio = "and radio_id =". $_GET['radio'];
        }elseif($radio['radio'] == 0){
            $radio = " and 1";
        }

        $timeSql = "pm.date_played >= CURDATE()";
        $timeSql_radio = "date_played >= CURDATE()";

        if ($from != 0 && $to != 0){
            $from = date("Y-m-d",$from);
            $to = date("Y-m-d",$to);
            $timeSql = "pm.date_played >= '".$from."' and pm.date_played <= '".$to."' ";
            $timeSql_radio = "date_played >= '".$from."' and date_played <= '".$to."' ";

        }
        else if ($from != 0) {
            $from = date("Y-m-d",$from);
            $timeSql = "pm.date_played >= '".$from."' ";
            $timeSql_radio = "date_played >= '".$from."' ";
        }
        else if ($to != 0) {
            $to = date("Y-m-d",$to);
            $timeSql = "pm.date_played <= '".$to."' ";
            $timeSql_radio = "date_played <= '".$to."' ";
        }

        // singer selected
        if(!empty($singer_id)){
            $singer = " and am.artist_id = ".$singer_id;
        }else{
            $singer = ' and 1';
        }

        $query = "select count(pm.track_id) as total, m.track_id, m.song, m.id as melody_id
        from played_melody pm, artist_melody am, melody m
        where am.melody_id = m.id
        ".$singer."
        and ".$timeSql."
        ".$radio."
        and m.track_id=pm.track_id
        GROUP BY am.melody_id
        ORDER BY total desc";

        //die($query);

        $artist_names = $db->select_one("artist","id=".$singer_id);
        $artist_name = $artist_names['name'];

        $popular_songs = $db->selectpuresql($query);

        foreach($popular_songs as $song){
            $unique++;
            ?>

                <div class="panel-body">
                    <div>
                    <ul class="list-group">

                            <li class="list-group-item">
                                <span style="margin-left: 9%;"><?php echo $unique;?>.</span>
                                <span style="margin-left: 1%; cursor: pointer" data-toggle="modal" data-target="#myModal<?php echo $song['track_id'];?>"><?=empty($song['song'])?"[без названия]":$song['song'];?></span>
                                <span class="badge pull-right">
                                    <a href="#" class="badge pull-right"><?php echo $song['total'];?></a>
                                </span>
                            </li>

                    </ul>
                    </div>
                </div>
<?php
        $track_id = $song['track_id'];

        $sql_radio = "
        select radio as radio_name, date_played as date_played, track_id, count(*) as total
        from played_melody
        where ".$timeSql_radio."
        ".$radio_radio."
        and track_id ='".$track_id."'
        group by radio_id, track_id
        order by total desc";

        //die($sql_radio);
        $radio_list = $db->selectpuresql($sql_radio);

?>
    <!-- Modal -->
    <div class="modal fade" id="myModal<?php echo $track_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center" id="myModalLabel"><?php echo $artist_name." - ".$song['song'];?></h4>
                </div>

                <div class="modal-body">
                    <div>
                        <ul class="list-group">

                <?php foreach($radio_list as $radio){ ?>

                            <li class="list-group-item">
                                <strong><?php echo $radio['radio_name'];?></strong>

                                <span class="badge pull-right">
                                <?php echo $radio['total'];?>
                                </span>
                <?php } ?>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>

    </div> <!-- MODAL end -->

        <?php
        } // end of song foreach()
    } // end of if
?>
