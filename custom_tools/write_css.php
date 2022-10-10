<?

    /* Map Rows and Loop Through Them 
	#card_101 {
		background-position-y: 0%;
	}
	
	
	*/
	$to_write = '';
	$level = 1;
	while ($level <= 3) {
		$i = 1;
		$plus_percent = ($level-1)*3600;
		while ($i<=36) {
			$id = "card_$level";
			if ($i<10) {
				$id .= '0';
			}
			$id .= $i;
			$percent = ($i-1)*100+$plus_percent;
			$to_write .= "#$id {\n\tbackground-position-y: -$percent%;\n}\n";			
			$i++;
		}
		$level++;
	}
	
	$myfile = fopen("cards_css.css", "w");
	fwrite($myfile, $to_write);
	fclose($myfile);
?>
