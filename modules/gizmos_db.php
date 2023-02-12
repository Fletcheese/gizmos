<?php

class DB
{
    public static $game;


    public static function init( $theGame ) {
        self::$game = $theGame;
	}

    /*
    UTILS
    */
	public function LevelAsNumerals($level) {
		$s = "I";
		$i = 1;
		while ($i < $level) {
			$s .= "I";
			$i++;
		}
		return $s;
	}

	public function scoreAndUpgradeBuiltCard($player_id, $built_mt_gizmo) {		
		$card_score;
		// All level 1s are worth 1 point
		if ($built_mt_gizmo['level'] == 1) {
			$card_score = 1;
		} else {
			$card_score = $built_mt_gizmo['points'];
		}

		$get_sql = "SELECT player_id id, player_score score, player_energy_limit energy_limit, player_archive_limit archive_limit, player_research_quantity research_quantity FROM player WHERE player_id = '$player_id'";
		$player = Gizmos::getObject($get_sql);
		$score = $player['score'] + $card_score;
		$update_sql = "UPDATE player SET player_score = '$score'";
		if ($built_mt_gizmo['effect_type'] == 'upgrade') {
			if ( !empty($built_mt_gizmo['upgrade_energy']) ) {
				$energy_limit = $player['energy_limit'] + $built_mt_gizmo['upgrade_energy'];
				$update_sql .= ", player_energy_limit = '$energy_limit'";
			}

			if ( !empty($built_mt_gizmo['upgrade_archive']) ) {
				$archive_limit = $player['archive_limit'] + $built_mt_gizmo['upgrade_archive'];
				$update_sql .= ", player_archive_limit = '$archive_limit'";
			}
			
			if ( !empty($built_mt_gizmo['upgrade_research']) ) {
				$research_quantity = $player['research_quantity'] + $built_mt_gizmo['upgrade_research'];
				$update_sql .= ", player_research_quantity = '$research_quantity'";
			}
			//$update_sql .= ", player_energy_limit = '$energy_limit', player_archive_limit = '$archive_limit', player_research_quantity = '$research_quantity'";
		}
		$update_sql .= " WHERE player_id = '$player_id'";
		
		Gizmos::DbQuery( $update_sql );
		return $score;
	}
	// Returns TRUE if player CAN hold more energy
	public static function checkPlayerEnergyCapacity($player_id) {		
        $sql = "SELECT player_energy_limit FROM player WHERE player_id=$player_id";
		$limit = Gizmos::getUniqueValue( $sql );
		
		$ecount = self::getPlayerEnergyCount($player_id);
		if ($ecount >= $limit) {
			return false;		
		} else {
			return true;
		}
	}
    public function getGameProgress() {
        // Get number of gizmos built for each player
		$select_sql = "SELECT card_type_arg, card_location_arg FROM gizmo_cards WHERE card_location = 'built'";
        $cards = Gizmos::getCollection( $select_sql );
		$counts = array();
		foreach ($cards as $gizmo_id => $card) {
			$pid = $card['card_location_arg'];
			if ( empty($counts[$pid]) ) {
				$counts[$pid] = array (
					'total' => 0,
					'threes' => 0
				);
			}
			$counts[$pid]['total']++;
			if ( self::$game->mt_gizmos[$gizmo_id]['level'] == 3 ) {
				$counts[$pid]['threes']++;				
			}			
		}
		$most_gs = 0;
		$most_3s = 0;
		foreach ($counts as $pid => $count) {
			if ($count['total'] > $most_gs) {
				$most_gs = $count['total'];
			}
			if ($count['threes'] > $most_3s) {
				$most_3s = $count['threes'];
			}
		}		
		$prog_gs = 100*$most_gs/16;
		$prog_3s = 100*$most_3s/4;
				
		// return whichever is greater
		if ($prog_gs > $prog_3s) {
			return array('progress' => $prog_gs, '3s' => false);
		} else {
			return array('progress' => $prog_3s, '3s' => true);
		}
    }
	public function getSpecialUpgradeGizmos($player_id = null) {
		$card_sql = "SELECT card_type_arg,card_location_arg FROM gizmo_cards WHERE card_type_arg IN (333,334,335,336) AND card_location = 'built'";
		if ($player_id) {
			$card_sql .= " AND card_location_arg = $player_id";
		}
		return Gizmos::getCollection($card_sql);
	}

    public function isFirstPlayer($pid) {
        $sql = "SELECT player_no FROM player WHERE player_id = '$pid'";
        return 1 == Gizmos::getUniqueValue($sql);
    }
    /*
    END UTILS
    */

    /*
    GIZMO_CARDS
    */    
	public static function getBuiltOrFiledCards() {
		$select_sql = "SELECT card_type_arg type_arg,card_location card_location,card_location_arg card_location_arg,card_type card_type,is_triggered is_triggered,is_used is_used FROM gizmo_cards WHERE card_location = 'built' or card_location = 'filed'";	
        return Gizmos::getCollection( $select_sql );		
	}    
	public static function getPlayerFiledCount($pid) {
		$select_sql = "SELECT card_type_arg,card_location,card_location_arg FROM gizmo_cards WHERE card_location = 'filed' AND card_location_arg = '$pid'";	
        return count( Gizmos::getCollection($select_sql) );		
	}
	public static function setGizmoUsed($gizmo_id) {
		Gizmos::DbQuery( "UPDATE gizmo_cards SET is_used=1 WHERE card_type_arg=$gizmo_id" );			
	}
	public static function setGizmosUsed($gizmo_ids) {
		$s_ids = implode(',', $gizmo_ids);
		Gizmos::DbQuery( "UPDATE gizmo_cards SET is_used=1 WHERE card_type_arg IN ($s_ids)" );			
	}
	public static function clearAllGizmoTriggers() {
		Gizmos::DbQuery( "UPDATE gizmo_cards SET is_triggered=0,is_used=0 WHERE is_triggered=1 OR is_used=1" );		
	}
	public static function getSingleGizmoById($gizmo_id) {
		$card_sql = "SELECT card_location,card_location_arg FROM gizmo_cards WHERE card_type_arg='$gizmo_id'";
		return Gizmos::getObject($card_sql);
	}
    public static function isGizmoBuiltByPlayer($gid, $pid) {
        $gizmo = self::getSingleGizmoById($gid);
        return $gizmo['card_location'] == 'built' && $gizmo['card_location_arg'] == $pid;
    }
	public static function checkResearch($pid) {
		$sql = "SELECT card_type_arg,card_location,card_location_arg FROM gizmo_cards WHERE card_type_arg IN (321,322) AND card_location = 'built' AND card_location_arg = '$pid'";
		return Gizmos::getCollection($sql);
	}
	public static function checkArchive($pid) {
		$sql = "SELECT card_type_arg,card_location,card_location_arg FROM gizmo_cards WHERE card_type_arg IN (323,324) AND card_location = 'built' AND card_location_arg = '$pid'";
		return Gizmos::getCollection($sql);
	}
	public static function validateLegalBuildLocation($gid, $player_id) {
		$card_sql = "SELECT card_id card_id,card_location location,card_location_arg location_arg,card_type_arg type_arg FROM gizmo_cards WHERE card_type_arg='".$gid."'";
		$built_card = Gizmos::getObject($card_sql);
		$built_from_file = false;
		$location = $built_card['location'];
		// Validate that card is either in your archive OR a row OR research (not a deck)
		if ( $location == 'filed' ) {
			if ($built_card['location_arg'] != $player_id) {
				throw new BgaVisibleSystemException( "Cannot build card $gid from another player's archive!" );
			} else {
				$built_from_file = true;
			}
		} else if ( !Gizmos::stringContains('row_', $location) && $location != 'research' ) {
			throw new BgaVisibleSystemException( "Cannot build card $gid from ".$built_card['location'] );
		}
		return $built_card;
	}
	public static function getTriggeredGizmos($player_id) {
		$select_sql = "SELECT card_type_arg,card_id FROM gizmo_cards WHERE is_triggered=1 AND is_used=0 AND card_location='built' AND card_location_arg='$player_id'";	
        return Gizmos::getCollection( $select_sql );
	}
	public function getDiscount($gid, $pid) {
		$valid_ids = array();
		$mtg = self::$game->mt_gizmos[$gid];

		if ($mtg['level'] == 2) {
			array_push($valid_ids, 315, 316);
		}
		$built_card = self::getSingleGizmoById($gid);
		if ($built_card['card_location'] == 'filed' && $built_card['card_location_arg'] == $pid) {
			array_push($valid_ids, 327, 328);
		}
		if ($built_card['card_location'] == 'research') {
			array_push($valid_ids, 329, 330);
		}
		if (count($valid_ids) > 0) {
			$s_ids = implode(',', $valid_ids);
			$sql = "SELECT card_type_arg,card_location,card_location_arg FROM gizmo_cards WHERE card_type_arg IN ($s_ids) AND card_location = 'built' AND card_location_arg = '$pid'";
			return count( Gizmos::getCollection($sql) );
		} else {
			return 0;
		}
	}
	public static function getBuiltGizmos() {
		$select_sql = "SELECT card_location_arg,card_type_arg,card_location FROM gizmo_cards WHERE card_location = 'built'";	
        return Gizmos::getDoubleKeyCollection( $select_sql );
	}
	public static function getDeckCounts() {
		$select_sql = "SELECT card_location,card_type_arg FROM gizmo_cards";	
        $cards = Gizmos::getDoubleKeyCollection( $select_sql );
		return [
			'deck_1' => isset($cards['deck_1']) ? count( $cards['deck_1']) : 0,
			'deck_2' => isset($cards['deck_2']) ? count( $cards['deck_2']) : 0,
			'deck_3' => isset($cards['deck_3']) ? count( $cards['deck_3']) : 0
		];
	}
	public static function getResearchCards() {
		return Gizmos::getCollection( "SELECT card_type_arg,card_location_arg FROM gizmo_cards WHERE card_location='research' ORDER BY card_location_arg DESC" );
	}
	
	public static function canPlayerPickOrDraw($player_id) {
		return DB::checkPlayerEnergyCapacity($player_id);
	}
	public static function canPlayerFile($player_id) {
		return !DB::checkArchive($player_id) && !DB::checkArchiveLimit($player_id);
	}
	public static function canPlayerResearch($player_id) {
		return !DB::checkResearch($player_id);
	}
	public static function isLegalTrigger($gizmo_id, $player_id) {
		$mt_gizmo = self::$game->mt_gizmos[$gizmo_id];
		switch ( $mt_gizmo['trigger_action'] ) {
			case 'pick':
			case 'pick_2':
			case 'pick_two':
			case 'draw':
			case 'draw_3':
			case 'draw_three':
				// Check energy capacity
				return DB::canPlayerPickOrDraw($player_id);
			case 'file':
				return DB::canPlayerFile($player_id);		
			case 'research':
				return DB::canPlayerResearch($player_id);
				break;
			case 'score': // gaining VP is always possible
			case 'score_2':
			case 'build_level1_for0': 
				return true;
				break;		
			default:
				throw new BgaVisibleSystemException( "Gizmo ".$gizmo_id." has unhandled trigger_action: ".$mt_gizmo['trigger_action'] );
				break;
		}
	}
    /*
    END GIZMO_CARDS
    */

	/*
	ENERGY/SPHERES
	*/
	public static function getRowEnergy() {
		$sphere_sql = "SELECT sphere_id,location FROM sphere WHERE location != 'dispenser'";
        return Gizmos::getCollection( $sphere_sql );
	}
	public static function getAllPlayersEnergy() {	
		$sphere_sql = "SELECT sphere_id,location FROM sphere WHERE location NOT IN ('dispenser','row','next')";
        return Gizmos::getCollection( $sphere_sql, true );
	}
	public static function getPlayerEnergyCount($player_id) {		
		$sphere_sql = "SELECT sphere_id FROM sphere WHERE location='$player_id'";
        $player_spheres = Gizmos::getCollection( $sphere_sql );
		return sizeof( $player_spheres );
	}
	public static function putSpheresInDispenser($sphere_ids) {	
		if ($sphere_ids) {	
			$sphere_sql = "UPDATE sphere SET location='dispenser' WHERE sphere_id in ($sphere_ids)";
			Gizmos::DbQuery( $sphere_sql );
		}
	}
	public static function getNextEnergy() {
		$sphere_sql = "SELECT sphere_id FROM sphere WHERE location='next'";
        return Gizmos::getUniqueValue( $sphere_sql );
	}
	public static function moveNextToRow() {
		// handle active games when deployed where next energy does not exist
		if (!self::getNextEnergy()) {
			self::randomDispenserNext();
		}
		$sphere_sql = "UPDATE sphere SET location='row' WHERE location = 'next'";
		Gizmos::DbQuery( $sphere_sql );
	}
	public static function randomDispenserNext() {
		$sphere_sql = "SELECT sphere_id FROM sphere WHERE location='dispenser'";
        $spheres = Gizmos::getCollection( $sphere_sql );
		
		$new_sphere = array_rand($spheres);
		$sql_new = "UPDATE sphere SET location='next' WHERE sphere_id='$new_sphere'";
		Gizmos::DbQuery( $sql_new );
		return $new_sphere;
	}
	public static function moveSphereToPlayer($sphere_id, $player_id) {		
		$sql = "UPDATE sphere SET location='$player_id'
                    WHERE sphere_id='$sphere_id'";
		Gizmos::DbQuery( $sql );	
	}
	public static function getSphereLocation($sphere_id) {		
		$sel_sql = "SELECT location FROM sphere WHERE sphere_id='$sphere_id'";
		return Gizmos::getUniqueValue( $sel_sql );
	}
	/*
	END ENERGY/SPHERES
	*/

    /*
    PLAYERS
    */
    public static function getPlayerLimits($pid) {        
        $sql = "SELECT player_energy_limit energy_limit, player_archive_limit archive_limit, player_research_quantity research_quantity FROM player WHERE player_id = '$pid'";
        return Gizmos::getObject( $sql );
    }
	public static function score($player_id, $number) {		
		$get_sql = "SELECT player_score FROM player WHERE player_id = '$player_id'";
		$score = Gizmos::getUniqueValue($get_sql);
		if ($number > 0) {
			$score += $number;
			$update_sql = "UPDATE player SET player_score = '$score' WHERE player_id = '$player_id'";
			Gizmos::DbQuery( $update_sql );
		}
		return $score;
	}
	public static function scoreVictoryPoints($player_id, $number) {		
		$select_sql = "SELECT player_score,victory_points FROM player WHERE player_id=$player_id";
		$counts = Gizmos::getObject( $select_sql );
        $vp_count = $counts['victory_points'];
		$score = $counts['player_score'];
		if ($number > 0) {
			$vp_count += $number;
			$score += $number;
			$update_sql = "UPDATE player SET victory_points=$vp_count,player_score=$score WHERE player_id=$player_id";
			Gizmos::DbQuery( $update_sql );
		}
		return ['vps' => $vp_count, 'score' => $score];
	}
	public static function getPlayers() {
		$sql = "SELECT player_id id, player_color color, player_score score, victory_points, player_energy_limit energy_limit, player_archive_limit archive_limit, player_research_quantity research_quantity, player_no FROM player ORDER BY player_no ASC";
        return Gizmos::getCollection( $sql );
	}
	public static function setPlayerAuxScores() {
		$players = self::getPlayers();
		$gizmos = self::getBuiltGizmos();
		$sql = "UPDATE player SET player_score_aux=%s WHERE player_id=%s";
		foreach ($gizmos as $player_id => $built_gs) {
			$energy_count = self::getPlayerEnergyCount($player_id);
			// Number of gizmos in Active Gizmo Area followed by most remaining energy followed by furthest from first player in turn order
			$tie_breaker = (10000 * count($built_gs)) + (100 * $energy_count) + $players[$player_id]['player_no'];
			//var_dump($tie_breaker);
			Gizmos::DbQuery( sprintf($sql, $tie_breaker, $player_id) );
		}
		//throw new BgaUserException("TIE BREAKERS DEBUG");
	}
	// returns TRUE if Archive IS FULL
	public static function checkArchiveLimit($player_id) {
        $sql = "SELECT player_archive_limit FROM player WHERE player_id = '$player_id'";
        $limit = Gizmos::getUniqueValue( $sql );
		$filed = self::getPlayerFiledCount($player_id);
		return $filed >= $limit;
	}
	
	public static function setPlayerPref($player_id, $pref_id, $pref_val) {
		$sql = "INSERT INTO user_preferences (player_id,pref_id,pref_value) VALUES ($player_id,$pref_id,$pref_val) ON DUPLICATE KEY UPDATE player_id=VALUES(player_id),pref_id=VALUES(pref_id),pref_value=VALUES(pref_value)"; 
			//"UPDATE user_preferences SET pref_value=$pref_val WHERE player_id=$player_id AND pref_id=$pref_id";
		//var_dump($sql);
		Gizmos::DbQuery( $sql );
	}
	public static function isAutoPassTriggers($player_id) {
		$sql = "SELECT pref_value FROM user_preferences WHERE player_id=$player_id AND pref_id=202";
		$val = Gizmos::getUniqueValue( $sql );
		return $val && $val == 2;
	}
    /*
    END PLAYERS
    */
    
}