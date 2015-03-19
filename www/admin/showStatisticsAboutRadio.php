<?php
    include('../config.php');
    include("header.php");
    $select_radio = getpost('select_radio');
    $radio_id = $radio_type_row['id'];
    $radio_type = $db->select('radio');

    $radio = $db->selectpuresql("SELECT m.artist, m.song,p.date_played , p.time_played, r.name
    FROM played_melody  p
    inner join melody m on p.track_id = m.track_id
    inner join radio r on p.radio_id = r.id
    where p.time_played >= NOW()- INTERVAL 1 HOUR and p.date_played >= NOW()- INTERVAL 1 DAY and p.radio_id = ".$select_radio.";");

    include('header_menu.php');
?>
<script src="../css/bootstrap-3.3.2-dist/js/jquery-ui.js"></script>
<script>
    $(function() {
        $( "#from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>

<div class="container-fluid">
    <div class="row" style="margin: 0 auto; position: relative;">
        <div class="col-lg-offset-2 col-lg-8">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="margin-left: 41px">Выберите радио</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="showStatisticsAboutRadio.php">
                        <div class="form-group col-md-12">
                            <div class="col-md-6 no-padding">
                                <select onchange="this.form.submit();" required="required" class="form-control" name="select_radio">
                                    <?php
                                    if($select_radio){
                                        $selected_radio = $db->select_one("radio","id='".$select_radio."'");
                                        echo '<option value="'.$selected_radio['id'].'">'.$selected_radio['name'].'</option>';
                                    }else{ echo '<option value="0"> . . . . . </option>'; }

                                    foreach($radio_type as $radio_type_row){
                                        echo '<option value="'.$radio_type_row['id'].'">'.$radio_type_row['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-md-12">

                            <div class="col-md-6 no-padding">

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
                            <div class="col-md-6 no-padding">
                                <input type="text" name="singer" id="text_tag_input" class="form-control" onkeyup="ajax_search(this.value)"/>
                                <div id="livesearch"></div>
                            </div>



                        </div>

                        <div class="form-group col-md-12">
                            <input class="btn btn-default" type="button" name="button" value="Today"/>
                            <input class="btn btn-primary" type="button" name="button" value="3"/>
                            <input class="btn btn-success" type="button" name="button" value="week"/>
                            <input class="btn btn-warning" type="button" name="button" value="14"/>
                            <input class="btn btn-danger" type="button" name="button" value="Month"/>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="table">
        <table class="table table-striped table-bordered table-hover" style="width: 65%; margin: 25px auto;">
            <thead>
                <th>№</th>
                <th>Певец</th>
                <th>Песня</th>
                <th>Кол-во воспроизведений</th>
                <th>Время(часы)</th>
            </thead>
            <tbody>
            <?php
            foreach($radio as $key=>$radio_row){
                $key++;
                ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><a href="#" data-toggle="modal" data-target="#myModal<?=$key;?>"><?php echo $radio_row['artist']; ?></a></td>
                    <td><?php echo $radio_row['song']; ?></td>
                    <td><?php echo $radio_row['song']; ?></td>
                    <td><?php echo $radio_row['time_played']; ?></td>

                </tr>
                <div class="modal fade" id="myModal<?=$key;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
                            </div>
                            <div class="modal-body">
                                <?php echo $radio_row['song']; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            </tbody>
        </table>

    </div>
