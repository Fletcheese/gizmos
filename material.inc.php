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
	0 => 'red',
	1 => 'blue',
	2 => 'black',
	3 => 'yellow',
	4 => 'multi'
);

$this->mt_trigger_types = array(
	0 => 'trigger_file',
	1 => 'trigger_pick',
	2 => 'trigger_build',
	3 => 'trigger_build_from_file',
	4 => 'trigger_build_level2'
);

$this->tooltip_types = array(
	'build_trigger' => clienttranslate('When you build a ${color} card: ${action}'),
	'converter' => clienttranslate('Convert one ${from} energy to ${to] energy')
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
		'tooltip' => $this->tooltip_types['converter']
	),
	102 => array(
		'id' => 102,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one red energy to any energy')
	),
	103 => array(
		'id' => 103,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one black energy to any energy')
	),
	104 => array(
		'id' => 104,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one blue energy to any energy')
	),
	105 => array(
		'id' => 105,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one red energy to any energy')
	),
	106 => array(
		'id' => 106,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one yellow energy to any energy')
	),
	107 => array(
		'id' => 107,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one blue energy to any energy')
	),
	108 => array(
		'id' => 108,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one black energy to any energy')
	),
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
		'tooltip' => clienttranslate('When you pick a blue energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a red energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a yellow energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a black energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a yellow energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a black energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a blue energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a red energy: draw')
	),
	117 => array(
		'id' => 117,
		'color' => 'black',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => clienttranslate('When you file a gizmo: pick')
	),
	118 => array(
		'id' => 118,
		'color' => 'blue',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => clienttranslate('When you file a gizmo: pick')
	),
	119 => array(
		'id' => 119,
		'color' => 'red',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => clienttranslate('When you file a gizmo: pick')
	),
	120 => array(
		'id' => 120,
		'color' => 'yellow',
		'level' => 1,
		'cost' => 1,
		'points' => 1,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'pick',
		'tooltip' => clienttranslate('When you file a gizmo: pick')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 archive')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 archive')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 archive')
	),
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
		'tooltip' => clienttranslate('Upgrade: +1 energy, +1 archive')
	),
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
		'tooltip' => clienttranslate('When you build a blue gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a yellow gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a black gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a red gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a red gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('When you build a black gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('When you build a yellow gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('When you build a blue gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('Upgrade: +2 energy, +1 archive, +2 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +2 energy, +1 archive, +2 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +2 energy, +1 archive, +2 research')
	),
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
		'tooltip' => clienttranslate('Upgrade: +2 energy, +1 archive, +2 research')
	),
	205 => array(
		'id' => 205,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => clienttranslate('When you build a gizmo from your archive: pick two')
	),
	206 => array(
		'id' => 206,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => clienttranslate('When you build a gizmo from your archive: pick two')
	),
	207 => array(
		'id' => 207,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => clienttranslate('When you build a gizmo from your archive: pick two')
	),
	208 => array(
		'id' => 208,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'pick_two',
		'tooltip' => clienttranslate('When you build a gizmo from your archive: pick two')
	),
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
		'tooltip' => clienttranslate('When you pick a yellow or red energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a yellow or black energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a blue or black energy: draw')
	),
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
		'tooltip' => clienttranslate('When you pick a red or blue energy: draw')
	),
	213 => array(
		'id' => 213,
		'color' => 'black',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'any2',
		'tooltip' => clienttranslate('Convert up to two blue energy to any energy')
	),
	214 => array(
		'id' => 214,
		'color' => 'blue',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'any2',
		'tooltip' => clienttranslate('Convert up to two black energy to any energy')
	),
	215 => array(
		'id' => 215,
		'color' => 'red',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'any2',
		'tooltip' => clienttranslate('Convert up to two yellow energy to any energy')
	),
	216 => array(
		'id' => 216,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 2,
		'points' => 2,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'any2',
		'tooltip' => clienttranslate('Convert up to two red energy to any energy')
	),
	217 => array(
		'id' => 217,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one yellow energy to two energy')
	),
	218 => array(
		'id' => 218,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one red energy to two energy')
	),
	219 => array(
		'id' => 219,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one blue energy to two energy')
	),
	220 => array(
		'id' => 220,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one black energy to two energy')
	),
	221 => array(
		'id' => 221,
		'color' => 'black',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'red',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one red energy to two energy')
	),
	222 => array(
		'id' => 222,
		'color' => 'blue',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'yellow',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one yellow energy to two energy')
	),
	223 => array(
		'id' => 223,
		'color' => 'red',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'black',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one black energy to two energy')
	),
	224 => array(
		'id' => 224,
		'color' => 'yellow',
		'level' => 2,
		'cost' => 3,
		'points' => 3,
		'effect_type' => 'converter',
		'convert_from' => 'blue',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one blue energy to two energy')
	),
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
		'tooltip' => clienttranslate('When you build a red or blue gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or black gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a blue or yellow gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a black or red gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or red gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or red gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a blue or black gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a blue or black gizmo: pick')
	),
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
		'tooltip' => clienttranslate('When you build a blue or yellow gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('When you build a black or red gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or black gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('When you build a red or blue gizmo: gain a victory point')
	),
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
		'tooltip' => clienttranslate('Upgrade: +4 energy')
	),
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
		'tooltip' => clienttranslate('Upgrade: +4 energy')
	),
	303 => array(
		'id' => 303,
		'color' => 'red',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'converter',
		'convert_from' => 'any',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one any energy to any energy')
	),
	304 => array(
		'id' => 304,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'converter',
		'convert_from' => 'any',
		'convert_to' => 'any',
		'tooltip' => clienttranslate('Convert one any energy to any energy')
	),
	305 => array(
		'id' => 305,
		'color' => 'black',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'converter',
		'convert_from' => 'blue,yellow',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one blue and/or yellow energy to two energy')
	),
	306 => array(
		'id' => 306,
		'color' => 'blue',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'converter',
		'convert_from' => 'black,red',
		'convert_to' => 'two',
		'tooltip' => clienttranslate('Convert one black and/or red energy to two energy')
	),
	307 => array(
		'id' => 307,
		'color' => 'red',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'score_2',
		'tooltip' => clienttranslate('When you build a gizmo from your archive: gain two victory points')
	),
	308 => array(
		'id' => 308,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'trigger_build_from_file',
		'trigger_action' => 'score_2',
		'tooltip' => clienttranslate('When you build a gizmo from your archive: gain two victory points')
	),
	309 => array(
		'id' => 309,
		'color' => 'black',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'score',
		'tooltip' => clienttranslate('When you file a gizmo: gain a victory point')
	),
	310 => array(
		'id' => 310,
		'color' => 'red',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'score',
		'tooltip' => clienttranslate('When you file a gizmo: gain a victory point')
	),
	311 => array(
		'id' => 311,
		'color' => 'blue',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw_3',
		'tooltip' => clienttranslate('When you file a gizmo: draw 3')
	),
	312 => array(
		'id' => 312,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 4,
		'points' => 4,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw_3',
		'tooltip' => clienttranslate('When you file a gizmo: draw 3')
	),
	313 => array(
		'id' => 313,
		'color' => 'black',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'trigger_build_level_2',
		'trigger_action' => 'pick_2',
		'tooltip' => clienttranslate('When you build a Level II gizmo: pick 2')
	),
	314 => array(
		'id' => 314,
		'color' => 'red',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'trigger_build_level_2',
		'trigger_action' => 'pick_2',
		'tooltip' => clienttranslate('When you build a Level II gizmo: pick 2')
	),
	315 => array(
		'id' => 315,
		'color' => 'blue',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_level2',
		'tooltip' => clienttranslate('You may spend 1 less Energy when building Level 2 Gizmos')
	),
	316 => array(
		'id' => 316,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_level2',
		'tooltip' => clienttranslate('You may spend 1 less Energy when building Level 2 Gizmos')
	),
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
		'tooltip' => clienttranslate('When you build a red or blue gizmo: gain two victory points')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or black gizmo: gain two victory points')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or red Gizmo: build a Level I Gizmo for free')
	),
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
		'tooltip' => clienttranslate('When you build a blue or black Gizmo: build a Level I Gizmo for free')
	),
	321 => array(
		'id' => 321,
		'color' => 'black',
		'level' => 3,
		'cost' => 4,
		'points' => 8,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_research',
		'tooltip' => clienttranslate('You cannot Research for the rest of the game')
	),
	322 => array(
		'id' => 322,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 4,
		'points' => 8,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_research',
		'tooltip' => clienttranslate('You cannot Research for the rest of the game')
	),
	323 => array(
		'id' => 323,
		'color' => 'blue',
		'level' => 3,
		'cost' => 4,
		'points' => 7,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_file',
		'tooltip' => clienttranslate('You cannot File for the rest of the game')
	),
	324 => array(
		'id' => 324,
		'color' => 'red',
		'level' => 3,
		'cost' => 4,
		'points' => 7,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'no_file',
		'tooltip' => clienttranslate('You cannot File for the rest of the game')
	),
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
		'tooltip' => clienttranslate('When you build a blue or yellow gizmo: file')
	),
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
		'tooltip' => clienttranslate('When you build a black or red gizmo: file')
	),
	327 => array(
		'id' => 327,
		'color' => 'blue',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromfile',
		'tooltip' => clienttranslate('You may spend 1 less Energy when building Gizmos from the Archive')
	),
	328 => array(
		'id' => 328,
		'color' => 'red',
		'level' => 3,
		'cost' => 5,
		'points' => 5,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromfile',
		'tooltip' => clienttranslate('You may spend 1 less Energy when building Gizmos from the Archive')
	),
	329 => array(
		'id' => 329,
		'color' => 'black',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromresearch',
		'tooltip' => clienttranslate('You may spend 1 less Energy when building Gizmos directly from a Research Action')
	),
	330 => array(
		'id' => 330,
		'color' => 'yellow',
		'level' => 3,
		'cost' => 6,
		'points' => 6,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'discount_buildfromresearch',
		'tooltip' => clienttranslate('You may spend 1 less Energy when building Gizmos directly from a Research Action')
	),
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
		'tooltip' => clienttranslate('When you build a yellow or red gizmo: research')
	),
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
		'tooltip' => clienttranslate('When you build a blue or black gizmo: research')
	),
	333 => array(
		'id' => 333,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_energy',
		'tooltip' => clienttranslate('At the end of the game score points equal your remaining Energy Spheres')
	),
	334 => array(
		'id' => 334,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_energy',
		'tooltip' => clienttranslate('At the end of the game score points equal your remaining Energy Spheres')
	),
	335 => array(
		'id' => 335,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_scores',
		'tooltip' => clienttranslate('At the end of the game score points equal to your victory point count')
	),
	336 => array(
		'id' => 336,
		'color' => 'multi',
		'level' => 3,
		'cost' => 7,
		'points' => 0,
		'effect_type' => 'upgrade',
		'upgrade_special' => 'score_scores',
		'tooltip' => clienttranslate('At the end of the game score points equal to your victory point count')
	),
	901 => array(
		'id' => 901,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => clienttranslate('When you file a gizmo: draw')
	),
	902 => array(
		'id' => 902,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => clienttranslate('When you file a gizmo: draw')
	),
	903 => array(
		'id' => 903,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => clienttranslate('When you file a gizmo: draw')
	),
	904 => array(
		'id' => 904,
		'color' => 'none',
		'level' => 0,
		'cost' => 0,
		'points' => 0,
		'effect_type' => 'trigger_file',
		'trigger_action' => 'draw',
		'tooltip' => clienttranslate('When you file a gizmo: draw')
	)
);