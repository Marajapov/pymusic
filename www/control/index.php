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
   /* $radio_js = 0;
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
    }*/

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

   /* if(empty($artist_id)){
        $artist_id = ' ';
//        if($_SESSION['user_type'] == 3){
//            $user_tie = $db->select("user_tie","user_id='".$_SESSION['userid']."'");
//            foreach($user_tie as $tie){
//                $tie
//            }
//        }
    }else{
        $button_view = $artist_id;
        $artist_id = " and am.artist_id = ".$artist_id;

    }*/

    //echo $_SESSION['userid'];

    $user_tie = $db->select_one("user_tie","user_id='".$_SESSION['userid']."'");
    $singer_id = $user_tie['singer_id'];
    $user_radio = $user_tie['radio'];
    $radio_zero = $user_radio;

    $user_tie_from_y = date("Y",strtotime($user_tie['from_date']));
    $user_tie_from_m = date("m",strtotime($user_tie['from_date']));
    $user_tie_from_d = date("d",strtotime($user_tie['from_date']));

    $user_tie_to_y = date("Y",strtotime($user_tie['to_date']));
    $user_tie_to_m = date("m",strtotime($user_tie['to_date']));
    $user_tie_to_d = date("d",strtotime($user_tie['to_date']));

    $user_tie_to = date($user_tie['to_date']);

    if($radio_zero == 0){
        $radio_zero = " and 1";
        $radio_js = 0;
    }else{
        $radio_zero = " and pm.radio_id = ".$user_radio;
        $radio_js = $user_radio;
    }

    $sql_singer = "
        select count(pm.track_id) as total, a.name as artist, a.id as artist_id
        from played_melody pm, artist a,artist_melody am,melody m
        where a.id = am.artist_id
        and am.melody_id = m.id
        and a.id=".$singer_id."
        and ".$timeSql."
        ".$radio_zero."
        and m.track_id=pm.track_id
        GROUP BY a.name,a.id
        ORDER BY total desc";

     //echo $_SESSION['userid'];
     //die($sql_singer);

    $popular_singer = $db->selectpuresql($sql_singer);


    ///////////// -- for popular songs /////////
    $sql_songs = "select count(pm.track_id) as total, m.track_id,  am.artist_id, m.song, m.id as melody_id, a.name as artist_name
        from played_melody pm, artist_melody am, melody m,artist a
        where am.melody_id = m.id
        and am.melody_id = m.id
        and am.artist_id = a.id
        and a.id=".$singer_id."
        and ".$timeSql.
        " ".$radio_js."
        and m.track_id=pm.track_id
        GROUP BY am.melody_id,am.artist_id,m.song
        ORDER BY total desc, m.song asc";

    //die($sql_songs);
    $popular_songs = $db->selectpuresql($sql_songs);


if($user_radio == 0){
    $radio_select = '';
}else{
    $radio_select = "id='".$user_radio."'";
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
                                            $radio_type = $db->select("radio",$radio_select);?>
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
                                                $artist = $db->select("artist","id='".$singer_id."'");
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
                                            <h4 class="col-lg-1">№</h4>
                                            <h4 class="col-lg-11">Исполнитель</h4>
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
    <a onclick="return ajax_singer(<?php echo $singer['artist_id'];?>,<?php echo $from_js;?>, <?php echo $to_js;?>,<?php echo $radio_js;?>);" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $singer['artist_id'];?>" aria-expanded="false" class="collapsed"><?=$singer['artist'];?></a>
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

                                <div class="tab-pane fade <?php if(!empty($_SESSION['last_tab']) && ($_SESSION['last_tab'] == 2)){echo 'in active';}else{echo '';}?>" id="profile">
                                    <div class="table">
                                        <div class="table panel p-second">
                                            <div class="panel-heading clearfix">
                                                <h4 class="col-lg-1">№</h4>
                                                <h4 class="col-md-5">Песня</h4>
                                                <h4 class="col-md-5">Исполнитель</h4>
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

                                                                    <a onclick="return ajax_song(<?php echo $song_row['melody_id'];?>,<?php echo $from_js;?>, <?php echo $to_js;?>,<?php echo $radio_zero;?>);" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $song_row['melody_id'];?>" aria-expanded="false" class="collapsed col-md-5" style="text-align: right"><?=$song_row['song'];?></a>
                                                                    <span class="col-md-5" style="text-align: left"><?php echo $song_row['artist_name'];?></span>
                                                                    <span class="badge pull-right"><?=$song_row['total'];?></span>

                                                                </h4>

                                                                <div id="collapse<?php echo $song_row['melody_id'];?>" class="panel-collapse collapse" aria-expanded="false">

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