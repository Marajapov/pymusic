<?php
include '../config.php';
include('header.php');
$showSongs = $db->select("songs");
?>
<?php
include('header_menu.php');
?>


		<table class="table table-hover tex" style= "width: 75%; margin: 0 auto;" >
      		<thead>
		        <tr>
		          <th>Песня</th>
		          <th>Певец</th>
		        </tr>
		    </thead>
      	    <tbody>
        <?php
            if ($showSongs)
            {
                foreach($showSongs as $row){
                    echo
                        "<tr>
                            <td>".$row['songName']."</td>
                            <td>".$row['artist']."</td>
                        </tr>\n";
                }
            }
        ?>
      </tbody>
    </table>
	</body>
</html>