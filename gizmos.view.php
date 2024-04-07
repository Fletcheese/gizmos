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
 * gizmos.view.php
 *
 * This is your "view" file.
 *
 * The method "build_page" below is called each time the game interface is displayed to a player, ie:
 * _ when the game starts
 * _ when a player refreshes the game page (F5)
 *
 * "build_page" method allows you to dynamically modify the HTML generated for the game interface. In
 * particular, you can set here the values of variables elements defined in gizmos_gizmos.tpl (elements
 * like {MY_VARIABLE_ELEMENT}), and insert HTML block elements (also defined in your HTML template file)
 *
 * Note: if the HTML of your game interface is always the same, you don't have to place anything here.
 *
 */
  
  require_once( APP_BASE_PATH."view/common/game.view.php" );
  
  class view_gizmos_gizmos extends game_view
  {
    function getGameName() {
        return "gizmos";
    }    
  	function build_page( $viewArgs )
  	{		
  	    // Get players & players number
        $players = $this->game->loadPlayersBasicInfos();
        $players_nbr = count( $players );

        /*********** Place your code below:  ************/

        // $this->page->begin_block( "gizmos_gizmos", "tokens" );

		// $colors = array("red","black","blue","yellow");
        // $hor_scale = 30;
        // for( $x=0; $x<6; $x++ )
        // {
			// $this->page->insert_block( "tokens", array(
				// 'X' => $x,
				// 'LEFT' => $x*$hor_scale,
				// 'COLOR' => $colors[rand(0,3)])
			// );
        // }
		// $this->page->insert_block( "tokens", array(
			// 'X' => $x,
			// 'LEFT' => $x*$hor_scale,
			// 'COLOR' => 'random')
		// );


        // $this->page->begin_block( "gizmos_gizmos", "cards" );

		// // level 3 has 2 cards, level 2 has 3 cards, level 1 has 4 cards
		// $num_cards = 2;
        // $hor_scale = 110;
		// $row_offset = 55;
		// $ver_scale = 110;
		// for( $y=1; $y<=3; $y++ ) {
			// // TODO: insert deck https://en.doc.boardgamearena.com/Deck
			// for ( $x=0; $x<$num_cards; $x++) {				
				// $this->page->insert_block( "cards", array(
					// 'X' => $x+1,
					// 'Y' => 4-$y,
					// 'LEFT' => $x*$hor_scale + (3-$y)*$row_offset,
					// 'TOP' => ($y-1)*$ver_scale)
				// );				
			// }
			// $num_cards++;
		// }

        /*

        // Examples: set the value of some element defined in your tpl file like this: {MY_VARIABLE_ELEMENT}

        // Display a specific number / string
        $this->tpl['MY_VARIABLE_ELEMENT'] = $number_to_display;

        // Display a string to be translated in all languages: 
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::_("A string to be translated");

        // Display some HTML content of your own:
        $this->tpl['MY_VARIABLE_ELEMENT'] = self::raw( $some_html_code );

        */

        /*

        // Example: display a specific HTML block for each player in this game.
        // (note: the block is defined in your .tpl file like this:
        //      <!-- BEGIN myblock --> 
        //          ... my HTML code ...
        //      <!-- END myblock --> 


        $this->page->begin_block( "gizmos_gizmos", "myblock" );
        foreach( $players as $player )
        {
            $this->page->insert_block( "myblock", array( 
                                                    "PLAYER_NAME" => $player['player_name'],
                                                    "SOME_VARIABLE" => $some_value
                                                    ...
                                                     ) );
        }

        */



        /*********** Do not change anything below this line  ************/
  	}
  }
  

