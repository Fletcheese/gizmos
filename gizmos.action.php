<?php
/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * gizmos implementation : © Fletcheese <1337ch33z@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on https://boardgamearena.com.
 * See http://en.doc.boardgamearena.com/Studio for more information.
 * -----
 * 
 * gizmos.action.php
 *
 * gizmos main action entry point
 *
 *
 * In this file, you are describing all the methods that can be called from your
 * user interface logic (javascript).
 *       
 * If you define a method "myAction" here, then you can call it from your javascript code with:
 * this.ajaxcall( "/gizmos/gizmos/myAction.html", ...)
 *
 */
  
  
  class action_gizmos extends APP_GameAction
  { 
    // Constructor: please do not modify
   	public function __default()
  	{
  	    if( self::isArg( 'notifwindow') )
  	    {
            $this->view = "common_notifwindow";
  	        $this->viewArgs['table'] = self::getArg( "table", AT_posint, true );
  	    }
  	    else
  	    {
            $this->view = "gizmos_gizmos";
            self::trace( "Complete reinitialization of board game" );
      }
  	} 
  	
    public function cardSelected()
    {
        self::setAjaxMode();
        $selected_card_id = self::getArg( "selected_card_id", AT_posint, true );
        $research = self::getArg( "research_order", AT_numberlist, false );
        $result = $this->game->cardSelected( $selected_card_id, $research );
        self::ajaxResponse( );
    }
    public function cancel()
    {
        self::setAjaxMode();
        $research = self::getArg( "research_order", AT_numberlist, false );
        $result = $this->game->cancel( $research );
        self::ajaxResponse( );
	}
    
    public function cancelTrigger()
    {
        self::setAjaxMode();
        $result = $this->game->cancelTrigger( );
        self::ajaxResponse( );
    }
	
    public function sphereSelect()
    {
        self::setAjaxMode();
        $sphere_id = self::getArg( "sphere_id", AT_posint, true );
        $result = $this->game->sphereSelect( $sphere_id );
        self::ajaxResponse( );
    }
    public function buildSelectedCard()
    {
        self::setAjaxMode();
        $spheres = self::getArg( "spheres", AT_numberlist, true );
        $converters = self::getArg( "converters", AT_json, true );
        $research = self::getArg( "research_order", AT_numberlist, false );
        $this->game->validateJSonAlphaNum( $converters, 'converters' );
        $result = $this->game->buildSelectedCard( $spheres, $converters, $research );
        self::ajaxResponse( );
    }
    public function fileSelectedCard()
    {
        self::setAjaxMode();
        $selected_card_id = self::getArg( "selected_card_id", AT_posint, true );
        $research = self::getArg( "research_order", AT_numberlist, false );
        $result = $this->game->fileSelectedCard( $selected_card_id, $research );
        self::ajaxResponse( );
	}
    
	public function triggerSelected() 
	{
        self::setAjaxMode();
        $selected_card_id = self::getArg( "selected_card_id", AT_posint, true );
        $result = $this->game->triggerGizmo( $selected_card_id );
        self::ajaxResponse( );		
	}
	
	public function pass() {
        self::setAjaxMode();
        $research = self::getArg( "research_order", AT_numberlist, false );
        $result = $this->game->pass($research);
        self::ajaxResponse( );		
	}
	
	public function research() {
        self::setAjaxMode();
        $result = $this->game->research();
        self::ajaxResponse( );		
	}
	
	public function draw() {
        self::setAjaxMode();
        $result = $this->game->draw();
        self::ajaxResponse( );		
	}

    public function buildLevel1For0() {
        self::setAjaxMode();
        $gizmo_id = self::getArg( "gizmo_id", AT_posint, true );
        $result = $this->game->buildLevel1For0($gizmo_id);
        self::ajaxResponse( );		
    }


    public function loadBugSQL() {
        self::setAjaxMode();
        $reportId = (int) self::getArg('report_id', AT_int, true);
        $this->game->loadBugSQL($reportId);
        self::ajaxResponse();
      }
    
  }
  

