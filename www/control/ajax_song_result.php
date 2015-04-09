<?php
    include('../config.php');

    if(isset($_GET['mel'])){

    $from = $_GET['from'];
    $to = $_GET['to'];

    $radio = $_GET['radio'];

    $melody_id = $_GET['mel'];

    if(!empty($_GET['radio']) && ($_GET['radio'] != 0)){
        $radio = "and pm.radio_id =". $_GET['radio'];
        $radio_radio = "and radio_id =". $_GET['radio'];
    }elseif($radio['radio'] == 0){
        $radio = " and 1";
        $radio_radio = " and 1";
    }

    $timeSql = "pm.date_played >= CURDATE()";
    $timeSql_radio = "date_played >= CURDATE()";

    if ($from != 0 && $to != 0){
        $from = date("Y-m-d",$from);
        $to = date("Y-m-d",$to);
        $timeSql = "pm.date_played >= '".$from."' and pm.date_played <= '".$to."' ";
        $timeSql_radio = "pm.date_played >= '".$from."' and date_played <= '".$to."' ";


    }
    else if ($from != 0) {
        $from = date("Y-m-d",$from);
        $timeSql = "pm.date_played >= '".$from."' ";
        $timeSql_radio = "pm.date_played >= '".$from."' ";
        $from_display = date("Y-m-d",strtotime($from));

    }

    else if ($to != 0) {
        $to = date("Y-m-d",$to);
        $timeSql = "pm.date_played <= '".$to."' ";
        $timeSql_radio = "pm.date_played <= '".$to."' ";
        $to_display = date("Y-m-d",strtotime($to));

    }

    // singer selected
    if(!empty($singer_id)){
        $singer = " and am.artist_id = ".$singer_id;
    }else{
        $singer = ' and 1';
    }

        $sql_radio = "
        select pm.radio as radio_name, pm.radio_id as radio_id, pm.date_played as date_played, pm.track_id as track_id, count(*) as total, m.id as mel
        from played_melody pm, melody m
        where ".$timeSql."
        ".$radio."
        and m.id = ".$melody_id."
        and pm.track_id=m.track_id
        group by radio_name
        order by total desc";

        //die($sql_radio);
        $radio_list = $db->selectpuresql($sql_radio);

?>
<div class="panel-body">
    <ul class="list-group">
        <?php   foreach($radio_list as $collapse=>$radio_row){

            $radio_id = $radio_row['radio_id'];
            $track_id = $radio_row['track_id'];
            ?>
            <li style="cursor: pointer;" class="list-group-item" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $radio_row['radio_id'].$melody_id; ?>" aria-expanded="false" class="collapsed">
                <strong><?php echo $radio_row['radio_name'];?></strong>

                                                                                    <span class="badge pull-right">
                                                                                    <?php echo $radio_row['total'];?>
                                                                                    </span>
            </li>

            <div id="collapse<?php echo $radio_row['radio_id'].$melody_id;;?>" class="panel-collapse collapse" aria-expanded="false">
                <?php
                $sql_for_time = "select time_played, track_id, date_played
                                from played_melody pm
                                where ".$timeSql."
                                and radio_id=".$radio_row['radio_id']."
                                and track_id ='".$track_id."'
                                order by time_played asc
                                ";
                //die($sql_for_time);
                $sql_times = $db->selectpuresql($sql_for_time);?>
                <div class="panel-body">
                    <?php
                    foreach($sql_times as $time){?>
                        <ul class="list-group" style="margin: 5px 10% !important;">
                            <li class="list-group-item">
                                <span><?php echo $time['date_played'];?></span>
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

  <?php  }
?>