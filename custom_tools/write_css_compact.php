<?

    /* Map Rows and Loop Through Them 
	#card_101 {
		background-position-y: 0%;
		background-position-x: 0%;
	}	
	*/
	$to_write = '';
	$level = 1;
	$x=1;
	$y=1;
	$base_css1 = "\tbackground-image: url('img/sheet1.jpg');\n\tbackground-size: 800% 800%;\n}\n";
	$base_css2 = "\tbackground-image: url('img/sheet2.jpg');\n\tbackground-size: 700% 700%;\n}\n";

	$use_css = $base_css1;
	$xy_threshold = 8;
	while ($level <= 3) {
		$i = 1;
		$plus_percent = ($level-1)*3600;
		while ($i<=36) {
			$id = "card_$level";
			if ($i<10) {
				$id .= '0';
			}
			$id .= $i;
			$x_percent = ($x-1)*100;
			$y_percent = ($y-1)*100;
			$to_write .= "#gizmos_board #$id, .dijitTooltip #$id {\n\tbackground-position-y: -$y_percent%;\n\tbackground-position-x: -$x_percent%;\n$use_css";			
			$i++;
			$x++;
			if ($x > $xy_threshold) {
				$y++;
				$x = 1;		
			}
			// switch after 228
			if ($i == 29 && $level == 2) {
				$use_css = $base_css2;
				$x=1;
				$y=1;
				$xy_threshold = 7;
			}
		}
		$level++;
	}
	
	$x_percent = ($x-1)*100;
	$y_percent = ($y-1)*100;
	$to_write .= "#gizmos_board #card_901, #gizmos_board #card_902, #gizmos_board #card_903, #gizmos_board #card_904 {\n\tbackground-position-y: -$y_percent%;\n\tbackground-position-x: -$x_percent%;\n$use_css";
	$x++;
	$x_percent = ($x-1)*100;
	$to_write .= "#TODO_mixed {\n\tbackground-position-y: -$y_percent%;\n\tbackground-position-x: -$x_percent%;\n$use_css";
	$x++;
	$n = 1;
	while ($n <= 3) {
		$x_percent = ($x-1)*100;
		$to_write .= "#gizmos_board #deck_$n, .gzs_fd_card_$n {\n\tbackground-position-y: -$y_percent%;\n\tbackground-position-x: -$x_percent%;\n$use_css";
		$n++;
		$x++;
	}
	
	$myfile = fopen("2sheets.css", "w");
	fwrite($myfile, $to_write);
	fclose($myfile);
?>
