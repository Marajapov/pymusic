<?php
    include('../config.php');

    if(isset($_GET['t'])){
        $track_id = getget('t');
        $radio_list = $db->selectpuresql("
        select r.id as radio_id, r.name, pm.track_id, count(*) as total
        from played_melody pm
        INNER JOIN radio r ON pm.radio_id = r.id
        where pm.date_played >= CURDATE()
        and pm.track_id =".$track_id."
        group by r.id, pm.track_id
        order by total desc");

foreach($radio_list as $radio){
    ?>


    <!-- Modal body -->
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="panel-heading" role="tab" id="heading">
                <h4 class="panel-title">
                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse" aria-expanded="false" aria-controls="collapse">
                        <?php echo $radio['name']; ?>
                    </a>
                    <span class="badge pull-right"><?=$radio['total'];?></span>
                </h4>
            </div>

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">

                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">

                    <div class="panel panel-default">
                        <div id="collapse" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading">
                            <div class="panel-body">
                                <ul class="list-group">

                                    <li class="list-group-item">
                                        <?php //$repdatetime->song;?>
                                        <?php //$repdatetime->date;?>
                                        <span class="badge pull-right">
                                                            <?php //$repdatetime->time;?>
                                                        </span>

                                    </li>
                                </ul>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div> <!-- end Modal body -->


<?php } // end radio of foreach()

    }// end of if

?>

