<header style="width: 70%; margin: 10px auto;">
    <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
            <!--                    <a class="navbar-brand" href="#">Home</a>-->
            <!--                </div>-->
            <!--                <div class="navbar-text">-->
            <!--                    <a href="#" class="navbar-link">Thomas</a>-->
            <!--                </div>-->



            <ul class="nav nav-pills">
                <?php
                if ($_SESSION['user_type'] == 1){ //admin
                    echo "<a class=\"navbar-brand\" href=\"/admin/index.php\">Home</a>
                    </div>
                <div class=\"navbar-text\">";
                    echo "<a href=\"logout.php\" style=\"text-decoration: none;\">Logout</a></div>";
                }
//                    if ($_SESSION['user_type'] == 1){ //admin
//                        echo "<li role=\"presentation\" class=\"active\"><a href=\"/admin/index.php\">Home</a></li>";
//                        echo "<li role=\"presentation\"><a href=\"logout.php\">Logout</a></li>";
//                    }
                elseif($_SESSION['user_type'] == 2){ //operator
                    echo "<li role=\"presentation\" class=\"active\"><a href=\"/admin/index.php\">Home</a></li>";
                    echo "<li role=\"presentation\"><a href=\"logout.php\">Logout</a></li>";
                }elseif($_SESSION['user_type'] == 3){ //company
                    echo "<li role=\"presentation\" class=\"active\"><a href=\"/admin/index.php\">Home</a></li>";
                    echo "<li role=\"presentation\"><a href=\"logout.php\">Logout</a></li>";
                }elseif($_SESSION['user_type'] == 4){ //user
                    echo "<li role=\"presentation\" class=\"active\"><a href=\"/admin/index.php\">Home</a></li>";
                    echo "<li role=\"presentation\"><a href=\"logout.php\">Logout</a></li>";
                }elseif($_SESSION['user_type'] == 5){ //super admin
                    echo "<li role=\"presentation\" class=\"active\"><a href=\"/admin/index.php\">Home</a></li>";
                    echo "<li role=\"presentation\"><a href=\"logout.php\">Logout</a></li>";
                }else
                {
                    echo "<li role=\"presentation\" class=\"active\"><a href=\"/index.php\">Home</a></li>";
                    echo "<li role=\"presentation\"><a href=\"admin/login.php\">Login</a></li>";

                }
                ?>
            </ul>
</header>