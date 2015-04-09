<?php
include('../config.php');

    $post_name = $_GET['q'];
    $artists = $db->select("artist","name like '%".$post_name."%'");
    foreach($artists as $artist){ ?>

    <script type="text/javascript">
        $(function() {
            $('.tags_select a').click(function() {
                var value = $(this).text();
                var input = $('#text_tag_input');
                input.val(value);
                $('#livesearch'.val(''));
                return false;
            });
        });
    </script>
        <div class="tags_select">
            <a href="#"><?=$artist['name'];?></a>
        </div>

    <?php
    }
    ?>