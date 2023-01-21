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
 * states.inc.php
 *
 * gizmos game states description
 *
 */
 
$machinestates = array(

    // The initial state. Please do not modify.
    1 => array(
        "name" => "gameSetup",
        "description" => "",
        "type" => "manager",
        "action" => "st_GameSetup",
        "transitions" => array( "" => 2 )
    ),
    
    // Note: ID=2 => your first state
    2 => array(
    		"name" => "playerTurn",
    		"description" => clienttranslate('${actplayer} may File, Pick, Build, or Research'),
    		"descriptionmyturn" => clienttranslate('${you} may File, Pick, Build, or Research'),
    		"type" => "activeplayer",
			"args" => "arg_playerTurn",
    		"possibleactions" => array( "cardSelected", "deckSelected", "sphereSelect", "zombiePass" ),
    		"transitions" => array( "cardSelected" => 10, "deckSelected" => 14, "sphereSelect" => 30, "zombiePass" => 3 )
    ),
    3 => array(
    		"name" => "nextPlayer",
    		"type" => "game",
			"action" => "st_nextPlayer",
			"updateGameProgression" => true,
    		"transitions" => array( "nextTurn" => 2, "endGame" => 99 )
    ),
	
	// begin gizmos-specific actions
    10 => array(
    		"name" => "cardSelected",
    		"description" => clienttranslate('${actplayer} may Build or File selected Gizmo'),
    		"descriptionmyturn" => clienttranslate('${you} may Build or File selected Gizmo'),
    		"type" => "activeplayer",
			"args" => "arg_getSelectedCard",
    		"possibleactions" => array( "research", "cardFile", "cardBuilt", "cancel", "cancelTrigger", "zombiePass" ),
    		"transitions" => array( "research" => 12, "cardFile" => 30, "cardBuilt" => 30, "cancel" => 2, "cancelTrigger" => 13, "zombiePass" => 3 )
    ),
    11 => array(
    		"name" => "triggerSphereSelect",
    		"description" => clienttranslate('${actplayer} ${desc}'),
    		"descriptionmyturn" => clienttranslate('${you} ${desc}'),
    		"type" => "activeplayer",
			"args" => "arg_triggerSphereSelect",
    		"possibleactions" => array( "sphereSelect", "cancel", "triggerCheck", "zombiePass" ),
    		"transitions" => array( "sphereSelect" => 30, "cancel" => 13, "triggerCheck" => 30, "zombiePass" => 3  )
    ),
    12 => array(
    		"name" => "research",
    		"description" => clienttranslate('${actplayer} may Build or File a Researched Gizmo'),
    		"descriptionmyturn" => clienttranslate('${you} may Build or File a Researched Gizmo'),
			"args" => "arg_getResearchedCards",
    		"type" => "activeplayer",
    		"possibleactions" => array( "cardSelected", "pass", "zombiePass" ),
    		"transitions" => array( "cardSelected" => 15, "pass" => 30, "zombiePass" => 3 )
    ),
    13 => array(
    		"name" => "triggerSelect",
    		"description" => clienttranslate('${actplayer} may select a Gizmo to trigger'),
    		"descriptionmyturn" => clienttranslate('${you} may select a Gizmo to trigger'),
			"args" => "arg_getTriggeredCards",
    		"type" => "activeplayer",
    		"possibleactions" => array( "triggerSphereSelect", "triggerResearch", "triggerSphereRandom", "pass", "gainVictoryPoint", "buildLevel1For0", "triggerFile", "zombiePass" ),
    		"transitions" => array( "triggerSphereSelect" => 11, "triggerResearch" => 17, "triggerSphereRandom" => 23, "pass" => 3, "gainVictoryPoint" => 27, "buildLevel1For0" => 18, "triggerFile" => 19, "zombiePass" => 3 )
    ),
    14 => array(
    		"name" => "deckSelected",
    		"description" => clienttranslate('${actplayer} may research'),
    		"descriptionmyturn" => clienttranslate('${you} may research'),
			"args" => "arg_getSelectedCard",
    		"type" => "activeplayer",
    		"possibleactions" => array( "research", "cancel", "cancelTrigger", "zombiePass" ),
    		"transitions" => array( "research" => 12, "cancel" => 2, "cancelTrigger" => 13, "zombiePass" => 3 )
    ),
	15 => array(
    		"name" => "researchedCardSelected",
    		"description" => clienttranslate('${actplayer} may Build or File selected Gizmo'),
    		"descriptionmyturn" => clienttranslate('${you} may Build or File selected Gizmo'),
    		"type" => "activeplayer",
			"args" => "arg_getSelectedAndResearchedCard",
    		"possibleactions" => array( "cardFile", "cardBuilt", "cancel", "zombiePass"),
    		"transitions" => array( "cardFile" => 30, "cardBuilt" => 30, "cancel" => 12, "zombiePass" => 3 )
    ),
	16 => array(
    		"name" => "triggerDraw",
    		"description" => '${actplayer} ${desc}',
    		"descriptionmyturn" => '${you} ${desc}',
    		"type" => "activeplayer",
			"args" => "arg_triggerDraw",
    		"possibleactions" => array( "triggerSphereRandom", "cancel", "triggerCheck", "zombiePass"),
    		"transitions" => array( "triggerSphereRandom" => 23, "cancel" => 13, "triggerCheck" => 30, "zombiePass" => 3 )
    ),
	17 => array(
    		"name" => "triggerResearch",
    		"description" => clienttranslate('${actplayer} may select a deck to Research'),
    		"descriptionmyturn" => clienttranslate('${you} may select a deck to Research'),
    		"type" => "activeplayer",
			"args" => "arg_triggeringGizmo",
    		"possibleactions" => array( "deckSelected", "cancel", "zombiePass" ),
    		"transitions" => array( "deckSelected" => 14, "cancel" => 13, "zombiePass" => 3 )
    ),
	18 => array(
		"name" => "buildLevel1For0",
		"description" => clienttranslate('${actplayer} may build a Level I Gizmo for free'),
		"descriptionmyturn" => clienttranslate('${you} may build a Level I Gizmo for free'),
		"type" => "activeplayer",
		"args" => "arg_triggeringGizmo",
		"possibleactions" => array( "buildLevel1For0", "cancel", "zombiePass" ),
		"transitions" => array( "buildLevel1For0" => 30, "cancel" => 13, "zombiePass" => 3 )
	),
	19 => array(
		"name" => "triggerFile",
		"description" => clienttranslate('${actplayer} may File a Gizmo'),
		"descriptionmyturn" => clienttranslate('${you} may File a Gizmo'),
		"type" => "activeplayer",
		"args" => "arg_triggeringGizmo",
		"possibleactions" => array( "cardFile", "cancel", "zombiePass" ),
		"transitions" => array( "cardFile" => 30, "cancel" => 13, "zombiePass" => 3 )
	),
	
    23 => array(
    		"name" => "triggerSphereRandom",
			"description" => "",
    		"type" => "game",
			"action" => "st_triggerSphereRandom",
    		"transitions" => array( "triggerCheck" => 30 )
    ),
	27 => array(
    		"name" => "gainVictoryPoint",
			"description" => "",
    		"type" => "game",
			"action" => "st_gainVictoryPoint",
    		"transitions" => array( "triggerCheck" => 30 )
    ),
    30 => array(
    		"name" => "triggerCheck",
			"description" => "",
    		"type" => "game",
			"action" => "st_triggerCheck",
    		"transitions" => array( "triggerSelect" => 13, "nextTurn" => 3, "triggerSphereSelect" => 11, "triggerDraw" => 16 )
    ),
   
    // Final state.
    // Please do not modify (and do not overload action/args methods).
    99 => array(
        "name" => "gameEnd",
        "description" => clienttranslate("End of game"),
        "type" => "manager",
        "action" => "stGameEnd",
        "args" => "argGameEnd"
    )

);



