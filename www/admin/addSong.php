<?php
include('../config.php');
include('header.php');
if($_POST['submit'] && $_POST['song'] && $_POST['artist'])
{
	$songname = $_POST['song'];
	$artistname = $_POST['artist'];
    $all = $db->select("songs","songName='".$songname."'");
    if($all)
    {
        echo "This song already exists";
    }
    else
    {
        $newSong = array(
            "songName"=>$songname,
            "artist"=>$artistname
        );

        $allSongs = $db->insert("songs", $newSong);
        if ($allSongs==0)
        {
            redirect('index.php','js');
        }
        else
        {
            echo "Song already exists";
        }
    }
}

?>

<?php
include('header_menu.php');
?>


<div class="container-fluid" style="margin-top: 12%">
    <div class="row">
        <div class="col-md-5 col-md-offset-4">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h4 style="margin-left: 41px">Добавить песню</h4>
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="addSong.php">

                        <div class="form-group">
                            <label class="col-md-4 control-label">Название песни</label>
                            <div class="col-md-7">
                                <input required = "required" type="text" class="form-control" name="song">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-4 control-label">Имя певца</label>
                            <div class="col-md-7">
                                <input required = "required" type="text" class="form-control" name="artist">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-11 control-label">
                                <input name="submit" type="submit" class="btn btn-primary" value="Save"/>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!--<form class="form-horizontal" method="post" action="addSong.php">-->
<!--    <fieldset>-->
<!--        <div class="form-group">-->
<!--            <div class="col-md-5 control-label">-->
<!--                <legend>Добавить песню</legend>-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <label class="col-md-4 control-label">Название песни</label>-->
<!--            <div class="col-md-3">-->
<!--                <input required = "required" type="text" class="form-control" name="song">-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <label class="col-md-4 control-label">Имя певца</label>-->
<!--            <div class="col-md-3">-->
<!--                <input required = "required" type="text" class="form-control" name="artist">-->
<!--            </div>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <div class="col-md-7 control-label">-->
<!--                <input name="submit" type="submit" class="btn btn-primary" value="Save"/>-->
<!--            </div>-->
<!--        </div>-->
<!--    </fieldset>-->
<!--</form>-->