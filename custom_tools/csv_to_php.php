<?
	// Instead of returning a string, this should return an i18n array
	// https://bga-devs.github.io/blog/posts/translations-summary/
	function insertSmartCommas($val, $is_converter) {
		$andor = $is_converter ? 'and/or' : 'or';
		$arr = explode(',', $val);
		$size = sizeof($arr);
		if ($size < 2) {
			return $val;
		} else if ($size == 2) {
			return [
				'log' => '${c1} ${andor} ${c2}',
				'args' => [
					'i18n' => ['c1', 'andor', 'c2'],
					'c1' => $arr[0],
					'andor' => $andor,
					'c2' => $arr[1]
				]
			];
			//return $arr[0]." $or_val ".$arr[1];
		} else {
			throw new Exception("ERROR in insertSmartCommas");
			// $ret = "";
			// $i = 1;
			// foreach ($arr as $index => $value) {
			// 	if ($i == $size) {
			// 		$ret .= "$andor $value";
			// 	} else {
			// 		$ret .= "$value, ";
			// 	}
			// }			
		}
	}function insertSmartCommasHtml($val, $is_converter) {
		$andor = $is_converter ? 'and/or' : 'or';
		$arr = explode(',', $val);
		$size = sizeof($arr);
		if ($size < 2) {
			return [
				'log' => '${c1_html}',
				'args' => [
					'c1_html' => $val
				]
			];;
		} else if ($size == 2) {
			return [
				'log' => '${c1_html} ${andor} ${c2_html}',
				'args' => [
					'i18n' => ['andor'],
					'c1_html' => $arr[0],
					'andor' => $andor,
					'c2_html' => $arr[1]
				]
			];
			//return $arr[0]." $or_val ".$arr[1];
		} else {
			throw new Exception("ERROR in insertSmartCommas");
			// $ret = "";
			// $i = 1;
			// foreach ($arr as $index => $value) {
			// 	if ($i == $size) {
			// 		$ret .= "$andor $value";
			// 	} else {
			// 		$ret .= "$value, ";
			// 	}
			// }			
		}
	}
	function addUpgradeDesc($csv_row, $type) {
		$upgrade_type = "upgrade_$type";
		$ut = $csv_row[$upgrade_type];
		if (!empty($ut) && $ut > 0) {
			return "+$ut $type, ";
		} else {
			return '';
		}
	}
	function getTriggerAction($csv_row) {
		$action = $csv_row['trigger_action'];
		switch ($action) {
			case 'pick':
				return 'Pick';
			case 'file':
				return 'File';
			case 'research':
				return 'Research';
			case 'pick_two':
			case 'pick_2':
				return 'Pick up to twice';
			case 'build_level1_for0':
				return 'Build a Level I Gizmo for free';
			case 'score':
				return [
					'log' => 'gain ${number} victory point token(s)',
					'i18n' => ['number'],
					'args' => [
						'number' => 1
					]
				];
			case 'score_2':
				return [
					'log' => 'gain ${number} victory point token(s)',
					'i18n' => ['number'],
					'args' => [
						'number' => 2
					]
				];
			case 'draw_3':
				return [
					'log' => '${draw} ${up_to_3}',
					'args' => [
						'draw' => 'draw',
						'up_to_3' => 'up to 3 times',
						'i18n' => ['draw','up_to_3']
					]
				];
			default:
				return str_replace('_', ' ', $action);			
		}		
	}
	function getTooltipForRow($csv_row) {
		$type = $csv_row['effect_type'];
		$tooltip = [];
		switch ($type) {
			case 'converter':
				$to = $csv_row['convert_to'];
				if ($to == 'any2') {
					$tooltip = [
						'log' => 'Convert ${number} ${from} energy to ${to} energy',
						'args' => [
							'i18n' => ['number', 'from', 'to'],
							'number' => 'up to 2',
							'from' => insertSmartCommas($csv_row['convert_from'], false),
							'to' => 'any'
						]
					];
					//$tooltip = "Convert up to two ".insertSmartCommas($csv_row['convert_from'], false)." energy to any energy";					
				} else {
					$tooltip = [
						'log' => 'Convert ${number} ${from} energy to ${to} energy', // $this->tooltip_types['converter'],
						'args' => [
							'i18n' => ['number', 'from', 'to'],
							'number' => '1',
							'from' => insertSmartCommasHtml($csv_row['convert_from'], true),
							'to' => $csv_row['convert_to']
						]
					];
					//$tooltip = "Convert one ".insertSmartCommas($csv_row['convert_from'], true)." energy to ".$csv_row['convert_to']." energy";					
				}			
				break;
			case 'trigger_pick':
				$tooltip = [
					'log' => '${trigger} a ${object}: ${action}',
					'args' => [
						'i18n' => ['trigger', 'object', 'action'],
						'trigger' => 'When you Pick',
						'object' => insertSmartCommasHtml($csv_row['trigger_color'], false),
						'action' => 'draw'
					]
				];
				//$tooltip = "When you pick a ".insertSmartCommas($csv_row['trigger_color'], false)." energy$when_delim draw";
				break;
			case 'trigger_file':
				$tooltip = [
					'log' => '${trigger} a ${object}: ${action}',
					'args' => [
						'i18n' => ['trigger', 'object', 'action'],
						'trigger' => 'When you File',
						'color' => '',
						'space' => '',
						'object' => 'Gizmo',
						'action' => getTriggerAction($csv_row)
					]
				];
				//$tooltip = "When you file a gizmo$when_delim ".getTriggerAction($csv_row);
				break;
			case 'trigger_build':
				$tooltip = [
					'log' => '${trigger} a ${object}: ${action}',
					'args' => [
						'i18n' => ['trigger', 'object', 'action'],
						'trigger' => 'When you Build',
						'object' => [
							'log' => '${color} ${gizmo}',
							'args' => [
								'i18n' => ['color', 'gizmo'],
								'gizmo' => 'Gizmo',
								'color' => insertSmartCommas($csv_row['trigger_color'], false)
							]
						],
						'action' => getTriggerAction($csv_row)
					]
				];
				//$tooltip = "When you build a ".insertSmartCommas($csv_row['trigger_color'], false)." gizmo$when_delim ".getTriggerAction($csv_row);
				break;
			case 'trigger_build_from_file':
			case 'trigger_buildfromfile':
				$tooltip = [
					'log' => '${trigger} a ${object}: ${action}',
					'args' => [
						'i18n' => ['trigger', 'object', 'action'],
						'trigger' => 'When you Build',
						'color' => '',
						'space' => '',
						'object' => 'Gizmo from your Archive',
						'action' => getTriggerAction($csv_row)
					]
				];
				//$tooltip = "When you build a gizmo from your archive$when_delim ".getTriggerAction($csv_row);
				break;
			case 'trigger_build_level_2':
			case 'trigger_buildlevel2':
				$tooltip = [
					'log' => '${trigger} a ${object}: ${action}', //$this->tooltip_types['trigger'],
					'args' => [
						'i18n' => ['trigger', 'object', 'action'],
						'trigger' => 'When you Build',
						'color' => '',
						'space' => '',
						'object' => 'Level II Gizmo',
						'action' => getTriggerAction($csv_row)
					]
				];
				//$tooltip = "When you build a Level II gizmo$when_delim ".getTriggerAction($csv_row);
				break;
				
			case 'upgrade':		
				$upgrade_types = array(
					'upgrade_energy' => 'energy capacity',
					'upgrade_archive' => 'archive limit',
					'upgrade_research' => 'research quantity',
					'no' => 'You cannot ${action} for the rest of the game',
					'no_research' => 'Research',
					'no_file' => 'File',
					'discount' => 'You may spend 1 less Energy when building a ${discount_type}',
					'discount_buildfromfile' => 'Gizmo from your Archive',
					'discount_buildfromresearch' => 'Gizmo directly from Research',
					'discount_level2' => 'Level II Gizmo',
					'score' => 'At the end of the game score points equal to your ${score}',
					'score_energy' => 'remaining unspent Energy',
					'score_scores' => 'victory point token count'
				);
				$special = $csv_row['upgrade_special'];
				if (empty($special)) {
					$infos = [];
					$args = [];
					foreach( [1 => 'upgrade_energy', 2 => 'upgrade_archive', 3 => 'upgrade_research'] as $i => $upg ) {
						if (!empty($csv_row[$upg])) {
							$infos[] = '+${num'. $i. '} ${upg'. $i .'}';
							$args['num' . $i] = $csv_row[$upg];
							$args['upg' . $i] = $upgrade_types[$upg]; // constants holding clienttranslated names of resources
							$args['i18n'][] = 'upg' . $i;
							$args['i18n'][] = 'num' . $i;
						}
					}
					$tooltip = [
						'log' => 'Upgrade: ${details}',
						'args' => [
							'i18n' => ['details'],
							'details' => [
								'log' => implode(', ', $infos),
								'args' => $args
							]
						]
					];
					//$tooltip .= addUpgradeDesc($csv_row, 'energy') . addUpgradeDesc($csv_row, 'archive') . addUpgradeDesc($csv_row, 'research');
					//$tooltip = substr($tooltip, 0, -2);
				} else {
					//$tooltip = "Upgrade: ".str_replace('_', ' ', $special);
					$tooltip = [];
					switch ($special) {
						case 'discount_buildfromfile':
						case 'discount_buildfromresearch':
						case 'discount_level2':
							$tooltip['log'] = 'You may spend 1 less Energy when building a ${discount_type}';
							$tooltip['args'] = [
								'i18n' => ['discount_type'],
								'discount_type' => $upgrade_types[$special]
							];
							break;
						case 'no_research':
						case 'no_file':
							$tooltip['log'] = 'You cannot ${action} for the rest of the game';
							$tooltip['args'] = [
								'i18n' => ['action'],
								'action' => $upgrade_types[$special]
							];
							break;
						case 'score_energy':
						case 'score_scores':
							$tooltip['log'] = 'At the end of the game score points equal to your ${score}';
							$tooltip['args'] = [
								'i18n' => ['score'],
								'score' => $upgrade_types[$special]
							];
							break;
						default:
							$tooltip = "Unhandled upgrade_special: $special";
							break;
					}
				}
				break;
			default:
				$tooltip = "Unhandled type: $type";
				break;
		}
		return $tooltip;
	}

    /* Map Rows and Loop Through Them */
	$int_keys = array( 'id', 'level', 'cost', 'points', 'upgrade_energy', 'upgrade_archive', 'upgrade_research' );
    $rows   = array_map('str_getcsv', file('2022-09-24_materials_gizmos.csv')); // 'materials_gizmos_18Jul2022.csv'));
    $header = array_shift($rows);
	$to_write = '$this->mt_gizmos = array('."\n";
	$i = 0;
    foreach($rows as $row) {		
        $csv_row = array_combine($header, $row);
		$to_write .= "\t".$csv_row['id']." => array(\n";
		// if (empty($csv_row['tooltip'])) {
		// 	$csv_row['tooltip'] = ;
		// }
		foreach ($csv_row as $key => $value) {
			if (!empty($value) || $value == 0) {
				if ($key == 'tooltip') {
					//$value = "clienttranslate('$value')";
					continue;
				} else if ( $key == 'trigger_color' ) {
					$colors = explode(',', $value);
					$value = "array(\n";
					foreach ($colors as $index => $color) {
						$value .= "\t\t\t'$color',\n";
					}
					$value = substr($value, 0, -2)."\n\t\t)";
					
				} else if ( in_array($key, $int_keys) ) {
					// no quotes
				} else {
					$value = "'$value'";
				}				
				$to_write .= "\t\t'$key' => $value,\n";
			}
		}
		$to_write .= "\t\t'tooltip' => ".var_export( getTooltipForRow($csv_row), true );
		//$to_write = substr($to_write, 0, -2)."\n";
		$to_write .= "\t),\n";
		// $i++;
		// if ($i > 4)
			// break;
    }
	$to_write = substr($to_write, 0, -2)."\n);";
	$myfile = fopen("php_array_v3.php", "w");
	fwrite($myfile, $to_write);
	fclose($myfile);
?>
