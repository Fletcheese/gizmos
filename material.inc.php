<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * gizmos implementation : © Fletcheese <1337ch33z@gmail.com>
 * 
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * material.inc.php
 *
 * gizmos game material description
 *
 * Here, you can describe the material of your game with PHP variables.
 *   
 * This file is loaded in your game logic class constructor, ie these variables
 * are available everywhere in your game logic code.
 *
 */
 
$this->mt_colors = array(
	0 => clienttranslate('red'),
	1 => clienttranslate('blue'),
	2 => clienttranslate('black'),
	3 => clienttranslate('yellow'),
	4 => clienttranslate('any'), // in the context of colors,
	5 => clienttranslate('multi') // in the context of colors
);

$this->tooltip_types = array(
	'trigger' => clienttranslate('When you ${trigger} a ${color}${space}${object}: ${action}'),
	'converter' => clienttranslate('Convert ${number} ${from} energy to ${to} energy'),
	'upgrade' => clienttranslate('Upgrade: ${details}')	
);
$this->action_types = array(
	0 => clienttranslate('File'),
	1 => clienttranslate('Pick'),
	2 => clienttranslate('Build'),
	3 => clienttranslate('Research'),
	4 => clienttranslate('draw'),
	5 => clienttranslate('gain ${number} victory point token(s)'),
	6 => clienttranslate('Build a Level I Gizmo for free'),
	7 => clienttranslate('up to 3 times'),
  8 => clienttranslate('up to 2'),
  9 => clienttranslate('Pick up to twice')
);
// $this->present_tense_actions = array(
// 	0 => clienttranslate('Files'),
// 	1 => clienttranslate('Picks'),
// 	2 => clienttranslate('Builds'),
// 	3 => clienttranslate('Researches'),
// 	4 => clienttranslate('draws'),
// 	5 => clienttranslate('gains ${n} victory point token(s)')
// );
$this->upgrade_types = array(
	'upgrade_energy' => clienttranslate('energy capacity'),
	'upgrade_archive' => clienttranslate('archive limit'),
	'upgrade_research' => clienttranslate('research quantity'),
	'no' => clienttranslate('You cannot ${action} for the rest of the game'),
	'no_research' => clienttranslate('Research'),
	'no_file' => clienttranslate('File'),
	'discount' => clienttranslate('You may spend 1 less Energy when building a ${discount_type}'),
	'discount_buildfromfile' => clienttranslate('Gizmo from your Archive'),
	'discount_buildfromresearch' => clienttranslate('Gizmo directly from Research'),
	'discount_level2' => clienttranslate('Level II Gizmo'),
	'score' => clienttranslate('At the end of the game score points equal to your ${score}'),
	'score_energy' => clienttranslate('remaining unspent Energy'),
	'score_scores' => clienttranslate('victory point token count')
);
$this->misc_terms = array(
	0 => clienttranslate('Gizmo'), // The game cards that represent inventions
	1 => clienttranslate('Energy'), // Spendable colored resource for building Gizmos - may also be referred to as spheres or tokens
	2 => clienttranslate('Level'), // Level I, II, or III Gizmos/Decks
	3 => clienttranslate('the row'), // where Gizmos exist on the board to be Built or Filed
	4 => clienttranslate('or'), // in the context of colors e.g. red or blue
	5 => clienttranslate('and/or'), // in the context of colors e.g. red and/or blue
);

$this->mt_gizmos = array(
	101 => array(
		'id' => 101,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'yellow',
    'to' => 'any',
  ),
)	),
	102 => array(
		'id' => 102,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'red',
    'to' => 'any',
  ),
)	),
	103 => array(
		'id' => 103,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'black',
    'to' => 'any',
  ),
)	),
	104 => array(
		'id' => 104,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'blue',
    'to' => 'any',
  ),
)	),
	105 => array(
		'id' => 105,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'red',
    'to' => 'any',
  ),
)	),
	106 => array(
		'id' => 106,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'yellow',
    'to' => 'any',
  ),
)	),
	107 => array(
		'id' => 107,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'blue',
    'to' => 'any',
  ),
)	),
	108 => array(
		'id' => 108,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'black',
    'to' => 'any',
  ),
)	),
	109 => array(
		'id' => 109,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'blue',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	110 => array(
		'id' => 110,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'red',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	111 => array(
		'id' => 111,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'yellow',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	112 => array(
		'id' => 112,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'black',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	113 => array(
		'id' => 113,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'yellow',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	114 => array(
		'id' => 114,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'black',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	115 => array(
		'id' => 115,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'blue',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	116 => array(
		'id' => 116,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 'red',
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	117 => array(
		'id' => 117,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	118 => array(
		'id' => 118,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	119 => array(
		'id' => 119,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	120 => array(
		'id' => 120,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	121 => array(
		'id' => 121,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 0,
		'upgrade_research' => 1,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg3',
          3 => 'num3',
        ),
        'num3' => '1',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	122 => array(
		'id' => 122,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 0,
		'upgrade_research' => 1,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg3',
          3 => 'num3',
        ),
        'num3' => '1',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	123 => array(
		'id' => 123,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 0,
		'upgrade_research' => 1,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg3',
          3 => 'num3',
        ),
        'num3' => '1',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	124 => array(
		'id' => 124,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 0,
		'upgrade_research' => 1,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg3',
          3 => 'num3',
        ),
        'num3' => '1',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	125 => array(
		'id' => 125,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 1,
		'upgrade_research' => 0,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
      ),
    ),
  ),
)	),
	126 => array(
		'id' => 126,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 1,
		'upgrade_research' => 0,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
      ),
    ),
  ),
)	),
	127 => array(
		'id' => 127,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 1,
		'upgrade_research' => 0,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
      ),
    ),
  ),
)	),
	128 => array(
		'id' => 128,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 1,
		'upgrade_archive' => 1,
		'upgrade_research' => 0,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}',
      'args' => 
      array (
        'num1' => '1',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
      ),
    ),
  ),
)	),
	129 => array(
		'id' => 129,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'blue',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	130 => array(
		'id' => 130,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'yellow',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	131 => array(
		'id' => 131,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'black',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	132 => array(
		'id' => 132,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'red',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	133 => array(
		'id' => 133,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'red',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	134 => array(
		'id' => 134,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'black',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	135 => array(
		'id' => 135,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'yellow',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	136 => array(
		'id' => 136,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 'blue',
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	201 => array(
		'id' => 201,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 2,
		'upgrade_archive' => 1,
		'upgrade_research' => 2,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '2',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
          4 => 'upg3',
          5 => 'num3',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
        'num3' => '2',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	202 => array(
		'id' => 202,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 2,
		'upgrade_archive' => 1,
		'upgrade_research' => 2,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '2',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
          4 => 'upg3',
          5 => 'num3',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
        'num3' => '2',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	203 => array(
		'id' => 203,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 2,
		'upgrade_archive' => 1,
		'upgrade_research' => 2,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '2',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
          4 => 'upg3',
          5 => 'num3',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
        'num3' => '2',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	204 => array(
		'id' => 204,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 2,
		'upgrade_archive' => 1,
		'upgrade_research' => 2,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}, +${num2} ${upg2}, +${num3} ${upg3}',
      'args' => 
      array (
        'num1' => '2',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
          2 => 'upg2',
          3 => 'num2',
          4 => 'upg3',
          5 => 'num3',
        ),
        'num2' => '1',
        'upg2' => 'archive limit',
        'num3' => '2',
        'upg3' => 'research quantity',
      ),
    ),
  ),
)	),
	205 => array(
		'id' => 205,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo from your Archive',
    'action' => 'Pick up to twice',
  ),
)	),
	206 => array(
		'id' => 206,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo from your Archive',
    'action' => 'Pick up to twice',
  ),
)	),
	207 => array(
		'id' => 207,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo from your Archive',
    'action' => 'Pick up to twice',
  ),
)	),
	208 => array(
		'id' => 208,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo from your Archive',
    'action' => 'Pick up to twice',
  ),
)	),
	209 => array(
		'id' => 209,
		'color' => 'black',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'yellow',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	210 => array(
		'id' => 210,
		'color' => 'blue',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'yellow',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	211 => array(
		'id' => 211,
		'color' => 'red',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'blue',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	212 => array(
		'id' => 212,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_pick',
		'trigger_action' => 'draw',
		'trigger_color' => array(
			'red',
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Pick',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'red',
        'andor' => 'or',
        'c2' => 'blue',
      ),
    ),
    'space' => ' ',
    'object' => 'Energy',
    'action' => 'draw',
  ),
)	),
	213 => array(
		'id' => 213,
		'color' => 'black',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'any2',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => 'up to 2',
    'from' => 'blue',
    'to' => 'any',
  ),
)	),
	214 => array(
		'id' => 214,
		'color' => 'blue',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'any2',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => 'up to 2',
    'from' => 'black',
    'to' => 'any',
  ),
)	),
	215 => array(
		'id' => 215,
		'color' => 'red',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'any2',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => 'up to 2',
    'from' => 'yellow',
    'to' => 'any',
  ),
)	),
	216 => array(
		'id' => 216,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'any2',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => 'up to 2',
    'from' => 'red',
    'to' => 'any',
  ),
)	),
	217 => array(
		'id' => 217,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'yellow',
    'to' => '2',
  ),
)	),
	218 => array(
		'id' => 218,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'red',
    'to' => '2',
  ),
)	),
	219 => array(
		'id' => 219,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'blue',
    'to' => '2',
  ),
)	),
	220 => array(
		'id' => 220,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'black',
    'to' => '2',
  ),
)	),
	221 => array(
		'id' => 221,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'red',
    'to' => '2',
  ),
)	),
	222 => array(
		'id' => 222,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'yellow',
    'to' => '2',
  ),
)	),
	223 => array(
		'id' => 223,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'black',
    'to' => '2',
  ),
)	),
	224 => array(
		'id' => 224,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'blue',
    'to' => '2',
  ),
)	),
	225 => array(
		'id' => 225,
		'color' => 'black',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'red',
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'red',
        'andor' => 'or',
        'c2' => 'blue',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	226 => array(
		'id' => 226,
		'color' => 'blue',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'yellow',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	227 => array(
		'id' => 227,
		'color' => 'red',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'blue',
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'yellow',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	228 => array(
		'id' => 228,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'black',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'black',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	229 => array(
		'id' => 229,
		'color' => 'black',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'yellow',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	230 => array(
		'id' => 230,
		'color' => 'blue',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'yellow',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	231 => array(
		'id' => 231,
		'color' => 'red',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'blue',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	232 => array(
		'id' => 232,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'pick',
		'trigger_color' => array(
			'blue',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Pick',
  ),
)	),
	233 => array(
		'id' => 233,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'blue',
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'yellow',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	234 => array(
		'id' => 234,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'black',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'black',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	235 => array(
		'id' => 235,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'yellow',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	236 => array(
		'id' => 236,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score',
		'trigger_color' => array(
			'red',
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'red',
        'andor' => 'or',
        'c2' => 'blue',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	301 => array(
		'id' => 301,
		'color' => 'black',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 4,
		'upgrade_archive' => 0,
		'upgrade_research' => 0,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}',
      'args' => 
      array (
        'num1' => '4',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
        ),
      ),
    ),
  ),
)	),
	302 => array(
		'id' => 302,
		'color' => 'blue',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'upgrade',
		'upgrade_energy' => 4,
		'upgrade_archive' => 0,
		'upgrade_research' => 0,
		'tooltip' => array (
  'log' => 'Upgrade: ${details}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'details',
    ),
    'details' => 
    array (
      'log' => '+${num1} ${upg1}',
      'args' => 
      array (
        'num1' => '4',
        'upg1' => 'energy capacity',
        'i18n' => 
        array (
          0 => 'upg1',
          1 => 'num1',
        ),
      ),
    ),
  ),
)	),
	303 => array(
		'id' => 303,
		'color' => 'red',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'converter',
		'convert_from' => 'any',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'any',
    'to' => 'any',
  ),
)	),
	304 => array(
		'id' => 304,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'converter',
		'convert_from' => 'any',
		'convert_to' => 'any',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 'any',
    'to' => 'any',
  ),
)	),
	305 => array(
		'id' => 305,
		'color' => 'black',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'converter',
		'convert_from' => 'blue,yellow',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'and/or',
        'c2' => 'yellow',
      ),
    ),
    'to' => '2',
  ),
)	),
	306 => array(
		'id' => 306,
		'color' => 'blue',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'converter',
		'convert_from' => 'black,red',
		'convert_to' => 'two',
		'tooltip' => array (
  'log' => 'Convert ${number} ${from} energy to ${to} energy',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'number',
      1 => 'from',
      2 => 'to',
    ),
    'number' => '1',
    'from' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'black',
        'andor' => 'and/or',
        'c2' => 'red',
      ),
    ),
    'to' => '2',
  ),
)	),
	307 => array(
		'id' => 307,
		'color' => 'red',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'score_2',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo from your Archive',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 2,
      ),
    ),
  ),
)	),
	308 => array(
		'id' => 308,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'score_2',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo from your Archive',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 2,
      ),
    ),
  ),
)	),
	309 => array(
		'id' => 309,
		'color' => 'black',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'score',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	310 => array(
		'id' => 310,
		'color' => 'red',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'score',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 1,
      ),
    ),
  ),
)	),
	311 => array(
		'id' => 311,
		'color' => 'blue',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw_3',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'draw up to 3 times',
  ),
)	),
	312 => array(
		'id' => 312,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw_3',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'draw up to 3 times',
  ),
)	),
	313 => array(
		'id' => 313,
		'color' => 'black',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'trigger_build_level_2',
		'trigger_action' => 'pick_2',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Level II Gizmo',
    'action' => 'Pick up to twice',
  ),
)	),
	314 => array(
		'id' => 314,
		'color' => 'red',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'trigger_build_level_2',
		'trigger_action' => 'pick_2',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => '',
    'space' => '',
    'object' => 'Level II Gizmo',
    'action' => 'Pick up to twice',
  ),
)	),
	315 => array(
		'id' => 315,
		'color' => 'blue',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_level2',
		'tooltip' => array (
  'log' => 'You may spend 1 less Energy when building a ${discount_type}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'discount_type',
    ),
    'discount_type' => 'Level II Gizmo',
  ),
)	),
	316 => array(
		'id' => 316,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_level2',
		'tooltip' => array (
  'log' => 'You may spend 1 less Energy when building a ${discount_type}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'discount_type',
    ),
    'discount_type' => 'Level II Gizmo',
  ),
)	),
	317 => array(
		'id' => 317,
		'color' => 'black',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score_2',
		'trigger_color' => array(
			'red',
			'blue'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'red',
        'andor' => 'or',
        'c2' => 'blue',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 2,
      ),
    ),
  ),
)	),
	318 => array(
		'id' => 318,
		'color' => 'red',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'score_2',
		'trigger_color' => array(
			'yellow',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 
    array (
      'log' => 'gain ${number} victory point token(s)',
      'i18n' => 
      array (
        0 => 'number',
      ),
      'args' => 
      array (
        'number' => 2,
      ),
    ),
  ),
)	),
	319 => array(
		'id' => 319,
		'color' => 'blue',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'build_level1_for0',
		'trigger_color' => array(
			'yellow',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Build a Level I Gizmo for free',
  ),
)	),
	320 => array(
		'id' => 320,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'build_level1_for0',
		'trigger_color' => array(
			'blue',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Build a Level I Gizmo for free',
  ),
)	),
	321 => array(
		'id' => 321,
		'color' => 'black',
		'level' => 3,
		'cost' => 4,
		'points' => 8,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_research',
		'tooltip' => array (
  'log' => 'You cannot ${action} for the rest of the game',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'action',
    ),
    'action' => 'Research',
  ),
)	),
	322 => array(
		'id' => 322,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 4,
		'points' => 8,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_research',
		'tooltip' => array (
  'log' => 'You cannot ${action} for the rest of the game',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'action',
    ),
    'action' => 'Research',
  ),
)	),
	323 => array(
		'id' => 323,
		'color' => 'blue',
		'level' => 3,
		'cost' => 4,
		'points' => 7,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_file',
		'tooltip' => array (
  'log' => 'You cannot ${action} for the rest of the game',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'action',
    ),
    'action' => 'File',
  ),
)	),
	324 => array(
		'id' => 324,
		'color' => 'red',
		'level' => 3,
		'cost' => 4,
		'points' => 7,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_file',
		'tooltip' => array (
  'log' => 'You cannot ${action} for the rest of the game',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'action',
    ),
    'action' => 'File',
  ),
)	),
	325 => array(
		'id' => 325,
		'color' => 'black',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'file',
		'trigger_color' => array(
			'blue',
			'yellow'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'yellow',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'File',
  ),
)	),
	326 => array(
		'id' => 326,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'file',
		'trigger_color' => array(
			'black',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'black',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'File',
  ),
)	),
	327 => array(
		'id' => 327,
		'color' => 'blue',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromfile',
		'tooltip' => array (
  'log' => 'You may spend 1 less Energy when building a ${discount_type}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'discount_type',
    ),
    'discount_type' => 'Gizmo from your Archive',
  ),
)	),
	328 => array(
		'id' => 328,
		'color' => 'red',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromfile',
		'tooltip' => array (
  'log' => 'You may spend 1 less Energy when building a ${discount_type}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'discount_type',
    ),
    'discount_type' => 'Gizmo from your Archive',
  ),
)	),
	329 => array(
		'id' => 329,
		'color' => 'black',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromresearch',
		'tooltip' => array (
  'log' => 'You may spend 1 less Energy when building a ${discount_type}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'discount_type',
    ),
    'discount_type' => 'Gizmo directly from Research',
  ),
)	),
	330 => array(
		'id' => 330,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromresearch',
		'tooltip' => array (
  'log' => 'You may spend 1 less Energy when building a ${discount_type}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'discount_type',
    ),
    'discount_type' => 'Gizmo directly from Research',
  ),
)	),
	331 => array(
		'id' => 331,
		'color' => 'blue',
		'level' => 3,
		'cost' => 7,
		'points' => 7,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'research',
		'trigger_color' => array(
			'yellow',
			'red'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'yellow',
        'andor' => 'or',
        'c2' => 'red',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Research',
  ),
)	),
	332 => array(
		'id' => 332,
		'color' => 'red',
		'level' => 3,
		'cost' => 7,
		'points' => 7,
		'effect_type' => 'trigger_build',
		'trigger_action' => 'research',
		'trigger_color' => array(
			'blue',
			'black'
		),
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'Build',
    'color' => 
    array (
      'log' => '${c1} ${andor} ${c2}',
      'args' => 
      array (
        'i18n' => 
        array (
          0 => 'c1',
          1 => 'andor',
          2 => 'c2',
        ),
        'c1' => 'blue',
        'andor' => 'or',
        'c2' => 'black',
      ),
    ),
    'space' => ' ',
    'object' => 'Gizmo',
    'action' => 'Research',
  ),
)	),
	333 => array(
		'id' => 333,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_energy',
		'tooltip' => array (
  'log' => 'At the end of the game score points equal to your ${score}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'score',
    ),
    'score' => 'remaining unspent Energy',
  ),
)	),
	334 => array(
		'id' => 334,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_energy',
		'tooltip' => array (
  'log' => 'At the end of the game score points equal to your ${score}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'score',
    ),
    'score' => 'remaining unspent Energy',
  ),
)	),
	335 => array(
		'id' => 335,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_scores',
		'tooltip' => array (
  'log' => 'At the end of the game score points equal to your ${score}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'score',
    ),
    'score' => 'victory point token count',
  ),
)	),
	336 => array(
		'id' => 336,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_scores',
		'tooltip' => array (
  'log' => 'At the end of the game score points equal to your ${score}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'score',
    ),
    'score' => 'victory point token count',
  ),
)	),
	901 => array(
		'id' => 901,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'draw',
  ),
)	),
	902 => array(
		'id' => 902,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'draw',
  ),
)	),
	903 => array(
		'id' => 903,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'draw',
  ),
)	),
	904 => array(
		'id' => 904,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => array (
  'log' => 'When you ${trigger} a ${color}${space}${object}: ${action}',
  'args' => 
  array (
    'i18n' => 
    array (
      0 => 'trigger',
      1 => 'color',
      2 => 'space',
      3 => 'object',
      4 => 'action',
    ),
    'trigger' => 'File',
    'color' => '',
    'space' => '',
    'object' => 'Gizmo',
    'action' => 'draw',
  ),
)	)
);