<?php
include '../config.php';
include('header.php');
$showSongs = $db->select("singer");
?>

<?php
include('header_menu.php');
?>

<table class="table table-hover tex" style= "width: 50%; margin: 0 auto;" >
    <thead>
    <tr>
        <th>Id</th>
        <th>Певец</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($showSongs)
    {
        foreach($showSongs as $row){
            echo
                '<tr>
                    <td class="col-md-6">'.$row['id'].'</td>
                    <td class="col-md-6">'.$row['singer_name'].'</td>';
            echo "</tr>\n";
        }
    }
    ?>
    </tbody>
</table>
</body>
</html>