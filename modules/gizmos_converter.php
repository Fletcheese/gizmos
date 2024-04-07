<?php

class Converter
{
    public static $game;

    public static function init( $theGame ) {
        self::$game = $theGame;
    }

    public static function validateBuild( $converters, $energies, $built_gid, $pid ) {
        $orders = array();
        foreach ($converters as $c_id => $c) {
            $o = $c['order'];
            $c['id'] = $c_id;
            $c['is_second'] = false;
            $orders[$o] = $c;
            if (!empty( $c['second_convert'] )) {
                $sc = $c['second_convert'];
                $o2 = $sc['order'];
                $sc['id'] = $c_id;
                $sc['is_second'] = true;
                $orders[$o2] = $sc;
            }
        }
        ksort($orders);
        //self::debug($orders);

        $spend_power = array(
            'black' => 0, 'blue' => 0, 'red' => 0, 'yellow' => 0
        );
        //self::debug($energies);
        if ($energies) {
            foreach ($energies as $i => $eid) {
                if ($eid) {
                    $color = self::$game->getSphereColor($eid);
                    $spend_power[$color]++;
                }
            }
        }
        //self::debug($spend_power);

        $used_converters = array();
        foreach ($orders as $o => $c_action) {
            if (!DB::isGizmoBuiltByPlayer($c_id, $pid)) {
				throw new BgaVisibleSystemException( "Cannot convert using gizmo[$c_id] not built by player[$pid]" );		
            }
            $c_id = $c_action['id'];
            $mt_c = self::$game->mt_gizmos[$c_id];
            // $c_action; 
            // if ($o_action['is_second']) {
            //     $c_action = $o_action;
            // } else {
            //     $c_action = $converters[$c_id];
            // }
            self::validateConvert($mt_c, $c_action, $spend_power);
            array_push($used_converters, $c_id);
		}

        // Ensure that spend_power is within the discount threshold i.e. spent color >= cost-discount AND spent color <= cost
        $mt_built = self::$game->mt_gizmos[$built_gid];
        $built_color = $mt_built['color'];
        $built_cost = $mt_built['cost'];
        $player_discount = DB::getDiscount($built_gid, $pid);
        $discount_cost = $built_cost - $player_discount;
        self::debug("Discount=$player_discount => MinCost=$discount_cost");

        if ($built_color == 'multi') {
            $total = 0;
            foreach ($spend_power as $color => $number) {
                $total += $number;
            }
            if ($total < $discount_cost || $total > $built_cost) {
				throw new BgaVisibleSystemException( "You selected $total energy for cost $built_cost.  Must pay exact cost" );		
            }
        } else {
            foreach ($spend_power as $color => $number) {
                if ($color == $built_color && ($number < $discount_cost || $number > $built_cost)) {
                    throw new BgaVisibleSystemException( "You selected $number $color energy for cost $built_cost.  Must pay exact cost" );		
                } 
                if ($color != $built_color && $number > 0) {
                    throw new BgaVisibleSystemException( "Gizmo is $built_color.  Cannot pay $number $color" );	
                }
            }
        }

        if ($used_converters) {
            DB::setGizmosUsed($used_converters);
        }
    }

    static function validateConvert($mt_c, $c_action, &$spend_power) {
        $c_id = $mt_c['id'];
        self::debug("[$]$c_id converts ".$c_action['from']." to ".$c_action['to_number'].$c_action['to_color']."\n");
       // self::debug($c_action);
        if ($mt_c['effect_type'] != 'converter') {
            throw new BgaVisibleSystemException( "Gizmo[$c_id] is not a converter" );           
        }
        $mt_from = $mt_c['convert_from'];
        $act_from_color = $c_action['from'];
        if ($mt_from != 'any' && $mt_from != $act_from_color && !Gizmos::stringContains($act_from_color, $mt_from)) {
            throw new BgaVisibleSystemException( "Gizmo[$c_id] cannot convert from color: $act_from_color ($mt_from)" );	           
        }
        if ($spend_power[$act_from_color] < 1) {
            throw new BgaVisibleSystemException( "No $act_from_color energy to convert" );	           
        }

        $mt_to = $mt_c['convert_to'];
        $act_to_num = $c_action['to_number'];
        $act_to_color = $c_action['to_color'];
        // If converts twice (2 colors), then second convert is allowed
        $valid_second = Gizmos::stringContains(',', $mt_from);
        switch ($mt_to) {
            case 'any2':
                $valid_second = true;
            case 'any':
                if ($act_to_num > 1) {
                    throw new BgaVisibleSystemException( "Gizmo[$c_id] cannot convert to more than one energy ($act_to_num)" );	     
                }
                $spend_power[$act_to_color]++;
                $spend_power[$act_from_color]--;
                break;
            case 'two':
            case '2':
            case 2:
                if ($act_from_color != $act_to_color) {
                    throw new BgaVisibleSystemException( "Gizmo[$c_id] cannot convert colors: $act_from_color to $act_to_color" );	 
                }
                if ($act_to_num != 2) {
                    throw new BgaVisibleSystemException( "Gizmo[$c_id] must convert to 2 energy ($act_to_num)" );	 
                }
                $spend_power[$act_to_color]++;
                break;
            default:
                throw new BgaVisibleSystemException( "Unrecognized convert_to: $mt_to" );                
        }
        $is_second = (isset($c_action['is_second']) ? $c_action['is_second'] : false);
        if ($is_second && !$valid_second) {
            throw new BgaVisibleSystemException( "Gizmo[$c_id] cannot include a second action" );	 
        }
        self::debug($spend_power);
    }

    static function debug($o) {
        if (false) {
            var_dump($o);
        }
    }
}