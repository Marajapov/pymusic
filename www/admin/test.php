<div class="modal fade" id="myModal<?=$key;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Информация</h4>
            </div>
            <div class="modal-body">
                <?php
                $where_date = '';
                if(!empty($date_selected)){

                }else{
                    $where_date .= '';
                }
                echo $query = "
                                SELECT artist,song,radio,count(*) c
                                FROM played_melody pm,melody m
                                WHERE m.track_id=pm.track_id
                                AND pm.track_id = '".$radio_row['p_track_id']."' AND
                                p.date_played >= NOW()- INTERVAL 1 DAY
                                GROUP BY artist,song,radio
                                ORDER BY artist,song,c desc;";

                $radio_list = $db->selectpuresql($query);



                foreach($radio_list as $list){
                    echo $list['radio']." - ".$list['c']."<br />";
                }
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>