<?php

$user_id = $row['id'];
$my_pure_sql = "SELECT s.name as singer_name, t.from_date, t.to_date, t.is_always FROM  user_tie  `t`
INNER JOIN  artist  `s` ON t.singer_id = s.id where t.user_id = '".$user_id."'";
$user_tie = $db->selectpuresql($my_pure_sql);



?>
<!--<link rel="stylesheet" href="../css/style.css">-->


<script>
    $(function() {
        $( "#from<?php echo $user_id; ?>" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#to<?php echo $user_id; ?>" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( "#to<?php echo $user_id; ?>" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( "#from<?php echo $user_id; ?>" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>

<div id="mainform<?php echo $user_id; ?>">

    <div id="form<?php echo $user_id; ?>">
        <table class="table table-hover tex" style="width: 50%; float: left; margin: 25px 25px">
            <thead class="text-capitalize">
            <th>Singer</th>
            <th>Choose for user</th>
            </thead>

            <tbody class="table">
            <tr>
                <td class="col-md-3">
                    <select name="singer" id="singer<?php echo $user_id; ?>" class="form-control" style="border: 0px">
                        <option value="0"><b>......</b></option>
                        <?php
                        foreach($singer as $artist){
                            echo
                                '<option value="'.$artist['id'].'">'.$artist['name'].'</option>';
                        }
                        ?>
                    </select>
                </td>

                <td class="col-md-9">

                    <div class="col-md-12">

                        <label for="from" id="fromlabel<?php echo $user_id; ?>">from&nbsp;</label>
                        <input type="text" id="from<?php echo $user_id; ?>" class="from" name="from">
                        <label for="to" id="tolabel<?php echo $user_id; ?>">&nbsp;to&nbsp;</label>
                        <input type="text" id="to<?php echo $user_id; ?>" class="to" name="to">
                        <br/><label for="lifetime_flag<?php echo $user_id; ?>">&nbsp;checkbox explanation:&nbsp;</label>
                        <input style="margin-left: 30px;" type="checkbox" class="lifetime_flag" id="lifetime_flag<?php echo $user_id; ?>" name="lifetime_flag" value="0">


                    </div>
                    <input type="hidden" name="user" id="user" value="<?php  echo $user_id; ?>"/>

                </td>
            </tr>
            </tbody>
        </table>
        <div style="clear: both"></div>
        <div class="col-md-offset-5">
            <input type="button" id="submit<?php echo $user_id; ?>" class="btn btn-primary" value="Save" style="position: absolute; top: 145px">
        </div>
    </div>
</div>

<div id="joogazin<?php echo $user_id; ?>">

    <?php
    $result =   '<br><br><br><br>
            <table class="table table-hover tex" style="width: 50%; float: right; margin: -168px -83px; margin-bottom: 5px;">
            <thead class="text-capitalize">
            <th>Singer</th>
            <th>from date</th>
            <th>to date</th>
            </thead>
            <tbody class="table">';


    foreach($user_tie as $tie_row){

        $result .= '<tr>';
        $result .= '<td class="col-md-1">'.$tie_row['singer_name'].'</td>';
        if (!$tie_row['is_always']) {
            $result .= '<td class="col-md-1">'.$tie_row["from_date"].'</td>';
            $result .= '<td class="col-md-1">'.$tie_row['to_date'].'</td>';
        } else {
            $result .= '<td class="col-md-1" colspan="2">always</td>';
        }
        $result .= '</tr>';
    }
    $result .= "</tbody> </table>";
    echo $result;

    ?>

</div>


<script>

    $(document).ready(function(){

        $("#lifetime_flag<?php echo $user_id; ?>").click(function() {
            $( "#to<?php echo $user_id; ?>" ).show();
            $( "#from<?php echo $user_id; ?>" ).show();
            $( "#tolabel<?php echo $user_id; ?>" ).show();
            $( "#fromlabel<?php echo $user_id; ?>" ).show();
            if($("#lifetime_flag<?php echo $user_id; ?>").is(':checked')) {
                $( "#to<?php echo $user_id; ?>" ).hide();
                $( "#from<?php echo $user_id; ?>" ).hide();
                $( "#tolabel<?php echo $user_id; ?>" ).hide();
                $( "#fromlabel<?php echo $user_id; ?>" ).hide();
            }
        });

        $("#submit<?php echo $user_id; ?>").click(function(){
            var user = <?php echo $user_id; ?>;
            var singer = $("#singer<?php echo $user_id; ?>").val();
            var from = $("#from<?php echo $user_id; ?>").val();
            var to = $("#to<?php echo $user_id; ?>").val();
            var lifetime_flag = 0;
            if($("#lifetime_flag<?php echo $user_id; ?>").is(':checked')) lifetime_flag = 1;

// Returns successful data submission message when the entered information is stored in database.
            var dataString = 'user='+ user + '&singer='+ singer + '&from='+ from + '&to='+ to + '&lifetime_flag='+ lifetime_flag;
            if(singer==0||from==''||to=='')
            {
                alert("Please Fill All Fields");
            }
            else
            {
                alert(dataString);
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
                        $("#singer<?php echo $user_id; ?>").val(0);
                        $("#from<?php echo $user_id; ?>").val("");
                        $("#to<?php echo $user_id; ?>").val("");
                        $("#lifetime_flag<?php echo $user_id; ?>").attr('checked', false);
                    }
                });
            }
            return false;
        });
    });
</script>



