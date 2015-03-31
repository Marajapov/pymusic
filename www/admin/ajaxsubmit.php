<?php
include('../config.php');
    $user_id = getpost('user');
    $singer_id = getpost('singer');
    $from = getpost('from');
    $to = getpost('to');
    $lifetime_flag = getpost('lifetime_flag')==1?1:0;

    $newUser_tie = array(
        "user_id" => $user_id,
        "singer_id" => $singer_id,
        "from_date" => $from,
        "to_date" => $to,
        "is_always" => $lifetime_flag,
    );
    $result_insert = $db->insert("user_tie", $newUser_tie);



$my_pure_sql = "SELECT s.name as singer_name, t.from_date, t.to_date, t.is_always FROM  user_tie  `t`
INNER JOIN  artist  `s` ON t.singer_id = s.id where t.user_id = '".$user_id."'";
$user_tie = $db->selectpuresql($my_pure_sql);

$result =   '<table class="table table-hover tex" style="width: 40%; float: right; margin: -216px 33px; margin-bottom: 5px; color: black;">
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
    echo $result;

    echo '</tbody>
    </table>';

?>
<link rel="stylesheet" href="../css/bootstrap-3.3.2-dist/css/bootstrap.css">
