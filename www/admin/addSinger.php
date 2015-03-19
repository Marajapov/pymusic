<?php
include('../config.php');
include('header.php');
if($_POST['submit']){
    $name = $_POST['name'];
    $all = $db->select("singer","id='".$id."' AND singer_name='".$name."'");
    if($all){
        echo "Singer already exists";
    }else
    {

        $newSinger = array(
            "id"=>$id,
            "singer_name"=>$name,
        );

        $allSingers = $db->insert("singer", $newSinger);

        if ($allSingers) {
            redirect('index.php','js');
        } else {
            echo "Singer already exists";
        }
    }
}
?>
<?php
include('header_menu.php');
?>

<div class="container-fluid" style="margin-top: 12%">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="margin-left: 41px">Add a new singer</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="addSinger.php">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Name</label>
                            <div class="col-md-7">
                                <input required = "required" type="text" class="form-control" name="name">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-11 control-label">
                                <input name="submit" type="submit" class="btn btn-primary" value="Add"/>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<form class="form-horizontal" method="post" action="addSinger.php">-->
<!--    <fieldset>-->
<!--        <div class="form-group">-->
<!--            <div class="col-md-5 control-label">-->
<!--                <legend>Add a new singer</legend>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <label class="col-md-4 control-label">Name</label>-->
<!--            <div class="col-md-3">-->
<!--                <input required = "required" type="text" class="form-control" name="name">-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <div class="col-md-7 control-label">-->
<!--                <input name="submit" type="submit" class="btn btn-primary" value="Add"/>-->
<!--            </div>-->
<!--        </div>-->
<!--    </fieldset>-->
<!--</form>-->
<!--<!--</div>-->

