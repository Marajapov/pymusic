<?php

include('../config.php');
include('header.php');

$user_id=0;
if(isset($_POST['user'])) $user_id = getpost("user");
if(isset($_GET['id'])) $user_id = getget("id");
if ($user_id==0) die('where is my user?');

$my_user = $db->select_one("users", "id='".$user_id."'");

$singer = $db->select("singer");

if(isset($_POST['from'])){
    $singer_id = getpost('singer');
    $from = getpost('from');
    $to = getpost('to');
    $lifetime_flag = getpost('lifetime_flag')==1?1:0;

    $newUser_tie = array(
        "user_id" => $user_id,
        "singer_id" => $singer_id,
        "from_date" => $from,
        "to_date" => $to,
        "is_always" => $lifetime_flag
    );
    $result_insert = $db->insert("user_tie", $newUser_tie);

}


$user_tie = $db->selectpuresql("SELECT s.singer_name, t.from_date, t.to_date, t.is_always FROM  user_tie  `t`
INNER JOIN  singer  `s` ON t.singer_id = s.id where t.user_id = '".$user_id."'");

?>


<script src="../css/bootstrap-3.3.2-dist/js/jquery-ui.js"></script>
<script>
    $(function() {
        $( ".from" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( ".to" ).datepicker( "option", "minDate", selectedDate );
            }
        });
        $( ".to" ).datepicker({
            defaultDate: "+1w",
            changeMonth: true,
            numberOfMonths: 1,
            onClose: function( selectedDate ) {
                $( ".from" ).datepicker( "option", "maxDate", selectedDate );
            }
        });
    });
</script>
<?php
include('header_menu.php');
?>

<?php echo $my_user['username']; ?> 's statistics

<?php
    if($user_tie){
        echo
        '<br><br><br><br>
        <table class="table table-hover tex" style="width: 50%; margin: 0 auto">
            <thead class="text-capitalize">
                <th>Singer</th>
                <th>from date</th>
                <th>to date</th>
            </thead>

            <tbody class="table">';

        foreach($user_tie as $row){ ?>

                <tr>
                    <td class="col-md-1"><?php echo $row['singer_name'];?></td>
                    <?php if (!$row['is_always']) { ?>
                    <td class="col-md-1"><?php echo $row['from_date'];?></td>
                    <td class="col-md-1"><?php echo $row['to_date'];?></td>
                    <?php } else {?>
                    <td class="col-md-1" colspan="2">always</td>
                    <?php } ?>
                </tr>

        <?php }
            echo
            '</tbody>
        </table>';
    }
?>


<br><br><br><br><br>
<form method="post" action="chooseSinger.php">
<table class="table table-hover tex" style="width: 50%; margin: 0 auto">
    <thead class="text-capitalize">
    <th>Singer</th>
    <th>Choose for user</th>
    </thead>

    <tbody class="table">
        <tr>
            <td class="col-md-3">
                <select name="singer" id="singer" class="form-control" style="border: 0px">
                    <?php
                        foreach($singer as $row1){
                            echo
                                '<option value="'.$row1['id'].'">'.$row1['singer_name'].'</option>';
                        }
                    ?>
                </select>
            </td>

            <td class="col-md-12">

                     <div class="col-md-12">
                         <label for="from">from&nbsp;</label>
                         <input  required="required" type="text" class="from" name="from">
                         <label for="to">&nbsp;to&nbsp;</label>
                         <input required="required" type="text" class="to" name="to">
                         <input class="disabled"style="margin-left: 30px;" type="checkbox" name="lifetime_flag"  value="1">
                     </div>
                    <input type="hidden" name="user" value="<?php  echo $user_id; ?>"/>

            </td>
        </tr>
     </tbody>
 </table>
    <br><br>
    <div class="col-md-offset-3">
        <input type="submit" name="save" class="btn btn-primary" value="save">
    </div>
</form>

