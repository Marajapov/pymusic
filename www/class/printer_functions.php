<?php
function mkStr($string){
	//return $string;
	//return mb_convert_encoding($string,'CP1251','utf8');
	$string = str_replace("ө", "о", $string);
	$string = str_replace("ү", "у", $string);
	$string = str_replace("ң", "н", $string);
	$string = str_replace("Ө", "О", $string);
	$string = str_replace("Ү", "У", $string);
	$string = str_replace("Ң", "Н", $string);
	//if (strlen($string)>25) $string = substr($string, 0, 24);
	return mb_convert_encoding($string,'CP1251','utf8');
}

function print_out_ru($printer, $zakaz, $items, $user, $settings){
	if($p = printer_open($printer)) 
	{
		//$content = mb_convert_encoding($content,'CP1251','utf8');
		
		printer_set_option($p,PRINTER_ORIENTATION,PRINTER_ORIENTATION_LANDSCAPE);
		printer_set_option($p,PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
		printer_set_option($p,PRINTER_PAPER_WIDTH,72 );
		printer_set_option($p,PRINTER_PAPER_LENGTH,250);
		printer_start_doc($p, "C O O K E R S");
		printer_start_page($p);
		$pen = printer_create_pen(PRINTER_PEN_DOT, 1, "000000");
		$pen_solid = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
		$font = printer_create_font("Arial", 22, 10, PRINTER_FW_NORMAL, false, false, false, 0);
		$header_font = printer_create_font("Courier", 35, 20, PRINTER_FW_BOLD, false, false, false, 0);
		$price_font = printer_create_font("Courier", 35, 20, PRINTER_FW_NORMAL, false, true, false, 0);
		$slogan_font = printer_create_font("Courier", 30, 16, PRINTER_FW_NORMAL, false, true, false, 0);
		$small_font = printer_create_font("Courier", 20, 10, PRINTER_FW_NORMAL, false, false, false, 0);
		
		
		
		printer_select_pen($p, $pen);
		
		
		//printer_select_font($p, $header_font);
		//printer_draw_text($p, mkStr("C O O K E R S"), 200, 0);
		printer_draw_bmp($p, "c:\\cookers.bmp", 200, 0);
		
		printer_select_font($p, $font);
		
		$current_y = 100;
		$rowcount = count($items);
		$vertical_line_length = $rowcount * 60 + 30;
		$graph_start = 250;
		$first_row = 30;
		$other_row = 60;
		$print_start = $graph_start + $first_row;
		for ($i = 1; $i < 5; $i++){
			$y = 0;
			if ($i == 1) $y=70;
			if ($i == 2) $y=370;
			if ($i == 3) $y=440;
			if ($i == 4) $y=510;
			if ($y>0) printer_draw_line($p, $y,$graph_start,$y, $vertical_line_length+$graph_start);
		}
		printer_select_pen($p, $pen_solid);
		$today = date("d-m-Y H:i:s");
		$serial = $zakaz['id'];
		$full_serial = "0".$serial."|".$serial;
		printer_draw_text($p, mkStr("Чек № ".$full_serial." от ".$today." стол ".$zakaz['tbl']),10, $graph_start-90);
		printer_select_font($p, $font);
		printer_draw_text($p, mkStr("Официант: ".$user['name']),20, $graph_start-30);
		
		printer_draw_line($p, 0, $graph_start,800,$i+$graph_start);
		printer_draw_text($p, mkStr("№"),20, 5+$graph_start);
		printer_draw_text($p, mkStr("Наименование"),75, 5+$graph_start);
		printer_draw_text($p, mkStr("Кол"),375, 5+$graph_start);
		printer_draw_text($p, mkStr("Цена"),445, 5+$graph_start);
		printer_draw_text($p, mkStr("Сумма"),515, 5+$graph_start);
		printer_draw_line($p, 0, $graph_start+$first_row,800,$i+$graph_start+$first_row);
		$total_sum = 0;
		printer_select_pen($p, $pen);
		for($k=0, $i=0;  $k<count($items); $k++, $i+= $other_row){
			$j = $k+1;
			$current_y = $i+$graph_start+$first_row+$other_row;
			$sum_price = $items[$k]['item_count'] * $items[$k]['price'];
			$total_sum += $sum_price;
			
			printer_draw_text($p,$j,20,$i+$graph_start+$first_row+20);
			$name = mkStr($items[$k]['name']);
			if (strlen($name)>25) {
				$str_len = strlen($name);
				$custom_font_w = floor(270 / $str_len);
				$custom_font_h = floor(22 * $custom_font_w / 10);
				$custom_font = printer_create_font("Arial", $custom_font_h, $custom_font_w, PRINTER_FW_NORMAL, false, false, false, 0);
				printer_select_font($p, $custom_font);
			}
			printer_draw_text($p,$name,75,$i+$graph_start+$first_row+20);
			if (strlen($name)>25) {
				printer_delete_font($custom_font);
				printer_select_font($p, $font);
			}
			printer_draw_text($p,$items[$k]['item_count'],375,$i+$graph_start+$first_row+20);
			printer_draw_text($p,$items[$k]['price'],445,$i+$graph_start+$first_row+20);

			printer_draw_text($p,$sum_price,515,$i+$graph_start+$first_row+20);
			printer_draw_line($p, 0,$current_y,800,$current_y);
		}
		
		printer_draw_text($p, mkStr("Сумма"),360, $current_y+20);
		printer_draw_text($p, $total_sum, 515, $current_y+20);
		$percentage = $user['user_type']==0 ? $settings['percentage'] : 0;
		printer_draw_text($p, mkStr("Обслуживание (%".$percentage.")"),230, $current_y+40);
		
		$sum_add = ceil($percentage * $total_sum / 100);
		printer_draw_text($p, $sum_add, 515, $current_y+40);
		
		printer_select_font($p, $price_font);
		$total_sum += $sum_add;
		$total_sum_x = 230;
		if ($total_sum<100) $total_sum_x = 230;
		if ($total_sum>999) $total_sum_x = 230;
		if ($total_sum>9999) $total_sum_x = 230;
		printer_draw_text($p, mkStr("Итого:")." ".$total_sum. " ".mkStr("сом"), $total_sum_x, $current_y+70);
		printer_select_font($p, $slogan_font);
		printer_draw_text($p, mkStr($settings['signature']), 20, $current_y+120);
		printer_draw_text($p, ".", 20, $current_y+100);
		
		printer_delete_font($font);
		printer_delete_font($price_font);
		printer_delete_font($slogan_font);
		printer_delete_font($header_font);
		
		printer_delete_pen($pen);
		printer_end_page($p);
		printer_end_doc($p);
		printer_close($p);
		return true;
	} 
	return false;
}

function print_out($printer, $zakaz, $items, $user){
	if($p = printer_open($printer)) 
	{
		//$content = mb_convert_encoding($content,'CP1251','utf8');
		
		printer_set_option($p,PRINTER_ORIENTATION,PRINTER_ORIENTATION_LANDSCAPE);
		printer_set_option($p,PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
		printer_set_option($p,PRINTER_PAPER_WIDTH,72 );
		printer_set_option($p,PRINTER_PAPER_LENGTH,250);
		printer_start_doc($p, "Testpage");
		printer_start_page($p);
		$pen = printer_create_pen(PRINTER_PEN_DOT, 1, "000000");
		$pen_solid = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
		$font = printer_create_font("Arial", 22, 10, PRINTER_FW_NORMAL, false, false, false, 0);
		$header_font = printer_create_font("Courier", 35, 20, PRINTER_FW_BOLD, false, false, false, 0);
		$price_font = printer_create_font("Courier", 35, 20, PRINTER_FW_NORMAL, false, true, false, 0);
		$slogan_font = printer_create_font("Courier", 30, 16, PRINTER_FW_NORMAL, false, true, false, 0);
		$small_font = printer_create_font("Courier", 20, 8, PRINTER_FW_NORMAL, false, false, false, 0);
		
		
		
		printer_select_pen($p, $pen);
		
		
		printer_select_font($p, $header_font);
		printer_draw_text($p, mkStr("C O O K E R S"), 215, 0);
		//printer_draw_bmp($p, "c:\\diar.bmp", 200, 0);
		
		printer_select_font($p, $font);
		
		$current_y = 0;
		$rowcount = count($items);
		$vertical_line_length = $rowcount * 60 + 30;
		$graph_start = 160;
		$first_row = 30;
		$other_row = 60;
		$print_start = $graph_start + $first_row;
		for ($i = 1; $i < 5; $i++){
			$y = 0;
			if ($i == 1) $y=70;
			if ($i == 2) $y=370;
			if ($i == 3) $y=430;
			if ($i == 4) $y=500;
			if ($y>0) printer_draw_line($p, $y,$graph_start,$y, $vertical_line_length+$graph_start);
		}
		$today = date("d-m-Y");
		printer_select_pen($p, $pen_solid);
		$serial = $zakaz["serial"];
		$full_serial = "0".$serial."|".$serial;
		printer_draw_text($p, mkStr("Чек № ".$full_serial." ".$today." ".$zakaz['tbl']." стол"),100, $graph_start-80);
		printer_select_font($p, $small_font);
		//printer_draw_text($p, mkStr("Коноктор ".$zakaz['person']." киши"),20, $graph_start-55);
		$right_now = date("H:i:s");
		printer_draw_text($p, $right_now,500, $graph_start-55);
		printer_select_font($p, $font);
		printer_draw_text($p, mkStr("Официант: ".$user['name']),20, $graph_start-25);
		
		printer_draw_line($p, 0, $graph_start,800,$i+$graph_start);
		printer_draw_text($p, mkStr("№"),20, 5+$graph_start);
		printer_draw_text($p, mkStr("Наименование"),75, 5+$graph_start);
		printer_draw_text($p, mkStr("Кол-во"),373, 5+$graph_start);
		printer_draw_text($p, mkStr("Цена"),435, 5+$graph_start);
		printer_draw_text($p, mkStr("Сумма"),505, 5+$graph_start);
		printer_draw_line($p, 0, $graph_start+$first_row,800,$i+$graph_start+$first_row);
		$total_sum = 0;
		printer_select_pen($p, $pen);
		for($k=0, $i=0;  $k<count($items); $k++, $i+= $other_row){
			$j = $k+1;
			$current_y = $i+$graph_start+$first_row+$other_row;
			$sum_price = $items[$k]['item_count'] * $items[$k]['price'];
			$total_sum += $sum_price;
			
			printer_draw_text($p,$j,20,$i+$graph_start+$first_row+20);
			$name = mkStr($items[$k]['name']);
			if (strlen($name)>25) {
				$str_len = strlen($name);
				$custom_font_w = floor(270 / $str_len);
				$custom_font_h = floor(22 * $custom_font_w / 10);
				$custom_font = printer_create_font("Arial", $custom_font_h, $custom_font_w, PRINTER_FW_NORMAL, false, false, false, 0);
				printer_select_font($p, $custom_font);
			}
			printer_draw_text($p,$name,75,$i+$graph_start+$first_row+20);
			if (strlen($name)>25) {
				printer_delete_font($custom_font);
				printer_select_font($p, $font);
			}
			printer_draw_text($p,$items[$k]['item_count'],375,$i+$graph_start+$first_row+20);
			printer_draw_text($p,$items[$k]['price'],445,$i+$graph_start+$first_row+20);

			printer_draw_text($p,$sum_price,515,$i+$graph_start+$first_row+20);
			printer_draw_line($p, 0,$current_y,800,$current_y);
		}
		
		$num_length = strlen((string)$total_sum);
		$this_x  = 555 - $num_length*10;
		printer_draw_text($p, mkStr("Сумма"),360, $current_y+20);
		printer_draw_text($p, $total_sum, $this_x, $current_y+20);
		$service_fee = $zakaz['person']*10;
		$num_length = strlen((string)$service_fee);
		$this_x  = 555 - $num_length*10;
		printer_draw_text($p, mkStr("Обслуживание"),360, $current_y+40);
		printer_draw_text($p, $service_fee, $this_x, $current_y+40);
		
		printer_select_font($p, $price_font);
		$total_sum += $zakaz['person']*10;
		$total_sum_x = 320;
		if ($total_sum<100) $total_sum_x = 340;
		if ($total_sum>999) $total_sum_x = 300;
		if ($total_sum>9999) $total_sum_x = 280;
		$result_to_pay = mkStr("Жалпы:")." ".$total_sum;
		printer_draw_text($p, $result_to_pay, $total_sum_x, $current_y+70);
		printer_select_font($p, $slogan_font);
		printer_draw_text($p, mkStr("Приятного аппетита!"), 20, $current_y+120);
		printer_draw_text($p, " ", 20, $current_y+200);
		
		
		printer_delete_font($font);
		printer_delete_font($price_font);
		printer_delete_font($slogan_font);
		printer_delete_font($header_font);
		printer_delete_font($small_font);
		
		printer_delete_pen($pen);
		printer_end_page($p);
		printer_end_doc($p);
		printer_close($p);
		return true;
	} 
	return false;
}


function print_out_kitchen($printer, $zakaz, $items, $user, $zakaz_type, $place){
	if($p = printer_open($printer))
	{
		printer_set_option($p,PRINTER_ORIENTATION,PRINTER_ORIENTATION_LANDSCAPE);
		printer_set_option($p,PRINTER_PAPER_FORMAT, PRINTER_FORMAT_CUSTOM);
		printer_set_option($p,PRINTER_PAPER_WIDTH,72 );
		printer_set_option($p,PRINTER_PAPER_LENGTH,250);
		printer_start_doc($p, "C O O K E R S");
		printer_start_page($p);
		$pen = printer_create_pen(PRINTER_PEN_DOT, 1, "000000");
		$pen_solid = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
		$font = printer_create_font("Arial", 22, 10, PRINTER_FW_NORMAL, false, false, false, 0);
		$header_font = printer_create_font("Courier", 40, 20, PRINTER_FW_NORMAL, false, false, false, 0);
		$name_font = printer_create_font("Courier", 30, 15, PRINTER_FW_NORMAL, false, false, false, 0);
		$small_font = printer_create_font("Courier", 20, 8, PRINTER_FW_NORMAL, false, false, false, 0);
		
		
		
		printer_select_pen($p, $pen);
		
		printer_select_font($p, $header_font);
		
		printer_draw_text($p, mkStr($place), 0, 0);
		
		printer_select_font($p, $name_font);
		
		printer_draw_text($p, mkStr($user['name']." ".$user['lastname']), 200, 10);
		
		
		printer_select_font($p, $font);
		if($zakaz_type==1)
		printer_draw_text($p, mkStr("--------------ДОПОЛНИТЕЛЬНЫЙ ЗАКАЗ---------------"),0, 50);
		
		$current_y = 0;
		$rowcount = count($items);
		$vertical_line_length = $rowcount * 60 + 30;
		$graph_start_add = $zakaz_type==1? 50:0;
		$graph_start = 90+$graph_start_add;
		$first_row = 30;
		$other_row = 60;
		$print_start = $graph_start + $first_row;
		for ($i = 1; $i < 5; $i++){
			$y = 0;
			if ($i == 1) $y=70;
			if ($i == 2) continue;
			if ($i == 3) continue;
			if ($i == 4) $y=495;
			if ($y>0) printer_draw_line($p, $y,$graph_start,$y, $vertical_line_length+$graph_start);
		}
		$today = date("d-m-Y");
		printer_select_pen($p, $pen_solid);
		$serial = $zakaz["id"];
		$full_serial = "0".$serial."|".$serial;
		printer_draw_text($p, mkStr("Чек № ".$full_serial." ".$today." ".$zakaz['tbl']." стол"),100, 50+$graph_start_add);
		printer_select_font($p, $small_font);
		
		$right_now = date("H:i:s");
		printer_draw_text($p, $right_now,500, 70+$graph_start_add);
		printer_select_font($p, $font);
		
		printer_draw_line($p, 0, $graph_start,800,$i+$graph_start);
		printer_draw_text($p, mkStr("№"),20, 5+$graph_start);
		printer_draw_text($p, mkStr("Наименование"),75, 5+$graph_start);
		printer_draw_text($p, mkStr("Кол-во"),500, 5+$graph_start);
		printer_draw_line($p, 0, $graph_start+$first_row,800,$i+$graph_start+$first_row);
		$total_sum = 0;
		printer_select_pen($p, $pen);
		$last_row_y = 0;
		for($k=0, $i=0;  $k<count($items); $k++, $i+= $other_row){
			$j = $k+1;
			$current_y = $i+$graph_start+$first_row+$other_row;
			$sum_price = $items[$k]['item_count'] * $items[$k]['price'];
			$total_sum += $sum_price;
			
			printer_draw_text($p,$j,20,$i+$graph_start+$first_row+20);
			printer_draw_text($p,mkStr($items[$k]['name']),75,$i+$graph_start+$first_row+20);
			printer_draw_text($p,$items[$k]['item_count'],500,$i+$graph_start+$first_row+20);
			printer_draw_line($p, 0,$current_y,800,$current_y);
			$last_row_y = $i+$graph_start+$first_row+20;
		}
		
		printer_draw_text($p, "_____________________________________________________________________________________", 0, $last_row_y+200);
		
		printer_delete_font($font);
		printer_delete_font($small_font);
		printer_delete_font($header_font);
		
		$gs = "\x1d";
		//$cutpaper = $gs."V".chr(66).chr(3);
		//$cutpaper = chr(27)."m";
		
		//$fp = fopen($p, 'w');
		//fwrite($fp, $cutpaper);
		//fclose($fp);
		
		//$cutpaper = chr(hexdec('0x1D')).chr(hexdec('0x56')).chr(hexdec('41'));
		
		

		printer_delete_pen($pen);
		printer_end_page($p);
		printer_end_doc($p);
		
		printer_close($p);
		
		return true;
	}
	return false;
}

?>
