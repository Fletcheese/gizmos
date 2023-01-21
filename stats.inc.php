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
 * stats.inc.php
 *
 * gizmos game statistics description
 *
 */

$stats_type = array(

    // Statistics global to table
    "table" => array(

        "turns_number" => array("id"=> 10,
            "name" => totranslate("Number of turns"),
            "type" => "int" ),

        "built_number" => array("id"=> 20,
            "name" => totranslate("Number of built gizmos"),
            "type" => "int" ),

        "filed_number" => array("id"=> 21,
            "name" => totranslate("Number of filed gizmos"),
            "type" => "int" ),

        "research_number" => array("id"=> 22,
            "name" => totranslate("Number of research actions"),
            "type" => "int" ),

        "trigger_number" => array("id"=> 23,
            "name" => totranslate("Number of used gizmo triggers"),
            "type" => "int" ),

        "picked_number" => array("id"=> 30,
            "name" => totranslate("Number of picked energy"),
            "type" => "int" ),

        "drawn_number" => array("id"=> 31,
            "name" => totranslate("Number of drawn energy"),
            "type" => "int" ),

        "conversion_number" => array("id"=> 32,
            "name" => totranslate("Number of energy conversions"),
            "type" => "int" ),

        "level1_score" => array("id"=> 41,
            "name" => totranslate("Score from Level I Gizmos"),
            "type" => "int" ),

        "level2_score" => array("id"=> 42,
            "name" => totranslate("Score from Level II Gizmos"),
            "type" => "int" ),

        "level3_score" => array("id"=> 43,
            "name" => totranslate("Score from Level III Gizmos"),
            "type" => "int" ),

        "vps_score" => array("id"=> 44,
            "name" => totranslate("Score from Victory Point Tokens"),
            "type" => "int" ),

        "level1_built" => array("id"=> 51,
            "name" => totranslate("Number of built Level I Gizmos"),
            "type" => "int" ),

        "level2_built" => array("id"=> 52,
            "name" => totranslate("Number of built Level II Gizmos"),
            "type" => "int" ),

        "level3_built" => array("id"=> 53,
            "name" => totranslate("Number of built Level III Gizmos"),
            "type" => "int" ),

        "level1_research" => array("id"=> 54,
            "name" => totranslate("Number of Level I researches"),
            "type" => "int" ),

        "level2_research" => array("id"=> 55,
            "name" => totranslate("Number of Level II researches"),
            "type" => "int" ),

        "level3_research" => array("id"=> 56,
            "name" => totranslate("Number of Level III researches"),
            "type" => "int" ),
    ),
    
    // Statistics existing for each player
    "player" => array(

        "turns_number" => array("id"=> 10,
            "name" => totranslate("Number of turns"),
            "type" => "int" ),

        "built_number" => array("id"=> 20,
            "name" => totranslate("Number of built gizmos"),
            "type" => "int" ),

        "filed_number" => array("id"=> 21,
            "name" => totranslate("Number of filed gizmos"),
            "type" => "int" ),

        "research_number" => array("id"=> 22,
            "name" => totranslate("Number of research actions"),
            "type" => "int" ),

        "trigger_number" => array("id"=> 23,
            "name" => totranslate("Number of used gizmo triggers"),
            "type" => "int" ),

        "picked_number" => array("id"=> 30,
            "name" => totranslate("Number of picked energy"),
            "type" => "int" ),

        "drawn_number" => array("id"=> 31,
            "name" => totranslate("Number of drawn energy"),
            "type" => "int" ),

        "conversion_number" => array("id"=> 32,
            "name" => totranslate("Number of energy conversions"),
            "type" => "int" ),

        "level1_score" => array("id"=> 41,
            "name" => totranslate("Score from Level I Gizmos"),
            "type" => "int" ),

        "level2_score" => array("id"=> 42,
            "name" => totranslate("Score from Level II Gizmos"),
            "type" => "int" ),

        "level3_score" => array("id"=> 43,
            "name" => totranslate("Score from Level III Gizmos"),
            "type" => "int" ),

        "vps_score" => array("id"=> 44,
            "name" => totranslate("Score from Victory Point Tokens"),
            "type" => "int" ),

        "level1_built" => array("id"=> 51,
            "name" => totranslate("Number of built Level I Gizmos"),
            "type" => "int" ),

        "level2_built" => array("id"=> 52,
            "name" => totranslate("Number of built Level II Gizmos"),
            "type" => "int" ),

        "level3_built" => array("id"=> 53,
            "name" => totranslate("Number of built Level III Gizmos"),
            "type" => "int" ),

        "level1_research" => array("id"=> 54,
            "name" => totranslate("Number of Level I researches"),
            "type" => "int" ),

        "level2_research" => array("id"=> 55,
            "name" => totranslate("Number of Level II researches"),
            "type" => "int" ),

        "level3_research" => array("id"=> 56,
            "name" => totranslate("Number of Level III researches"),
            "type" => "int" ),
    )

);
