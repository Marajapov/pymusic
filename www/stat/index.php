<?php
	include_once '../usercontrol.php';
    include('../admin/header.php');
  //  $statistics = $db-> selectpuresql("SELECT m.artist,m.song,p.date_played,p.time_played, COUNT( m.song ) as count_song
   // FROM pymusic.played_melody  'p' inner join melody 'm' on p.track_id = m.track_id inner join user_tie 'u' where u.user_id = '.$_SESSION['user_id'].' GROUP BY p.date_played");
//    $data = $db->selectpuresql("SELECT * , DATE( vdate ) AS tdate, COUNT( ip ) as cip FROM counterdb GROUP BY tdate");

?>


<script src="../css/bootstrap-3.3.2-dist/js/script_for_menu.js"></script>
<link rel="stylesheet" href="../css/styles.css">



<div id='cssmenu'>
    <ul>

    <?php
        if($_SESSION['user_type'] == 2){ //operator
    ?>
        <li class='has-sub'><a href='#'>Песня</a>
            <ul>
                <li><a href='addSong.php'>Добавить песню</a>
                </li>
                <li><a href='showSong.php'>Список песен</a>
                </li>
            </ul>
        </li>
        <li class='has-sub'><a href='#'>Певец</a>
            <ul>
                <li><a href='addSinger.php'>Добавить певца</a>
                </li>
                <li><a href='showSinger.php'>Список певцов</a>
                </li>
            </ul>
        </li>
    <?php
        }elseif($_SESSION['user_type'] == 3){ //company
    ?>
        <li><a href="showStatisticsAboutRadio.php">Статистика по радио</a></li>

    <?php
        }elseif($_SESSION['user_type'] == 5){ //super admin
    ?>
            <li><a href="showUser.php">Список пользователей</a></li>
            <li><a href="showSong.php">Список песен</a></li>
            <li><a href="showStatisticsAboutRadio.php">Статистика по радио</a></li>
            <li><a href="showStatisticsAboutSinger.php">Статистика по певцам</a></li>
    <?php
        }elseif($_SESSION['user_type'] == 4){ //user
    ?>
<!--        <a href="showStatisticsAboutSinger.php"><div class="btn btn-click"><h3>Статистика по певцам</h3></div></a> <br><br>-->
        <li><a href="#">Певец/Певцы</a></li>
            <?php
            if($statistics){
                foreach($statistics as $statistics_row){
            ?>
            <ul>
                <li>
                    <div class="btn btn-primary"><?php echo $statistics_row['artist']; ?></div>
                </li>
            </ul>



        <?php
                }
            }
        }
    ?>
    </ul>
</div>





