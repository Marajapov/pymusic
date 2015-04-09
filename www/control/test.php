<html>
<head>
    <title>JS Charts</title>
    <script type="text/javascript" src="js/jquery.canvasjs.min.js"></script>
</head>
<body>
<div id="chart_container">Loading chart...</div>
<script type="text/javascript">
    var myChart = new JSChart('chart_container', 'bar', '');
    myChart.setDataArray([['E-mail', 25],['Search', 49],['News', 21],['Weather', 59],['Hobby', 10],['For fun', 40],['Social networking', 27]]);
    myChart.colorize(['#66ACD1','#3489B6','#68AED2','#206183','#A7D3EA','#73B8DC','#66ACD1']);
    myChart.setSize(600, 300);
    myChart.setIntervalEndY(70);
    myChart.setTitle('Daily internet activities (% internet users)');
    myChart.setTitleColor('#206183');
    myChart.setGrid(false);
    myChart.setBarSpacingRatio(50);
    myChart.setBarValuesColor('#128300');
    myChart.setAxisWidth(1);
    myChart.setAxisColor('#66ACD1');
    myChart.setAxisWidth(1);
    myChart.setAxisValuesColor('#206183');
    myChart.setAxisNameColor('#206183');
    myChart.setAxisNameX('', 'xxx');
    myChart.setAxisNameY('%', 'yyy');
    myChart.setAxisReversed(true);
    myChart.set3D(true);
    myChart.setBarOpacity(0.51);
    myChart.setAxisPaddingLeft(100);
    myChart.setBarBorderColor('#FFFFFF',1);
    myChart.draw();
</script>
</body>
</html>













<?php
include("header.php");
$singer = $db->select("artist");
?>

<!-- Button trigger modal -->




<!-- Navigation -->
<link rel="stylesheet" href="../css/style.css">
<script src="../css/bootstrap-3.3.2-dist/js/script_for_menu.js"></script>
<link rel="stylesheet" href="../css/cssmenu.css">

<script src="../css/bootstrap-3.3.2-dist/js/script_for_submit.js"></script>
<script src="../css/bootstrap-3.3.2-dist/js/jquery-ui.js"></script>
<link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/jquery-ui.min.css"/>
<?php
include("nav.php");
?>

<div id="page-wrapper">
<div class="row">
    <div class="col-lg-12">
        <h4 class="page-header">Пользователи</h4>
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->
<div class="row">
<div class="col-lg-12">

<div class="panel panel-default">
<div class="panel-heading">
    Список всех пользователей
</div>
<!-- /.panel-heading -->
<div class="panel-body">
<div class="dataTable_wrapper">
<table class="table table-striped table-bordered table-hover tex">
<thead>
<tr>
    <th class="col-lg-2">Логин</th>
    <th class="col-lg-2">ФИО</th>
    <th class="col-lg-2">Email</th>
    <th class="col-lg-2">Телефон</th>
    <th class="col-lg-2">Manage tie</th>
    <th class="col-lg-2">Действия</th>
    <th class="col-lg-2">Действия</th>
</tr>
</thead>
<tbody>
<?php
$table = $db->select("users","user_type<>1 AND status=1");
foreach($table as $item){
$user_id = $item['id'];
?>

<script>
    $(function() {
        $( "#from<?php echo $user_id; ?>" ).datepicker({
            changeMonth: true,
            selectOtherMonths: true,
            showOtherMonths: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#to<?php echo $user_id; ?>" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to<?php echo $user_id; ?>" ).datepicker({
            changeMonth: true,
            selectOtherMonths: true,
            showOtherMonths: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#from<?php echo $user_id; ?>" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>


<tr class="odd gradeX">
    <td><?=$item['username'];?></td>
    <td><?=$item['full_name'];?></td>
    <td><?=$item['email'];?></td>
    <td class="text-info"><?=$item['phone'];?></td>

    <td class="col-md-1">
        <!--                                        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal--><?php //echo $item['id'];?><!--">-->
        <!--                                            Manage tie-->
        <!--                                        </button>-->
    <td class="col-md-2">
        <div id='cssmenu'>
            <ul>
                <li class='has-sub'><a href="#">manage tie</a>
                    <ul>
                        <li>

                            <?php include('user_tie_manager.php'); ?>

                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </td>
    </td>
    <td>
        <a href="user_edit.php?id=<?=$item['id'];?>" class="btn btn-info col-lg-5">Изменить</a>
        <a onclick="return confirm('Вы уверены?')" href="user_delete.php?id=<?=$item['id'];?>" class="btn btn-info col-lg-5" >Удалить</a>
    </td>
    <td class="col-md-1">
        <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#result_modal<?php echo $item['id'];?>">
            View result
        </button>
    </td>

    <!-- result_modal -->
    <div class="modal fade" id="result_modal<?php echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">View result label</h4>
                </div>
                <div class="modal-body">
                    <?php

                    $user_tie_all = $db->select("user_tie");
                    echo "<pre>";
                    print_r($user_tie_all);
                    $my_pure_sql = "
                                                    SELECT s.name as singer_name, t.from_date, t.to_date, t.is_always, t.radio radio_id
                                                    FROM  user_tie  `t`
                                                    INNER JOIN  artist  `s` ON t.singer_id = s.id
                                                    where t.user_id = '".$user_id."'";
                    $user_tie = $db->selectpuresql($my_pure_sql);

                    foreach($user_tie as $tie_row){
                        echo $tie_row['singer_name'];
                        if (!$tie_row['is_always']) {
                            echo $tie_row["from_date"];
                            echo $tie_row['to_date'];
                        } else {
                            echo 'always';
                        }
                    }
                    echo $tie_row['radio_id'];

                    ?>

                </div><!-- end of result-modal -> modal-body -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div> <!-- end result_modal -->

</tr>


<!-- myModal -->
<div class="modal fade" id="myModal<?php echo $item['id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Manager</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="form_id_user_tie" method="">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" value="<?php echo $item['username'];?>" disabled="disabled">
                    </div>
                    <div class="form-group">
                        <label for="radio" class="col-sm-2 control-label">Радио</label>
                        <select required="required" class="form-control" name="radio" id="radio<?php echo $user_id;?>">

                            <?php
                            $radio_type = $db->select('radio');?>
                            <option value="101"> -Все радио- </option>
                            <?php
                            foreach($radio_type as $radio_type_row){
                                echo '<option value="'.$radio_type_row['id'].'">'.$radio_type_row['name'].'</option>';
                            }

                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="col-md-12 no-padding">
                            <div class="ui-widget">
                                <label for="singer">Исполнитель<br/>
                                    <select id="combobox" name="singer<?php echo $user_id;?>" class="form-control">
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

                    <div class="form-group">
                        <label for="from" id="fromlabel<?php echo $user_id; ?>">С&nbsp;</label>
                        <input type="text" id="from<?php echo $user_id; ?>" class="from" name="from">
                        <label for="to" id="tolabel<?php echo $user_id; ?>">&nbsp;По&nbsp;</label>
                        <input type="text" id="to<?php echo $user_id; ?>" class="to" name="to">
                        <br/><label for="lifetime_flag<?php echo $user_id; ?>">&nbsp;Постоянно:&nbsp;</label>
                        <input type="checkbox" name="lifetime_flag" id="lifetime_flag<?php echo $user_id; ?>" value="0">

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                        <button id="submit<?php echo $item['id'];?>" type="button" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>



            </div><!-- end of modal-body-->
        </div><!-- end of modal-content -->
    </div><!-- end of modal-->



    <script type="text/javascript">
        $("#submit<?php echo $user_id; ?>").click(function(){
            var user = <?php echo $user_id; ?>;
            var singer = $('[name=singer<?php echo $user_id; ?>]').val();
            var radio = $("#radio<?php echo $user_id; ?>").val();
            var from = $("#from<?php echo $user_id; ?>").val();
            var to = $("#to<?php echo $user_id; ?>").val();
            var lifetime_flag = 0;

//    alert(radio+" - "+singer);


            if($("#lifetime_flag<?php echo $user_id; ?>").is(':checked')) lifetime_flag = 1;

// Returns successful data submission message when the entered information is stored in database.
            var dataString = 'user='+ user + '&singer='+ singer + '&from='+ from + '&to='+ to + '&lifetime_flag='+ lifetime_flag+"&radio="+radio;
            if(singer==0)
            {
                alert("Please Fill All Fields");
            }
            else
            {
                //      alert(dataString);
// AJAX Code To Submit Form.
                $.ajax({
                    type: "POST",
                    url: "ajaxsubmit.php",
                    data: dataString,
                    cache: false,
                    success: function(result){
                        function refresh_joogazin(){

                            $('#joogazin<?php echo $user_id; ?>').html(result);
                        }
                        refresh_joogazin();
                        $(".custom-combobox-input").val("");
                        $("#radio<?php echo $user_id; ?>").val(0);
                        $("#from<?php echo $user_id; ?>").val("");
                        $("#to<?php echo $user_id; ?>").val("");
                        $("#lifetime_flag<?php echo $user_id; ?>").attr('checked', false);
                    }
                });
            }
            return false;
        });
    </script>

    <?php
    } // end of foreach(userlist);
    ?>
</tbody>
</table>
</div><!-- dataTable_wrapper -->

</div>
<!-- /.panel -->
</div>
<!-- /.col-lg-12 -->
</div>
<!-- /.row -->
</div>
<!-- /#page-wrapper -->



<?php
include("footer.php");
?>