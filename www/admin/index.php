<?php
	include_once 'usercontrol.php';
    include('header.php');
?>


<script src="../css/bootstrap-3.3.2-dist/js/script_for_menu.js"></script>
<link rel="stylesheet" href="../css/styles.css">
<?php
include('header_menu.php');
?>



<div id='cssmenu'>
    <ul>


    <?php
        if ($_SESSION['user_type'] == 1){ //admin
    ?>
            <li class='has-sub'><a href='#'>Пользователь</a>
                <ul>
                    <li><a href='addUser.php'>Добавить пользователя</a>
                    </li>
                    <li><a href='showUser.php'>Список пользователей</a>
                    </li>
                </ul>
            </li>
<!--            <li class='has-sub'><a href='#'>Песня</a>-->
<!--                <ul>-->
<!--                    <li><a href='addSong.php'>Добавить песню</a>-->
<!--                    </li>-->
<!--                    <li><a href='showSong.php'>Список песен</a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
<!--            <li class='has-sub'><a href='#'>Певец</a>-->
<!--                <ul>-->
<!--                    <li><a href='addSinger.php'>Добавить певца</a>-->
<!--                    </li>-->
<!--                    <li><a href='showSinger.php'>Список певцов</a>-->
<!--                    </li>-->
<!--                </ul>-->
<!--            </li>-->
            <li>
                <a href="chooseSinger.php">Выбрать певца</a>
            </li>
            <li>
                <a href="showStatisticsAboutRadio.php">Статистика по радио</a>
            </li>


<!--    --><?php
//        }elseif($_SESSION['user_type'] == 2){ //operator
//    ?>
<!--        <li class='has-sub'><a href='#'>Песня</a>-->
<!--            <ul>-->
<!--                <li><a href='addSong.php'>Добавить песню</a>-->
<!--                </li>-->
<!--                <li><a href='showSong.php'>Список песен</a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </li>-->
<!--        <li class='has-sub'><a href='#'>Певец</a>-->
<!--            <ul>-->
<!--                <li><a href='addSinger.php'>Добавить певца</a>-->
<!--                </li>-->
<!--                <li><a href='showSinger.php'>Список певцов</a>-->
<!--                </li>-->
<!--            </ul>-->
<!--        </li>-->
    <?php
        }elseif($_SESSION['user_type'] == 3){ //company
    ?>
        <li><a href="showStatisticsAboutRadio.php">Статистика по радио</a></li>
    <?php
        }elseif($_SESSION['user_type'] == 4){ //user
    ?>
<!--            <a href="showStatisticsAboutSinger.php"><div class="btn btn-click"><h3>Статистика по певцам</h3></div></a> <br><br>-->
            <li><a href="showSinger.php">Певец/Певцы</a></li>

    <?php
        }elseif($_SESSION['user_type'] == 5){ //super admin
    ?>
            <li><a href="showUser.php">Список пользователей</a></li>
            <li><a href="showSong.php">Список песен</a></li>
            <li><a href="showStatisticsAboutRadio.php">Статистика по радио</a></li>
            <li><a href="showStatisticsAboutSinger.php">Статистика по певцам</a></li>
        <?php
        }
    ?>
    </ul>
</div>





