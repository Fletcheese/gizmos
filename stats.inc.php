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
    )

);
