{OVERALL_GAME_HEADER}

<!-- 
--------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- gizmos implementation : © Fletcheese <1337ch33z@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-------
-->

<script type="text/javascript">

// Javascript HTML templates
var jstpl_player_board = '\<div class="cp_board" id="token_counts_${id}">\
    <div class="counter_pair count_${red_count}" id="pair_${id}_red"><div id="token_${id}_red" class="counter_token red_token"> <div class="counter" id="tokencount_${id}_red">${red_count}</div></div></div>\
    <div class="counter_pair count_${black_count}" id="pair_${id}_black"><div id="token_${id}_black" class="counter_token black_token"><div class="counter" id="tokencount_${id}_black">${black_count}</div></div></div>\
    <div class="counter_pair count_${blue_count}" id="pair_${id}_blue"><div id="token_${id}_blue" class="counter_token blue_token"><div class="counter" id="tokencount_${id}_blue">${blue_count}</div></div></div>\
    <div class="counter_pair count_${yellow_count}" id="pair_${id}_yellow"><div id="token_${id}_yellow" class="counter_token yellow_token"><div class="counter" id="tokencount_${id}_yellow">${yellow_count}</div></div></div>\
    <div class="counter_pair count_${all_count}" id="pair_${id}_all"><div id="token_${id}_all" class="counter_all"><div class="counter ${energy_full}" id="tokencount_${id}_all">${all_count}/${energy_limit}</div></div></div>\
    <div class="counter_pair2 count_${vp_count}" id="pair_${id}_vps"><div id="vp_${id}" class="vp"><div class="counter" id="tokencount_${id}_vp">${vp_count}</div></div></div>\
    <div class="counter_pair2 count_${gizmos_all}" id="pair_${id}_allgs"><div id="gizmos_all_${id}" class="gizmos_all"><div class="counter" id="tokencount_${id}_gizmos_all">${gizmos_all}</div></div></div>\
    <div class="counter_pair2 count_${gizmos_3s}" id="pair_${id}_3gs"><div id="gizmos_3s_${id}" class="gizmos_3s"><div class="counter" id="tokencount_${id}_gizmos_3s">${gizmos_3s}</div></div></div>\
    <div class="counter_pair2 count_${archive}" id="pair_${id}_archive"><div id="gizmos_archive_${id}" class="gizmos_archive"><div class="counter ${archive_full}" id="tokencount_${id}_gizmos_archive">${archive}/${archive_limit}</div></div></div>\
    <div class="counter_pair2 count_${research}" id="pair_${id}_research"><div id="gizmos_research_${id}" class="gizmos_research"><div class="counter" id="tokencount_${id}_gizmos_research">${research}</div></div></div>\
</div>';

//var jstpl_sphere = '<div id="sphere_${id}" class="token ${color}_token ${other_classes}"></div>';
//var jstpl_sphere = '<div id="sphere_${id}" class="token ${color}_token" style="left: ${left}px;"></div>';

var jstpl_deck = '<div id="deck_${level}" class="deck"></div>';
var jstpl_card = '<div id="card_${id}" class="card card_${level} ${other_class}"></div>';
var jstpl_fd_card = '<div class="card card_${level} gzs_fd_card_${level} ${other_class}"></div>';

var jstpl_gizmos_container = '\<div id="gizmos_container_${id}" class="gizmos_container whiteblock">\
	<div class="player_header" id="player_header_${id}">\
		<h3 style="float:left;color:#${color}" class="player_name ${class}">${name}</h3>\
		<h3 style="float:right;color:#${color};padding:5px;background-color:white;border-radius:5%" class="">Archive</h3>\
	</div>\
	<div id="gizmo_track_${id}" class="gizmo_track${first}"></div>\
	<div id="gizmos_columns_${id}" class="gizmos_columns"></div>\
</div>';

var jstpl_gizmos_column = '<div id="${col}_${id}" class="${col} gizmos_column"></div>';

var jstpl_research_dialog = '<div id="research_dialog"><div id="research_row" class="row whiteblock"></div></div>';

var jstpl_cardTooltip = '\<div id="card_tooltip_${id}">\
	<div class="tooltip_desc"><span class="tooltip_desc">🛈 ${tooltip}</span></div>\
	<div id="card_${id}" class="card tooltipcard"></div>\
</div>';

var jstpl_deckTooltip = '<div id="deck_tooltip_${level}" style="text-align:center">🛈 Level ${level} deck has <span id="deck_count_${level}">${count}</span> cards remaining<br/>Click to Research</div>';

</script>

<div id="gizmos_board">
	<div id="energy_ring"> </div>
	<div id="end_banner" class="end_banner" style="display:none">This is the last round!</div>
	<div id="researched_gizmos" class="row whiteblock" style="display:none"> </div>
	<div id="current_player_gizmos"> </div>
	<div id="sphere_row_outer" class="row">
		<div id="dispenser"></div>
		<div id="sphere_row">
		</div>
	</div>
	<div id="row_3" class="row whiteblock">
	</div>
	<div id="row_2" class="row whiteblock">	
	</div>
	<div id="row_1" class="row whiteblock">	
	</div>
	<div id="player_gizmos">
	</div>	
</div>

{OVERALL_GAME_FOOTER}
