<?php
    include('../config.php');
    include("header.php");
    include('header_menu.php');

    if($_POST['select_radio']){
        $select_radio = getpost('select_radio');
        $radio_id = $radio_type_row['id'];
        $radio_type = $db->select('radio');

        $radio = $db->selectpuresql("SELECT m.artist, m.song,p.date_played , p.time_played, r.name
    FROM played_melody  p
    inner join melody m on p.track_id = m.track_id
    inner join radio r on p.radio_id = r.id
    where p.time_played >= NOW()- INTERVAL 1 HOUR and p.date_played >= NOW()- INTERVAL 1 DAY and p.radio_id = ".$select_radio.";");
    }

    ?>

<div class="container-fluid" style="margin-top: 9%">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="margin-left: 41px">Выберите радио</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="showStatisticsAboutRadio.php">
                        <div class="form-group">
                            <div class="col-md-7">
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

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table">
    <table class="table table-responsive" style="width: 65%; margin: 25px auto;">
        <thead>
        <th>Певец</th>
        <th>Песня</th>
        <th>Время(часы)</th>
        </thead>
        <tbody>
        <?php
        foreach($radio as $radio_row){
            ?>
            <tr>
                <td><?php echo $radio_row['artist']; ?></td>
                <td><?php echo $radio_row['song']; ?></td>
                <td><?php echo $radio_row['time_played']; ?></td>
            </tr>
        <?php
        }
        ?>
        </tbody>
    </table>

</div>