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
  * gizmos.game.php
  *
  * This is the main file for your game logic.
  *
  * In this PHP file, you are going to defines the rules of the game.
  *
  */


require_once( APP_GAMEMODULE_PATH.'module/table/table.game.php' );
require_once('modules/gizmos_converter.php');
require_once('modules/gizmos_db.php');

class Gizmos extends Table
{
	function __construct( )
	{
        parent::__construct();
        
        self::initGameStateLabels( array(
			"selected_card_id" => 50,
			"triggering_gizmo_id" => 51,
			"triggering_multiple_uses" => 52,			
			"research_level" => 55,
			"is_last_round" => 81,
        ) );     
		$this->gizmo_cards = self::getNew( "module.common.deck" );
        $this->gizmo_cards->init( "gizmo_cards" );   
		Converter::init( $this );
		DB::init( $this );
	}
	
    protected function getGameName( )
    {
		// Used for translations and stuff. Please do not modify.
        return "gizmos";
    }	

    /*
        setupNewGame:
        
        This method is called only once, when a new game is launched.
        In this method, you must setup the game according to the game rules, so that
        the game is ready to be played.
    */
    protected function setupNewGame( $players, $options = array() )
    {    
        // Set the colors of the players with HTML color code
        // The default below is red/green/blue/orange/brown
        // The number of colors defined here must correspond to the maximum number of players allowed for the gams
        $gameinfos = self::getGameinfos();
        $default_colors = $gameinfos['player_colors'];
 
        // Create players
        // Note: if you added some extra field on "player" table in the database (dbmodel.sql), you can initialize it there.
		
		
        $sql = "INSERT INTO player (player_id, player_color, player_canal, player_name, player_avatar) VALUES ";
        $values = array();
        foreach( $players as $player_id => $player )
        {
            $color = array_shift( $default_colors );
            $values[] = "('".$player_id."','$color','".$player['player_canal']."','".addslashes( $player['player_name'] )."','".addslashes( $player['player_avatar'] )."')";
        }
        $sql .= implode( $values, ',' );
        self::DbQuery( $sql );
        self::reattributeColorsBasedOnPreferences( $players, $gameinfos['player_colors'] );
        self::reloadPlayersBasicInfos();
        
        /************ Start the game initialization *****/
		
		// Create the spheres / energies / tokens
		// Pick 6 spheres at random for starting row:
		$start_spheres = array();
		for ($i = 0; $i < 6; $i++) {
			$r = rand(1,52);
			while (in_array($r, $start_spheres)) {
				$r = rand(1,52);
			}
			$start_spheres[$i] = $r;
		}
		
        $sql = "INSERT INTO sphere (location) VALUES ";
        $sql_values = array();
        for( $x=1; $x<=52; $x++ )
        {
			$location;
			if (in_array($x, $start_spheres)) {
				$location = 'row';
			} else {
                $location = 'dispenser';
            }
			$sql_values[] = "('$location')";
        }
        $sql .= implode( $sql_values, ',' );
        self::DbQuery( $sql );		
		
		$create_gizmos = array(
			0 => array(),
			1 => array(),
			2 => array(),
			3 => array()
		);

		// Randomly select 16 level 3 IDs to include
		$start_3s = array();
		for ($i = 0; $i < 16; $i++) {
			$r = rand(301,336);
			while (in_array($r, $start_3s)) {
				$r = rand(301,336);
			}
			$start_3s[$i] = $r;
		}

		$mt_gizmos = $this->mt_gizmos;
		foreach ( $mt_gizmos as $gizmo_id => $gizmo ) {
			$eff_type = $gizmo['effect_type'];
			if ( strpos( $eff_type, 'trigger_build' ) !== false ) {
				$eff_type = 'trigger_build';
			}

			if ($gizmo_id < 300 || $gizmo_id > 400 || in_array($gizmo_id, $start_3s)) {
				$card = array(
					'id' => $gizmo_id,
					'type' => $eff_type, 
					'type_arg' => $gizmo_id, 
					'location' => "deck_".$gizmo['level'],
					'nbr' => 1
				);
				array_push( $create_gizmos[$gizmo['level']], $card );
			}
		}
		$this->gizmo_cards->createCards( $create_gizmos[0] );
		$this->gizmo_cards->createCards( $create_gizmos[1], 'deck_1' );
		$this->gizmo_cards->shuffle( 'deck_1' );
		$this->gizmo_cards->createCards( $create_gizmos[2], 'deck_2' );
		$this->gizmo_cards->shuffle( 'deck_2' );
		
		$this->gizmo_cards->createCards( $create_gizmos[3], 'deck_3' );
		$this->gizmo_cards->shuffle( 'deck_3' );
		
		// Place cards from decks in rows
		$this->gizmo_cards->pickCardsForLocation( 4, 'deck_1', 'row_1' );
		$this->gizmo_cards->pickCardsForLocation( 3, 'deck_2', 'row_2' );
		$this->gizmo_cards->pickCardsForLocation( 2, 'deck_3', 'row_3' );	

		// Deal starting level 0 cards, one per player:
		foreach ( $players as $player_id => $player ) {
			$this->gizmo_cards->pickCardForLocation('deck','built',$player_id);			
		}
		self::setGameStateValue('research_level', 0);

        // Init global values with their initial values
        //self::setGameStateInitialValue( 'my_first_global_variable', 0 );
        
        // Init game statistics
        // (note: statistics used in this file must be defined in your stats.inc.php file)
        //self::initStat( 'table', 'table_teststat1', 0 );    // Init a table statistics
        //self::initStat( 'player', 'player_teststat1', 0 );  // Init a player statistics (for all players)

        // Activate first player (which is in general a good idea :) )
        $this->activeNextPlayer();

        /************ End of the game initialization *****/
    }

    /*
        getAllDatas: 
        
        Gather all informations about current game situation (visible by the current player).
        
        The method is called each time the game interface is displayed to a player, ie:
        _ when the game starts
        _ when a player refreshes the game page (F5)
    */
    protected function getAllDatas()
    {
        $result = array();
    
		// All information for gizmos is public :)
        $current_player_id = self::getCurrentPlayerId();
    
        // Get information about players
        // Note: you can retrieve some extra field you added for "player" table in "dbmodel.sql" if you need it.
        $result['players'] = DB::getPlayers();
		$current_player = $result['players'][$current_player_id];
		$result['energy_limit'] = $current_player['energy_limit'];
		$result['archive_limit'] = $current_player['archive_limit'];
		$result['research_quantity'] = $current_player['research_quantity'];		
		
		$result['selected_card_id'] = self::getGameStateValue('selected_card_id');
		$result['is_last_round'] = self::getGameStateValue('is_last_round');
        $result['spheres'] = DB::getDispenserSpheres();
		
		$gizmo_cards = array(
			1 => $this->gizmo_cards->getCardsInLocation( 'row_1' ),
			2 => $this->gizmo_cards->getCardsInLocation( 'row_2' ),
			3 => $this->gizmo_cards->getCardsInLocation( 'row_3' )	
		);
		// $deck_counts = array(
		// 	1 => count( $this->gizmo_cards->getCardsInLocation( 'deck_1' ) ),
		// 	2 => count( $this->gizmo_cards->getCardsInLocation( 'deck_2' ) ),
		// 	3 => count( $this->gizmo_cards->getCardsInLocation( 'deck_3' ) )
		// );
		
		$built_filed_gizmos = DB::getBuiltOrFiledCards();
		//var_dump( $built_filed_gizmos );
		//foreach ($result['players'] as $player_id => $player) {
		foreach ($built_filed_gizmos as $gizmo_id => $gizmo) {			
			$player_id = $gizmo['card_location_arg'];
			if (!array_key_exists($player_id, $gizmo_cards)) {
				$gizmo_cards[$player_id] = array(
					'filed' => array(),
					'built' => array(),
					'built_by_type' => array()
				);
			}
			
			if ($gizmo['card_location'] == 'filed') {
				array_push($gizmo_cards[$player_id]['filed'], $gizmo);				
			} else {
				$gtype = $gizmo['card_type'];
				if (!array_key_exists($gtype, $gizmo_cards[$player_id]['built_by_type'])) {
					$gizmo_cards[$player_id]['built_by_type'][$gtype] = array();
				}
				array_push($gizmo_cards[$player_id]['built_by_type'][$gtype], $gizmo);
				array_push($gizmo_cards[$player_id]['built'], $gizmo);
			}			
		}
		$result['mt_gizmos'] = $this->mt_gizmos;
		$result['mt_colors'] = $this->mt_colors;
		$result['gizmo_cards'] = $gizmo_cards;
		$result['deck_counts'] = DB::getDeckCounts();
  
        return $result;
    }

    function getGameProgression()
    {		
		return DB::getGameProgress()['progress'];
    }


//////////////////////////////////////////////////////////////////////////////
//////////// Utility functions
////////////    

    /*
        In this space, you can put any utility methods useful for your game logic
    */
	public static function getCollection( $sql ) { return self::getCollectionFromDb( $sql ); }
	public static function getObject( $sql ) { return self::getObjectFromDB( $sql ); }
	public static function getUniqueValue( $sql ) { return self::getUniqueValueFromDB( $sql ); }
	public static function getDoubleKeyCollection( $sql ) { return self::getDoubleKeyCollectionFromDB( $sql );}
	public function getSphereColor( $sid ) {
		$color_index = $sid % 4;
		return $this->mt_colors[$color_index];
	}
	function setSelectedCardId($scid) {
		self::setGameStateValue('selected_card_id', $scid);		
	}
	function setTriggeringGizmo($tgid) { 
		self::setGameStateValue('triggering_gizmo_id', $tgid);
	}
	public function getPlayerNameForNotification($player_id) {
		$name = self::getPlayerNameById($player_id);
		$color = self::getPlayerColorById($player_id);
		return "<span style='color:#$color'>$name</span>";
	}
	function checkFileTriggers() {
		$player_id = self::getActivePlayerId();
		self::DbQuery( "UPDATE gizmo_cards SET is_triggered=1 WHERE card_location='built' AND card_location_arg='$player_id' AND card_type='trigger_file'" );
	}
	function checkPickTriggers($sphere_id) {
		$player_id = self::getActivePlayerId();
		$select_sql = "SELECT card_type_arg,card_type FROM gizmo_cards WHERE is_triggered=0 AND card_location='built' AND card_location_arg='$player_id' AND card_type='trigger_pick'";	
        $potential_triggers = self::getCollectionFromDb( $select_sql );
		
		$sphere_color = self::getSphereColor($sphere_id);
		$triggered_card_ids = array();
		foreach ( $potential_triggers as $pt_gizmo_id => $pt_gizmo) {
			// Need to check the color of picked sphere to see if it matches triggers
			$is_match = self::isColorMatchesTrigger( $sphere_color, $this->mt_gizmos[$pt_gizmo_id] );
			if ( $is_match ) {
				array_push( $triggered_card_ids, $pt_gizmo_id );
			}
		}
		if ( count($triggered_card_ids) > 0 ) {
			self::DbQuery( "UPDATE gizmo_cards SET is_triggered=1 WHERE card_type_arg in (".implode(',',$triggered_card_ids).")" );
		}
	}
	function checkBuildTriggers($built_card_id, $built_from_file) {
		$player_id = self::getActivePlayerId();
		
		$select_sql = "SELECT card_type_arg,card_type FROM gizmo_cards WHERE is_triggered=0 AND card_location='built' AND card_location_arg='$player_id' AND card_type='trigger_build'";	
        $potential_triggers = self::getCollectionFromDb( $select_sql );	
		
		$triggered_card_ids = array();
		$built_mt_gizmo = $this->mt_gizmos[$built_card_id];
		$built_color = $built_mt_gizmo['color'];
		foreach ($potential_triggers as $pt_gizmo_id => $pt_gizmo) {
			$pt_mt_gizmo = $this->mt_gizmos[$pt_gizmo_id];
			$pt_effect = $pt_mt_gizmo['effect_type'];
			if ( $pt_effect == 'trigger_buildfromfile' || $pt_effect == 'trigger_build_from_file' ) {
				if ($built_from_file) {
					array_push( $triggered_card_ids, $pt_gizmo_id );					
				}
			} else if( $pt_effect == 'trigger_buildlevel2' || $pt_effect == 'trigger_build_level_2') {
				if ($built_mt_gizmo['level'] == 2) {
					array_push( $triggered_card_ids, $pt_gizmo_id );	
				}
			} else if( $pt_effect == 'trigger_build') {
				// Need to check the color of built card to see if it matches triggers
				$is_match = self::isColorMatchesTrigger( $built_color, $this->mt_gizmos[$pt_gizmo_id] );
				if ( $is_match ) {
					array_push( $triggered_card_ids, $pt_gizmo_id );
				}
			} else {
				throw new BgaVisibleSystemException( "Gizmo[$pt_gizmo_id] in checkBuildTriggers has unexpected trigger_build effect: $pt_effect" );
			}
		}
		if ( count($triggered_card_ids) > 0 ) {
			$s_card_ids = implode(',',$triggered_card_ids);
			self::DbQuery( "UPDATE gizmo_cards SET is_triggered=1 WHERE card_type_arg in ($s_card_ids)" );
		}
	}
	function isColorMatchesTrigger($color, $trigger_mt_gizmo) {
		$debug = "isColorMatchesTrigger( $color, ".$trigger_mt_gizmo['id']." ):\n";
		$ret;
		if ($color == 'multi') {
			//multi triggers all
			$debug .= "\t color[$color] == 'multi' => return true";
			$ret = true;
		} else if (in_array( $color, $trigger_mt_gizmo['trigger_color'] )) {
			$debug .= "\t color[$color] is in ".implode( $trigger_mt_gizmo['trigger_color'], ',')." => return true";
			$ret = true;
		} else {
			$debug .= "\t else return false";
			$ret = false;
		}
		//var_dump( $debug );
		return $ret;
	}
	
	public function getStateName() {
       $state = $this->gamestate->state();
       return $state['name'];
   }
	public function validateJSonAlphaNum($value, $argName = 'unknown')
	{
		if (is_array($value)) {
			foreach ($value as $key => $v) {
				$this->validateJSonAlphaNum($key, $argName);
				$this->validateJSonAlphaNum($v, $argName);
			}
			return true;
		}
		if (is_int($value)) {
			return true;
		}
		$bValid = preg_match("/^[_0-9a-zA-Z- ]*$/", $value) === 1;
		if (!$bValid) {
			throw new BgaSystemException("Bad value for: $argName", true, true, FEX_bad_input_argument);
		}
		return true;
	}
	public static function stringContains($needle, $haystack) {
		return strpos( $haystack, $needle) !== false;
	}
	function handleResearchReturn() {
		if (self::getGameStateValue('research_level') > 0) {
			self::returnResearchToDeck();
			self::setGameStateValue('research_level', 0);
		}
	}
	function returnResearchToDeck() {
		$level = self::getGameStateValue('research_level');
		$r_cards = $this->gizmo_cards->moveAllCardsInLocationKeepOrder( 'research', "deck_$level" );		
	}
	private function handleResearchOrder($research) {
		if ($research) {
			$gizmo_ids = explode(',', $research);
			$level = self::getGameStateValue('research_level');
			$bottom_pos = $this->gizmo_cards->getExtremePosition( false, "deck_$level" );
			foreach ($gizmo_ids as $i => $gid) {
				$bottom_pos--;
				self::DbQuery( "UPDATE gizmo_cards SET card_location_arg=$bottom_pos WHERE card_type_arg=$gid" );
			}
		}
	}

//////////////////////////////////////////////////////////////////////////////
//////////// Player actions
//////////// 

    /*
        Each time a player is doing some game action, one of the methods below is called.
        (note: each method below must match an input method in gizmos.action.php)
    */
	
	function cardSelected( $selected_card_id, $research )
    {
		// Deck IDs are 1,2,3
		$this->handleResearchOrder($research);
		$level = self::getGameStateValue('research_level');
		if ($level > 0) {
			self::checkAction( 'cardSelected' );
			// Confirm card is in 
			$gizmo = DB::getSingleGizmoById($selected_card_id);
			$location = $gizmo['card_location'];
			if (strpos( $location, 'research') !== false) {			
				self::setSelectedCardId($selected_card_id);			
				$this->gamestate->nextState( 'cardSelected' );
			} else {
				throw new BgaVisibleSystemException( "Card ".$selected_card_id." was not researched and thus cannot be selected" );				
			}			
		} else if ($selected_card_id > 0 && $selected_card_id < 4) {
			if (DB::checkResearch(self::getActivePlayerId())) {
				throw new BgaUserException( self::_("Cannot research due to upgrade!"));
			}
			self::checkAction( 'deckSelected' );
			self::setSelectedCardId($selected_card_id);		
			$this->gamestate->nextState( 'deckSelected' );		
		} else {
			self::checkAction( 'cardSelected' );
			// also ensure that this card is in a row OR filed to prevent gaining cards from the deck or other players
			$gizmo = DB::getSingleGizmoById($selected_card_id);
			$location = $gizmo['card_location'];
			if (strpos( $location, 'row_') !== false
				|| ($location == 'filed' && $gizmo['card_location_arg'] == self::getActivePlayerId())) {
				self::setSelectedCardId($selected_card_id);
				$this->gamestate->nextState( 'cardSelected' );			
			} else {
				throw new BgaVisibleSystemException( "Card ".$selected_card_id." is not in the row nor player filed and thus cannot be selected" );
			}
		}		
    }
	function cancel($research) {
        self::checkAction( 'cancel' );
		$this->handleResearchOrder($research);
		// if a multiple action gizmo trigger is being cancelled after the first action, need to ensure it's set to used
		$uses = self::getGameStateValue('triggering_multiple_uses');
		$tg_gizmo_id = self::getGameStateValue('triggering_gizmo_id');
		$next_state;
		if ($uses > 0) {
			DB::setGizmoUsed($tg_gizmo_id);
			$next_state = 'triggerCheck';
		} else if ($tg_gizmo_id > 0 && self::getStateName() == 'deckSelected') {
			$next_state = 'cancelTrigger';
		} else {
			$next_state = 'cancel';			
		}
		self::setGameStateValue('triggering_multiple_uses', 0);
		self::setSelectedCardId(0);
		
		// Do not clear triggering gizmo if player is researching
		$state = self::getStateName();
		if ($state != 'researchedCardSelected') {
			self::setTriggeringGizmo(0);
		}
		$this->gamestate->nextState( $next_state );   
	}
	function sphereSelect($sphere_id) {
        self::checkAction( 'sphereSelect' );
		$player_id = self::getActivePlayerId();
		if (!DB::checkPlayerEnergyCapacity($player_id)) {			
			throw new BgaUserException( self::_("You cannot hold more energy"));	
		}
		
		// ensure sphere is actually in the row to prevent cheaters:
		$sel_sql = "SELECT location FROM sphere WHERE sphere_id='$sphere_id'";
		$sp_location = self::getUniqueValueFromDB( $sel_sql );
		if ($sp_location != 'row') {
			throw new BgaVisibleSystemException( "Cannot pick energy sphere $sphere_id from $sp_location" );				
		}
		
		$sql = "UPDATE sphere SET location='$player_id'
                    WHERE sphere_id='$sphere_id'";
		self::DbQuery( $sql );		
		$sphere_sql = "SELECT sphere_id FROM sphere WHERE location='dispenser'";
        $spheres = self::getCollectionFromDb( $sphere_sql );
		
		$new_sphere = array_rand($spheres);
		$sql_new = "UPDATE sphere SET location='row' WHERE sphere_id='$new_sphere'";
		self::DbQuery( $sql_new );
		self::checkPickTriggers($sphere_id);
		$player_name = self::getPlayerNameForNotification($player_id);
		$sphere_color = self::getSphereColor($sphere_id);
		self::notifyAllPlayers('sphereSelect', clienttranslate('${player_name} Picks ${sphere_html}'), //a ${sphere_color} Energy"),
			array (
				'player_name' => $player_name,
				'sphere_html' => DB::getSphereHtml($sphere_color),
				'new_sphere_id' => $new_sphere,
				'purchased_sphere_id' => $sphere_id,
				'player_id' => $player_id
			)
		);
        $this->incStat(1, 'picked_number', $player_id);
        $this->incStat(1, 'picked_number');
		$this->gamestate->nextState( 'sphereSelect' ); 
	}
	function buildSelectedCard($sphere_ids, $converters, $research) {
		self::checkAction( 'cardBuilt' );
		$this->handleResearchOrder($research);
		$selected_card_id = self::getGameStateValue('selected_card_id');
		$player_id = self::getActivePlayerId();
		// confirm that all the selected sphere_ids actually belong to active player
		if ($sphere_ids) {
			$sel_sphere_sql = "SELECT sphere_id,location FROM sphere WHERE sphere_id in ($sphere_ids)";
			$spheres = self::getCollectionFromDb( $sel_sphere_sql );
			foreach ($spheres as $sphere_id => $sphere) {
				if ($sphere['location'] != $player_id) {
					throw new BgaVisibleSystemException( "Cannot build card $selected_card_id using spheres from ".$sphere['location'] );				
				}
			}
		}

		Converter::validateBuild($converters, explode(',', $sphere_ids), $selected_card_id, $player_id);
		
		DB::putSpheresInDispenser($sphere_ids);
		
		self::doBuildCard($selected_card_id, $player_id, $sphere_ids);

        $this->incStat( count($converters), 'conversion_number', $player_id);
        $this->incStat( count($converters), 'conversion_number');

		$this->gamestate->nextState( 'cardBuilt' ); 
	}

	function doBuildCard($selected_card_id, $player_id, $sphere_ids) {
		$built_card = DB::validateLegalBuildLocation($selected_card_id, $player_id);
		$built_from_file = ($built_card['location'] == 'filed');

		// Ensure built card does not trigger itself by checking triggers prior to moving
		self::checkBuildTriggers( $selected_card_id, $built_from_file );

		// move card to player
		$this->gizmo_cards->moveCard( $built_card['card_id'], 'built', $player_id );
		
		// add the top card of the deck to the row IF card was not from file NOR research
		$new_card_id = null;
		$level = $this->mt_gizmos[$selected_card_id]['level'];
		$built_from;
		if ( $built_from_file ) {
			$built_from = self::_('File');
		} else if ( self::getGameStateValue('research_level') > 0 ) {
			$built_from = self::_('Research');
		} else {
			$built_from = self::_('the row');
			$new_card = $this->gizmo_cards->pickCardForLocation( "deck_$level", "row_$level" );
			if (!empty($new_card)) {
				$new_card_id = $new_card['type_arg'];
			}
		}

		$built_mt_gizmo = $this->mt_gizmos[$selected_card_id];
		// Increment score and apply upgrades (if applicable)
		$new_score = DB::scoreAndUpgradeBuiltCard($player_id, $built_mt_gizmo );
		
		self::handleResearchReturn();
		
		// clear selected card
		self::setSelectedCardId(0);
		// notify everyone
		$player_name = self::getPlayerNameForNotification($player_id);
		$limits = DB::getPlayerLimits($player_id);
		self::notifyAllPlayers('cardBuiltOrFiled', clienttranslate('${player_name} ${action} a Level ${level} ${color} Gizmo from ${built_from}'), 
			array (
				'i18n' => ['color', 'built_from', 'action'],
				'player_name' => $player_name,
				'action' => 'Builds',
				'level' => DB::LevelAsNumerals($level),
				'color' => $built_mt_gizmo['color'],
				'built_from' => $built_from,
				'purchased_card_id' => $selected_card_id,
				'spent_spheres' => $sphere_ids,
				'new_card_id' => $new_card_id,
				'player_id' => $player_id,
				'built_from_file' => $built_from_file,
				'new_score' => $new_score,
				'limits' => [
					'archive' => $limits['archive_limit'],
					'energy' => $limits['energy_limit'],
					'research' => $limits['research_quantity']
				],
				'deck_counts' => DB::getDeckCounts()
			)
		);
        $this->incStat(1, 'built_number', $player_id);
        $this->incStat(1, 'built_number');
	}
	
	function fileSelectedCard($selected_card_id, $research) {
		self::checkAction( 'cardFile' );
		$this->handleResearchOrder($research);
		$player_id = self::getActivePlayerId();
		if (DB::checkArchive($player_id)) {
			throw new BgaUserException( self::_("Cannot file due to upgrade!"));
		}
		if (DB::checkArchiveLimit($player_id)) {
			throw new BgaUserException( self::_("Your archive is full!"));
		}
		
		// card was already selected (playerTurn)
		if (!$selected_card_id) {
			$selected_card_id = self::getGameStateValue('selected_card_id');
		} 
		// validate legal selection
		$gizmo = DB::getSingleGizmoById($selected_card_id);
		$location = $gizmo['card_location'];
		if (strpos( $location, 'row_') === false && strpos( $location, 'research') === false) {
			throw new BgaVisibleSystemException( "Card ".$selected_card_id." is not in the row nor research and thus cannot be filed" );
		}
		$card_sql = "SELECT card_id FROM gizmo_cards WHERE card_type_arg='".$selected_card_id."'";
		$db_id = self::getUniqueValueFromDB($card_sql);
		$this->gizmo_cards->moveCard( $db_id, 'filed', $player_id );
		
		// add the top card of the deck to the row
		$mt_gizmo = $this->mt_gizmos[$selected_card_id];
		$level = $mt_gizmo['level'];
		$new_card_id = null;
		$filed_from;
		// if NOT from research
		if ( self::getGameStateValue('research_level') == 0 ) {
			$filed_from = 'the row';
			$new_card = $this->gizmo_cards->pickCardForLocation( "deck_".$level, "row_".$level );
			if (!empty($new_card)) {
				$new_card_id = $new_card['type_arg'];
			}
		} else {
			$filed_from = 'Research';
		}
		self::handleResearchReturn();

		self::checkFileTriggers();
		// clear selected card
		self::setSelectedCardId(0);
		// notify everyone
		$player_name = self::getPlayerNameForNotification($player_id);
		self::notifyAllPlayers('cardBuiltOrFiled', clienttranslate('${player_name} ${action} a Level ${level} ${color} Gizmo from ${built_from}'), // add back tooltip?
			array (
				'i18n' => ['color', 'built_from', 'action'],
				'player_name' => $player_name,
				'action' => 'Files',
				'level' => DB::LevelAsNumerals($level),
				'color' => $mt_gizmo['color'],
				'built_from' => $filed_from,
				'filed_from' => $filed_from,
				'purchased_card_id' => $selected_card_id,
				'spent_spheres' => null,
				'new_card_id' => $new_card_id,
				'player_id' => $player_id,
				'deck_counts' => DB::getDeckCounts()
			)
		);
        $this->incStat(1, 'filed_number', $player_id);
        $this->incStat(1, 'filed_number');
		$this->gamestate->nextState( 'cardFile' );		
	}
	
	/*
		Some level 3 edge cases to handle later:
			pick 2 spheres
			all of the continous effects (prevent actions, build discounts)
	*/
	function triggerGizmo($gizmo_id) {
		$card_sql = "SELECT card_id,card_type,card_type_arg,card_location,card_location_arg,is_used,is_triggered FROM gizmo_cards WHERE card_type_arg='".$gizmo_id."'";
		$gizmo = self::getObjectFromDB($card_sql);
		$mt_gizmo = $this->mt_gizmos[$gizmo['card_type_arg']];
		// sanity: confirm gizmo is built by current player
		$active_player_id = self::getActivePlayerId();
		if ($gizmo['card_location'] != 'built') {
			throw new BgaVisibleSystemException( "Gizmo ".$gizmo_id." cannot be triggered as it is not built (".$gizmo['card_location'].")" );
		} else if (	$gizmo['card_location_arg'] != $active_player_id ) {
			throw new BgaVisibleSystemException( "Gizmo ".$gizmo_id." cannot be triggered as it is built by another player: (".$gizmo['card_location_arg'].")" );			
		} else if ( $gizmo['is_triggered'] != 1 ) {
			throw new BgaVisibleSystemException( "Gizmo ".$gizmo_id." has not been triggered this turn!" );		
		} else if ( $gizmo['is_used'] == 1 ) {
			throw new BgaVisibleSystemException( "Gizmo ".$gizmo_id." has already been used this turn!" );		
		} else {			
			self::setTriggeringGizmo($gizmo_id);
			switch ( $mt_gizmo['trigger_action'] ) {
				case 'pick':
				case 'pick_2':
				case 'pick_two':
					self::checkAction( 'triggerSphereSelect' );
					if (!DB::checkPlayerEnergyCapacity($active_player_id)) {			
						throw new BgaUserException( self::_("You cannot hold more Energy"));	
					}
					$this->gamestate->nextState( 'triggerSphereSelect' );	
					break;
				case 'draw':
				case 'draw_3':
				case 'draw_three':
					self::checkAction( 'triggerSphereRandom' );
					if (!DB::checkPlayerEnergyCapacity($active_player_id)) {			
						throw new BgaUserException( self::_("You cannot hold more Energy"));	
					}
					$this->gamestate->nextState( 'triggerSphereRandom' );	
					break;
				case 'score':
				case 'score_2':
					self::checkAction( 'gainVictoryPoint' );
					$this->gamestate->nextState( 'gainVictoryPoint' );	
					break;					
				case 'research':
					self::checkAction( 'triggerResearch' );
					$this->gamestate->nextState( 'triggerResearch' );
					break;
				case 'build_level1_for0':
					self::checkAction( 'buildLevel1For0' );
					$this->gamestate->nextState( 'buildLevel1For0' );					
					break;
				case 'file':
					self::checkAction( 'triggerFile' );
					if (DB::checkArchive($active_player_id)) {
						throw new BgaUserException( self::_("Cannot file due to upgrade!"));
					}
					if (DB::checkArchiveLimit($active_player_id)) {
						throw new BgaUserException( self::_("Your archive is full!"));
					}
					$this->gamestate->nextState( 'triggerFile' );
					break;
				default:
					throw new BgaVisibleSystemException( "Gizmo ".$gizmo_id." has unhandled trigger_action: ".$mt_gizmo['trigger_action'] );
					break;
			}
		}
	}
	
	function pass($research) {
		self::checkAction( 'pass' );
		$this->handleResearchOrder($research);
		$this->gamestate->nextState( 'pass' );
	}
	function draw() {
		self::checkAction( 'triggerSphereRandom' );
		$this->gamestate->nextState( 'triggerSphereRandom' );		
	}
	
	function research() {
		if (DB::checkResearch(self::getActivePlayerId())) {
			throw new BgaUserException( self::_("Cannot research due to upgrade!"));
		}
		self::checkAction( 'research' );
		$level = self::getGameStateValue('selected_card_id');
		if ($level < 1 || $level > 3) {
			throw new BgaVisibleSystemException( "Unexpected research level: $level" );			
		}
		$deck_id = "deck_$level";
		
		$player_id = self::getActivePlayerId();
		$card_sql = "SELECT player_research_quantity FROM player WHERE player_id=$player_id";
		$research_quantity = self::getUniqueValueFromDB($card_sql);
		$cards = $this->gizmo_cards->pickCardsForLocation( $research_quantity, $deck_id, "research", $level );
		if (count($cards) == 0) {
			throw new BgaUserException( self::_("No Gizmos left to Research!"));
		}

		self::setGameStateValue('research_level', $level);
		$this->gamestate->nextState( 'research' );
		self::notifyAllPlayers('research', clienttranslate('${player_name} Researches ${n} Level ${level} Gizmo(s)'),
			array (
				'player_name' => self::getPlayerNameForNotification($player_id),
				'n' => count($cards),
				'level' => DB::LevelAsNumerals($level),
				'deck_counts' => DB::getDeckCounts()
			)
		);
        $this->incStat(1, 'research_number', $player_id);
        $this->incStat(1, 'research_number');
	}
	
	function buildLevel1For0($gizmo_id) {
		// validate state
		self::checkAction( 'buildLevel1For0' );
		// validate gizmo is level 1
		$mt_gizmo = $this->mt_gizmos[$gizmo_id];
		if ($mt_gizmo['level'] != 1) {
			throw new BgaVisibleSystemException( "Gizmo must be Level I for buildLevel1For0!" );	
		}

		// identical to building a card just without spending or validating any spheres
		self::doBuildCard( $gizmo_id, self::getActivePlayerId(), null);
		
		$this->gamestate->nextState( 'buildLevel1For0' ); 
	}

    
//////////////////////////////////////////////////////////////////////////////
//////////// Game state arguments
////////////

    /*
        Here, you can create methods defined as "game state arguments" (see "args" property in states.inc.php).
        These methods function is to return some additional information that is specific to the current
        game state.
    */
	function arg_playerTurn() {
		// refresh the energy counts for each player to ensure accuracy
		$energy = DB::getAllPlayersEnergy();
		return array (
			'energy'=> $energy
		);
	}
	
	function arg_getSelectedCard() {
		$limits = DB::getPlayerLimits( self::getActivePlayerId() );
		return array (
			'selected_card_id' => self::getGameStateValue('selected_card_id'),
			'archive_limit' => $limits['archive_limit'],
			'energy_limit' => $limits['energy_limit'],
			'research_quantity' => $limits['research_quantity']
		);
	}
	function arg_getTriggeredCards() {
		$select_sql = "SELECT card_type_arg,is_used FROM gizmo_cards WHERE card_location = 'built' and card_location_arg=".self::getActivePlayerId()." and is_triggered=1";	
        return array( 
			'triggered_gizmos' => self::getCollectionFromDb( $select_sql )
		);
	}
	function arg_getResearchedCards() {
		$r_cards = DB::getResearchCards();
		return array(
			'_private' => array(          // Using "_private" keyword, all data inside this array will be made private
				'active' => array(       // Using "active" keyword inside "_private", you select active player(s)						
					'r_cards' => $r_cards
				)
			),
			'num_cards' => count( $r_cards ),
			'tg_gizmo_id' => self::getGameStateValue('triggering_gizmo_id'),
			'research_level' => self::getGameStateValue('research_level')
		);
	}
	function arg_getSelectedAndResearchedCard() {
		$r_cards = DB::getResearchCards();
		$limits = DB::getPlayerLimits( self::getActivePlayerId() );
		return array (
			'_private' => array(          // Using "_private" keyword, all data inside this array will be made private
				'active' => array(       // Using "active" keyword inside "_private", you select active player(s)						
					'r_cards' => $r_cards
				)
			),
			'num_cards' => count( $r_cards ),
			'research_level' => self::getGameStateValue('research_level'),
			'selected_card_id' => self::getGameStateValue('selected_card_id'),
			'archive_limit' => $limits['archive_limit'],
			'energy_limit' => $limits['energy_limit'],
			'research_quantity' => $limits['research_quantity'],
			'tg_gizmo_id' => self::getGameStateValue('triggering_gizmo_id')
		);
	}
	function arg_triggerSphereSelect() {
		$uses = self::getGameStateValue('triggering_multiple_uses');
		$desc;
		if ($uses > 0) {
			$desc = self::_('may pick a second energy or cancel to skip');			
		} else {
			$desc = self::_('may pick an available energy from the row');
		}
		
		return array(
			//'triggering_multiple_uses' => $uses,
			'desc' => $desc,
			'tg_gizmo_id' => self::getGameStateValue('triggering_gizmo_id')
		);
	}
	function arg_triggerDraw() {
		$uses = self::getGameStateValue('triggering_multiple_uses');
		$desc;
		if ($uses == 1) {
			$desc = self::_('may draw a second energy or cancel to skip');			
		} else if ($uses == 2) {
			$desc = self::_('may draw a third energy or cancel to skip');			
		} else {
			throw new BgaVisibleSystemException( "arg_triggerDraw has unexpected triggering_multiple_uses: $uses" );						
		}
		
		return array(
			//'triggering_multiple_uses' => $uses,
			'desc' => $desc,
			'tg_gizmo_id' => self::getGameStateValue('triggering_gizmo_id')
		);		
	}
	function arg_triggeringGizmo() {		
		return array(
			'tg_gizmo_id' => self::getGameStateValue('triggering_gizmo_id')
		);		
	}
	

    /*
    
    Example for game state "MyGameState":
    
    function argMyGameState()
    {
        // Get some values from the current game situation in database...
    
        // return values:
        return array(
            'variable1' => $value1,
            'variable2' => $value2,
            ...
        );
    }    
    */

//////////////////////////////////////////////////////////////////////////////
//////////// Game state actions
////////////

    /*
        Here, you can create methods defined as "game state actions" (see "action" property in states.inc.php).
        The action method of state X is called everytime the current game state is set to X.
    */
	
	function st_nextPlayer()
	{
		// First do something with stats to increment number of triggers?
		DB::clearAllGizmoTriggers();
        $player_id = $this->getActivePlayerId();
        $next_player_id = $this->getPlayerAfter($player_id);
        $this->giveExtraTime($next_player_id);
        $this->incStat(1, 'turns_number', $next_player_id);
        $this->incStat(1, 'turns_number');
        $this->gamestate->changeActivePlayer($next_player_id);
		
		$res = DB::getGameProgress();
		if ($res['progress'] >= 100) {
			if (!self::getGameStateValue('is_last_round')) {
				$msg;
				if ($res['3s']) {
					$msg = clienttranslate('${player_name} Builds their 4th Level III Gizmo');
				} else {
					$msg = clienttranslate('${player_name} Builds their 16th Gizmo');
				}
				self::notifyAllPlayers('lastTurn', "$msg<br/><div class='end_banner'>".clienttranslate("LAST ROUND")."</div>", 
					array ('player_name' => self::getPlayerNameForNotification($player_id))
				);
				self::setGameStateValue('is_last_round', 1);
			}
			if (DB::isFirstPlayer($next_player_id) ) {
				// Calculate variable point cards and add to score
				$cards = DB::getSpecialUpgradeGizmos();
				foreach ($cards as $gizmo_id => $card) {
					$player_id = $card['card_location_arg'];
					$mtg = $this->mt_gizmos[$gizmo_id];
					$gizmo_score;
					switch ($mtg['upgrade_special']) {
						case 'score_energy':
							$gizmo_score = DB::getPlayerEnergyCount($player_id);
							//$notif_trailer .= self::_("score equal to number of energy");
							break;
						case 'score_scores':
							$counts = DB::scoreVictoryPoints($player_id, 0);
							$gizmo_score = $counts['vps'];
							//$notif_trailer .= self::_("score equal to number of victory points");
							break;
						default:
							throw new BgaVisibleSystemException( "Unrecognized upgrade_special: ".$mtg['upgrade_special'] );
					}
					$player_score = DB::score($player_id, $gizmo_score);
					self::notifyAllPlayers('scoreSpecial', clienttranslate('${player_name} scores ${n} points for their upgrade: ${upgrade}'), 
						array (
							'player_name' => self::getPlayerNameForNotification($player_id),
							'n' => $gizmo_score,
							'upgrade' => $mtg['tooltip'],
							'player_id' => $player_id,
							'gizmo_score' => $gizmo_score,
							'player_score' => $player_score
						)
					);
				}

				DB::setPlayerAuxScores();

				$this->gamestate->nextState('endGame');
			}
		}
		$this->gamestate->nextState('nextTurn');
	}
		
	function st_triggerSphereRandom() 
	{
		$player_id = self::getActivePlayerId();
		// confirm player is not at energy capacity:
		if (!DB::checkPlayerEnergyCapacity($player_id)) {			
			throw new BgaUserException( self::_("You cannot hold more Energy"));	
		}

		// query database for spheres where status=dispenser
		$sphere_sql = "SELECT sphere_id FROM sphere WHERE location='dispenser'";
        $dispenser_spheres = self::getCollectionFromDb( $sphere_sql );
		// get a random sphere from the dispenser
		$new_sphere_id = array_rand($dispenser_spheres);
		// update sphere.status -> player, sphere.belngs-to-player -> current player
		$sql_new = "UPDATE sphere SET location='".$player_id."' WHERE sphere_id='$new_sphere_id'";
		self::DbQuery( $sql_new );
		
		$sphere_color = self::getSphereColor($new_sphere_id);
		$player_name = self::getPlayerNameForNotification($player_id);
		// send notification to indicate what sphere was drawn
		self::notifyAllPlayers('sphereDrawn', clienttranslate('${player_name} draws ${sphere_html}'), //a ${sphere_color} Energy"),
			array (
				'player_name' => $player_name,
				'sphere_html' => DB::getSphereHtml($sphere_color),
				'sphere_color' => $sphere_color,
				'sphere_id' => $new_sphere_id,
				'player_id' => $player_id
			)
		);
        $this->incStat(1, 'drawn_number', $player_id);
        $this->incStat(1, 'drawn_number');
		
		$this->gamestate->nextState('triggerCheck');					
	}
	
	function st_triggerCheck() 
	{
		$debug = 'st_triggerCheck:\n';
		self::handleResearchReturn();
		self::setSelectedCardId(0);
		$tg_gizmo_id = self::getGameStateValue('triggering_gizmo_id');
		$debug .= "\ttriggering_gizmo_id=$tg_gizmo_id\n";
		if ($tg_gizmo_id > 0) {
			// Handle cases where gizmo is used multiple times
			$mt_gizmo = $this->mt_gizmos[$tg_gizmo_id];
			$action = $mt_gizmo['trigger_action'];
			$debug .= "action=$action\n";
			switch ($action) {
				case 'pick_2':
				case 'pick_two':
					$uses = self::getGameStateValue('triggering_multiple_uses');
					$debug .= "\tuses=$uses\n";
					if ($uses < 1) {
						self::setGameStateValue('triggering_multiple_uses', 1);
						$this->gamestate->nextState('triggerSphereSelect');
						return;
					} else {
						self::setGameStateValue('triggering_multiple_uses', 0);
						goto default_case;
					}
					break;
				case 'draw_3':
				case 'draw_three':					
					$uses = self::getGameStateValue('triggering_multiple_uses');
					$debug .= "\tuses=$uses\n";
					if ($uses < 2) {
						self::setGameStateValue('triggering_multiple_uses', $uses+1);
						$this->gamestate->nextState('triggerDraw');
						return;
					} else {
						self::setGameStateValue('triggering_multiple_uses', 0);
						goto default_case;
					}
					break;
				default:
					default_case:
					DB::setGizmoUsed(self::getGameStateValue('triggering_gizmo_id'));
					$debug .= "\tset triggering_gizmo to used";
					self::setTriggeringGizmo(0);
					$this->incStat(1, 'trigger_number', self::getActivePlayerId());
					$this->incStat(1, 'trigger_number');
					break;				
			}
			
		}
		
		$player_id = self::getActivePlayerId();
		// All we care is if there exists at least one (LIMIT 1) gizmo that has triggered but has not been used
		$first_gizmo = DB::getTriggeredGizmo($player_id);
		
		//var_dump($debug);
		if (empty( $first_gizmo )) {
			// No more triggers -> next turn
			$this->gamestate->nextState('nextTurn');						
		} else {
			// More triggers to be used!
			$this->gamestate->nextState('triggerSelect');					
		}
	}
	
	function st_gainVictoryPoint() {
		$gizmo_id = self::getGameStateValue('triggering_gizmo_id');
		$mt_gizmo = $this->mt_gizmos[$gizmo_id];
		$action = $mt_gizmo['trigger_action'];
		$add_points;
		$plural;
		if ($action == 'score_2') {
			$add_points = 2;
			$plural = 's';
		} else if ($action == 'score') {
			$add_points = 1;
			$plural = '';
		} else {
			throw new BgaVisibleSystemException( "Gizmo $gizmo_id has unhandled trigger_action for st_gainVictoryPoint: $action" );			
		}
		$player_id = self::getActivePlayerId();
		$player_name = self::getPlayerNameForNotification($player_id);

		$counts = DB::scoreVictoryPoints($player_id, $add_points);
		self::notifyAllPlayers('victoryPoint', clienttranslate('${player_name} gains ${number} ${vp_html}'), //victory point token(s)"), 
			array (
				'player_name' => $player_name,
				'number' => $add_points,
				'vp_html' => '<div class="gzs_log_vp"></div>',
				'player_id' => $player_id,
				'vp_count' => $counts['vps'],
				'player_score' => $counts['score']
			)
		);
		
		$this->gamestate->nextState('triggerCheck');
	}

//////////////////////////////////////////////////////////////////////////////
//////////// Zombie
////////////

    /*
        zombieTurn:
        
        This method is called each time it is the turn of a player who has quit the game (= "zombie" player).
        You can do whatever you want in order to make sure the turn of this player ends appropriately
        (ex: pass).
        
        Important: your zombie code will be called when the player leaves the game. This action is triggered
        from the main site and propagated to the gameserver from a server, not from a browser.
        As a consequence, there is no current player associated to this action. In your zombieTurn function,
        you must _never_ use getCurrentPlayerId() or getCurrentPlayerName(), otherwise it will fail with a "Not logged" error message. 
    */

    function zombieTurn( $state, $active_player )
    {
    	$statename = $state['name'];
    	
        if ($state['type'] === "activeplayer") {
            switch ($statename) {
                default:
                    $this->gamestate->nextState( "zombiePass" );
                	break;
            }

            return;
        }

        if ($state['type'] === "multipleactiveplayer") {
            // Make sure player is in a non blocking status for role turn
            $this->gamestate->setPlayerNonMultiactive( $active_player, '' );
            
            return;
        }

        throw new feException( "Zombie mode not supported at this game state: ".$statename );
    }
    
///////////////////////////////////////////////////////////////////////////////////:
////////// DB upgrade
//////////

    /*
        upgradeTableDb:
        
        You don't have to care about this until your game has been published on BGA.
        Once your game is on BGA, this method is called everytime the system detects a game running with your old
        Database scheme.
        In this case, if you change your Database scheme, you just have to apply the needed changes in order to
        update the game database and allow the game to continue to run with your new version.
    
    */
    
    function upgradeTableDb( $from_version )
    {
        // $from_version is the current version of this game database, in numerical form.
        // For example, if the game was running with a release of your game named "140430-1345",
        // $from_version is equal to 1404301345
        
        // Example:
//        if( $from_version <= 1404301345 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "ALTER TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        if( $from_version <= 1405061421 )
//        {
//            // ! important ! Use DBPREFIX_<table_name> for all tables
//
//            $sql = "CREATE TABLE DBPREFIX_xxxxxxx ....";
//            self::applyDbUpgradeToAllDB( $sql );
//        }
//        // Please add your future database scheme changes here
//
//


    }

/// DEBUG UTILS
	  /*
   * loadBug: in studio, type loadBug(20762) into the table chat to load a bug report from production
   * client side JavaScript will fetch each URL below in sequence, then refresh the page
   */
  public function loadBug($reportId)
  {
    $db = explode('_', self::getUniqueValueFromDB("SELECT SUBSTRING_INDEX(DATABASE(), '_', -2)"));
    $game = $db[0];
    $tableId = $db[1];
    self::notifyAllPlayers('loadBug', "Trying to load <a href='https://boardgamearena.com/bug?id=$reportId' target='_blank'>bug report $reportId</a>", [
      'urls' => [
        // Emulates "load bug report" in control panel
        "https://studio.boardgamearena.com/admin/studio/getSavedGameStateFromProduction.html?game=$game&report_id=$reportId&table_id=$tableId",
        
        // Emulates "load 1" at this table
        "https://studio.boardgamearena.com/table/table/loadSaveState.html?table=$tableId&state=1",
        
        // Calls the function below to update SQL
        "https://studio.boardgamearena.com/1/$game/$game/loadBugSQL.html?table=$tableId&report_id=$reportId",
        
        // Emulates "clear PHP cache" in control panel
        // Needed at the end because BGA is caching player info
        "https://studio.boardgamearena.com/admin/studio/clearGameserverPhpCache.html?game=$game",
      ]
    ]);
  }
  
  /*
   * loadBugSQL: in studio, this is one of the URLs triggered by loadBug() above
   */
  public function loadBugSQL($reportId)
  {
    $studioPlayer = self::getCurrentPlayerId();
    $players = self::getObjectListFromDb("SELECT player_id FROM player", true);
  
    // Change for your game
    // We are setting the current state to match the start of a player's turn if it's already game over
    $sql = [
      "UPDATE global SET global_value=2 WHERE global_id=1 AND global_value=99"
    ];
    foreach ($players as $pId) {
      // All games can keep this SQL
      $sql[] = "UPDATE player SET player_id=$studioPlayer WHERE player_id=$pId";
      $sql[] = "UPDATE global SET global_value=$studioPlayer WHERE global_value=$pId";
      $sql[] = "UPDATE stats SET stats_player_id=$studioPlayer WHERE stats_player_id=$pId";
      $sql[] = "UPDATE gamelog SET gamelog_player=$studioPlayer WHERE gamelog_player=$pId";
      $sql[] = "UPDATE gamelog SET gamelog_current_player=$studioPlayer WHERE gamelog_current_player=$pId";
      $sql[] = "UPDATE gamelog SET gamelog_notification=REPLACE(gamelog_notification, $pId, $studioPlayer)";
  
      // TODO Add game-specific SQL updates for the tables, everywhere players ids are used in your game 
      $sql[] = "UPDATE gizmo_cards SET card_location_arg=$studioPlayer WHERE card_location_arg=$pId";
      $sql[] = "UPDATE sphere SET location='$studioPlayer' WHERE location='$pId'";
      $sql[] = "UPDATE gamelog SET gamelog_current_player=$studioPlayer WHERE gamelog_current_player=$pId";

      // This could be improved, it assumes you had sequential studio accounts before loading
      // e.g., quietmint0, quietmint1, quietmint2, etc. are at the table
      $studioPlayer++;
    }
    $msg = "<b>Loaded <a href='https://boardgamearena.com/bug?id=$reportId' target='_blank'>bug report $reportId</a></b><hr><ul><li>" . implode(';</li><li>', $sql) . ';</li></ul>';
    self::warn($msg);
    self::notifyAllPlayers('message', $msg, []);
  
    foreach ($sql as $q) {
      self::DbQuery($q);
    }
    self::reloadPlayersBasicInfos();
    $this->gamestate->reloadState();
  }

///
}
