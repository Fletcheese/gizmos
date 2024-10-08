/**
 *------
 * BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
 * gizmos implementation : © Fletcheese <1337ch33z@gmail.com>
 *
 * This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
 * See http://en.boardgamearena.com/#!doc/Studio for more information.
 * -----
 *
 * gizmos.js
 *
 * gizmos user interface script
 * 
 * In this file, you are describing the logic of your user interface, in Javascript language.
 *
 */

define([
    "dojo","dojo/_base/declare",
    "ebg/core/gamegui",
    "ebg/counter",
    //"ebg/stock",
    "ebg/zone",
    g_gamethemeurl + "modules/gizmos_helpers.js",
],
function (dojo, declare) {
	
    return declare("bgagame.gizmos", ebg.core.gamegui, {
        constructor: function() {
            console.log('gizmos constructor: 2024-08-23');
			//this.default_viewport = 'width=400';
			Game.zones = {};              
			this.card_width = 170.5;
			this.card_height = 170.5;
			Game.selected_card_id = 0;
			this.deck_counters = [];
			Builder.active = {};
			Builder.spend_spheres = {};
			this.gamedatas = {};
			
			// Set mobile viewport for portrait orientation based on gameinfos.inc.php
			this.default_viewport = "width=" + this.interface_min_width;
			this.onScreenWidthChange();
			
			// Load production bug report handler
			const self = this; // save the `this` context in a variable
			dojo.subscribe("loadBug", this, function loadBug(n) {
				function fetchNextUrl() {
				var url = n.args.urls.shift();
				console.log("Fetching URL", url, "...");
				// all the calls have to be made with ajaxcall in order to add the csrf token, otherwise you'll get "Invalid session information for this action. Please try reloading the page or logging in again"
				self.ajaxcall(url,
				{
					lock: true,
				},
				self,
				function (success) {
					console.log("=> Success ", success);
			
					if (n.args.urls.length > 1) {
						fetchNextUrl();
					}
					else if (n.args.urls.length > 0) {
					//except the last one, clearing php cache
					url = n.args.urls.shift();
					dojo.xhrGet({
						url: url,
						load: function (success) {
						console.log("Success for URL", url, success);
						console.log("Done, reloading page");
						window.location.reload();
						},
						handleAs: "text",
						error: function (error) {
						console.log("Error while loading : ", error);
						}
					});
				}
				}
				,
				function (error) {
				if (error)
					console.log("=> Error ", error);
				}
				);
			}
			console.log("Notif: load bug", n.args);
			fetchNextUrl();
			});
        },		

		onScreenWidthChange: function() {
			// Remove broken "zoom" property added by BGA framework
			// this.gameinterface_zoomFactor = 1;
			// $("page-content").style.removeProperty("zoom");
			// $("page-title").style.removeProperty("zoom");
			// $("right-side-first-part").style.removeProperty("zoom");
			
			let width = window.innerWidth;
			if (width < Const.Breakpoint) {
				// if ($('energy_ring').parentNode.id == 'board_right') {
				// 	dojo.place('energy_ring', 'board_left');
				// }
				if ($('nrg_card_rows').parentNode.id == 'board_right') {
					dojo.place('nrg_card_rows', 'player_gizmos', 'before');
				}
			} else {
				// if ($('energy_ring').parentNode.id == 'board_left') {
				// 	dojo.place('energy_ring', 'board_right');
				// 	Game.repositionEnergyRing();
				// }
				if ($('nrg_card_rows').parentNode.id == 'board_left') {
					dojo.place('nrg_card_rows', 'board_right');
				}
			}
			Game.repositionEnergyRing();
		},
				
        /*
            setup:
            
            This method must set up the game user interface according to current game situation specified
            in parameters.
            
            The method is called each time the game interface is displayed to a player, ie:
            _ when the game starts
            _ when a player refreshes the game page (F5)
            
            "gamedatas" argument contains all datas retrieved by your "getAllDatas" PHP method.
        */
        
        setup: function( gamedatas )
        {
			console.log("Current state: " + Game.stateName);			
			Energy.colors = gamedatas.mt_colors;
			Gizmo.mt_gizmos = gamedatas.mt_gizmos;
			Gizmo.cards = gamedatas.gizmo_cards;
			Game.activePlayer = this.getActivePlayerId();
			Builder.showMessage = this.showMessage;
			this.gamedatas = gamedatas;
			Game.selected_card_id = gamedatas.selected_card_id;	
			if (gamedatas.is_last_round == 1) {
				$('gzs_end_banner').innerHTML = _("This is the last round!");
				dojo.style('gzs_end_banner', 'display', 'block');
			}
			
			this.setupCards(gamedatas);
			this.initSphereRowAndPlayerCards(gamedatas);
			
			dojo.query( '.token' ).connect( 'onclick', this, 'onEnergySelect' );
			dojo.query( '.deck' ).connect( 'onclick', this, 'onCardSelect' );
			//dojo.query( '.card' ).connect( 'onclick', this, 'onCardSelect' );

			this.addTooltipHtmlToClass('track_upgrades', this.format_block('jstpl_trackTooltip', {type: 'upgrades', offset: 0, text: "" }));
			this.addTooltipHtmlToClass('track_converters', this.format_block('jstpl_trackTooltip', {type: 'converters', offset: Const.TrackSeg_Width, text: Const.Tooltip_Converters() }));
			this.addTooltipHtmlToClass('track_trigger_file', this.format_block('jstpl_trackTooltip', {type: 'trigger_file', offset: Const.TrackSeg_Width*2, text: Const.Tooltip_File() }));
			this.addTooltipHtmlToClass('track_trigger_pick', this.format_block('jstpl_trackTooltip', {type: 'trigger_pick', offset: Const.TrackSeg_Width*3, text: Const.Tooltip_Pick() }));
			this.addTooltipHtmlToClass('track_trigger_build', this.format_block('jstpl_trackTooltip', {type: 'trigger_build', offset: Const.TrackSeg_Width*4, text: Const.Tooltip_Build() }));
			this.addTooltipHtmlToClass('track_archive', this.format_block('jstpl_trackTooltip', {type: 'archive', offset: Const.TrackSeg_Width*5, text: Const.Tooltip_Research() }));
			this.addTooltip('research_help', _('Researched Gizmos will be returned to the bottom of the deck in the order shown starting with the first card on top. You may use the arrows to adjust this order'), '');
			 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();
			
			this.initPreferencesObserver();

			if (Game.isColorblindFriendly(this)) {
				dojo.query('.token').addClass('colorblind');
			}

			console.log( "Ending game setup" );
        },

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
			Game.unlock();
			if (args && args.args) {
				if (args.args.tg_gizmo_id && $(Gizmo.getEleId(args.args.tg_gizmo_id) )) {
					dojo.removeClass( Gizmo.getEleId(args.args.tg_gizmo_id), 'triggerable' );
					dojo.addClass( Gizmo.getEleId(args.args.tg_gizmo_id), 'half_selected' );
				} else {
					dojo.query('.half_selected').removeClass('half_selected');
				}

				if (args.args.used_gizmos) {
					for (var gizmo_id in args.args.used_gizmos) {
						dojo.addClass(Gizmo.getEleId(gizmo_id), 'already_used' );
					}
				}
				
				if (args.args.deck_counts) {				
					Game.updateDeckCounts(args.args.deck_counts);
				}
			} else {
				dojo.query('.half_selected').removeClass('half_selected');
			}
			
			dojo.query('.selected').removeClass('selected');
			
			Game.stateName = stateName;
            console.log( 'Entering state w args: '+stateName, args );
			
            switch( stateName )
            {
				case 'playerTurn':
					dojo.query('.triggerable').removeClass('triggerable');
					dojo.query('.gzs_illegal').removeClass('gzs_illegal');
					dojo.query('.already_used').removeClass('already_used');
					Game.activePlayer = this.getActivePlayerId();
					dojo.query('.row_card').addClass('selectable');
					dojo.query('#'+Game.getPlayerArchive(Game.activePlayer)+' .filed').addClass('selectable');

					dojo.query('.active_player').removeClass('active_player');
					dojo.addClass('player_header_'+Game.activePlayer, 'active_player');

					if (args && args.args && args.args.energy) {
						Builder.reinitSphereCounts(this.gamedatas.players, args.args.energy, this);
					}
					Game.hideResearch(this);
				case 'triggerResearch':
					dojo.query('.deck').addClass('selectable');
					break;
				case 'triggerSelect':
					//console.log('highlighting triggerable cards:');
					console.log(args);					
					if (args && args.args && args.args.triggered_gizmos) {
						let tg_gizmos = args.args.triggered_gizmos;
						let illegals = args.args.illegal_actions;
						console.log("triggerSelect", tg_gizmos, illegals);
						for (var gizmo_id in tg_gizmos) {
							let gizmo = tg_gizmos[gizmo_id];
							dojo.addClass(Gizmo.getEleId(gizmo_id), gizmo.is_used == "1" ? 'already_used' : 'triggerable');
							dojo.removeClass(Gizmo.getEleId(gizmo_id), gizmo.is_used == "1" ? 'triggerable' : 'already_used');
							if (illegals && illegals.findIndex(x => x == gizmo_id) >= 0) {
								dojo.addClass(Gizmo.getEleId(gizmo_id), 'gzs_illegal');
							}
						}
					}
					Game.hideResearch(this);			
					break;
				case 'research':
					// console.log("POPULATING RESEARCH:");
					// console.log(args);
					if (args && args.args && args.args._private) {
						this.r_gizmos = args.args._private.r_cards;
					} else {
						this.r_gizmos = args.args.num_cards;
					}
					this.r_level = args.args.research_level;
					this.showResearch();
					break;
				case 'cardSelected':
					//Builder.handleButtonDisabled();
				case 'deckSelected':
					this.handleSelectedCard();
				case 'researchedCardSelected':
					//console.log(args);
					if (args && args.args) {

						if (args && args.args && args.args.research_level) {							
							if (args && args.args && args.args._private) {
								this.r_gizmos = args.args._private.r_cards;
							} else {
								this.r_gizmos = args.args.num_cards;
							}
							Game.selected_card_id = args.args.selected_card_id;
							this.r_level = args.args.research_level;
							this.showResearch();
						}
					}
					Builder.checkApplyDiscounts();
					if (this.isCurrentPlayerActive()) {
						Builder.autoselectSpend();
					}
					if (this.player_id == this.getActivePlayerId() && stateName != 'deckSelected' && dojo.query( '#converter_'+this.player_id+' .card' ).length > 0) {
						dojo.addClass('converter_' + this.player_id, 'highlighted');
					}
					Builder.refreshHeader(this);
					break;
				case 'buildLevel1For0':
					if (this.isCurrentPlayerActive()) {
						dojo.query('#row_1 .card_1').addClass('selectable');
						dojo.query(Game.getPlayerArchive() + " .card_1").addClass('selectable');
					}
					break;
				case 'gameEnd':
					dojo.query('.triggerable').removeClass('triggerable');
					dojo.query('.gzs_illegal').removeClass('gzs_illegal');
					dojo.query('.already_used').removeClass('already_used');
					break;
				default:
					//console.log('ERROR: UNEXPECTED STATE: ' + stateName);
					break;
            }
        },

        // onLeavingState: this method is called each time we are leaving a game state.
        //                 You can use this method to perform some user interface changes at this moment.
        //
        onLeavingState: function( stateName )
        {
            console.log( 'Leaving state: '+stateName );
			switch (stateName) {
				case 'playerTurn':
					dojo.query('.row_card').removeClass('selectable');
					dojo.query(Game.getPlayerArchive(Game.activePlayer)+' .filed').removeClass('selectable');
				case 'triggerResearch':
					dojo.query('.deck').removeClass('selectable');
					break;
				case 'cardSelected':
				case 'deckSelected':
				case 'researchedCardSelected':
					if (this.player_id == this.getActivePlayerId()) {
						dojo.removeClass('converter_' + this.player_id, 'highlighted');
					}
					dojo.query('.selected').removeClass('selected');
					dojo.query('.half_selected').removeClass('half_selected');
					dojo.query('.discount').removeClass('selectable');
					dojo.query('.tempnrg').forEach(dojo.destroy);
					Builder.resetVars();					
					break;
				case 'triggerSelect':
					break;
				case 'research':
					// if (!Game.waitHideResearch) {
					// 	Game.hideResearch(this);
					// }
					break;					
				default:
					break;
			}
        }, 

        // onUpdateActionButtons: in this method you can manage "action buttons" that are displayed in the
        //                        action status bar (ie: the HTML links in the status bar).
        //        
        onUpdateActionButtons: function( stateName, args )
        {
			if (args && args.selected_card_id) {
				Game.selected_card_id = args.selected_card_id;
				console.log('Set selected_card_id=' + Game.selected_card_id);
			}
            console.log( 'onUpdateActionButtons: '+stateName );                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
					case 'playerTurn':
						if (args?.can_research === false
							&& args?.can_file === false
							&& args?.can_pick === false) {
							this.addActionButton( 'button_pass', _('Pass'), 'passTurn' );							
						}
						break;
					case 'deckSelected':
						this.addActionButton( 'button_research', 
							this.format_string_recursive( _('Research Level ${level} (${quantity})'), {
									i18n: ['level', 'quantity'],
									level: Gizmo.levelNumerals(Game.selected_card_id), 
									quantity: this.gamedatas.players[this.getActivePlayerId()].research_quantity
							}), 'researchSelectedDeck' );
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancelSelectedCard' );
						break;
					case 'triggerSphereSelect':
						this.addActionButton( 'button_cancel', 
							args?.is_skip ? _('Skip') : _('Cancel'), 
							'cancel'
						);
						break;
					case 'research':
						this.addActionButton( 'button_pass', _('Pass'), 'passResearch' );
						break;
					case 'cardSelected':
					case 'researchedCardSelected':
						this.addActionButton( 'button_build', 
							this.format_string_recursive( _('Build (${energy})'), {
								energy: {
									log: '${x} / ${y} ${color}',
									args: Builder.getSpendSpheresArgs(this)
								}
							}), 
							'buildSelectedCard' );
						this.addActionButton( 'button_file', _('File'), 'fileSelectedCard' );
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancelSelectedCard' );
						Builder.handleButtonDisabled();
						break;
					case 'triggerSelect':
						this.addActionButton( 'button_pass', _('Pass'), 'passTriggers' );
						break;
					case 'triggerDraw':
						this.addActionButton( 'button_draw', _('Draw'), 'drawEnergy' );
						this.addActionButton( 'button_cancel', 
							args?.is_skip ? _('Skip') : _('Cancel'), 
							'cancel' 
						);					
						break;
					case 'triggerResearch':
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancel' );
						break;
					case 'buildLevel1For0':	
						this.addActionButton( 'button_build', _('Build'), 'buildLevel1For0' );
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancel' );
						dojo.addClass( 'button_build', 'disabled');
						break;	
					case 'triggerFile':	
						this.addActionButton( 'button_file', _('File'), 'fileSelectedCard' );
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancel' );
						dojo.addClass( 'button_file', 'disabled');
						break;					
					default:
						break;
				}
				if (args && args.can_file !== undefined && !args.can_file && $('button_file')) {
					dojo.addClass( 'button_file', 'disabled');
				}
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */

		setUpgradeScores: function(player_scores) {
			if (player_scores) {
				for (var player_id in player_scores) {
					this.setUpgradeScore(player_id, player_scores[player_id]);
				}
			}
		},
		setUpgradeScore: function(player_id, score) {
			let upg_score_id = 'player_upgrade_score_'+player_id;
			var upg_score = $(upg_score_id);
			if (upg_score) {
				if (score > 0) {
					upg_score.innerHTML = " (+"+score+")";
				} else {
					upg_score.innerHTML = "";
				}
			} else if (score > 0) {
				upg_score = dojo.place("<span id='"+upg_score_id+"' class='gzs_upgrade_score'> (+"+score+")</span>",'player_score_'+player_id,'after');
			}
		},

		/** Override this function to inject html into log items. This is a built-in BGA method.  */
        /* @Override */
        format_string_recursive: function format_string_recursive(log, args) {
			//console.log("OVERRIDE format_string_recursive", log, args);
            try {
                if (log && args && !args.processed) {
                    args.processed = true;
                    // list of special keys we want to replace with images
                    var keys = ['vp_html','sphere_html','gizmo_html',
						'html_file', 'html_pick', 'html_research', 
						'c1_html', 'c2_html', 'to'];
                    for ( var i in keys) {
                        var key = keys[i];
						if (key in args) {
							var val;
							switch (key) {
								case 'vp_html':
									val = '<div class="gzs_log_vp"></div>';
									break;
								case 'c1_html':
								case 'c2_html':
									val = Energy.getHtmlForTooltip(args[key], this);
									break;
								case 'to':
									if (args[key] == 'any') {
										val = Energy.getHtmlForTooltip(args[key], this);
									} else {
										continue;
									}
									break;
								case 'sphere_html':
									val = "<div class='gzs_log_token gzs_log_"+args['sphere_color']+(Game.isColorblindFriendly(this) ? ' colorblind' : '')+"'></div>";
									break;
								case 'gizmo_html':
									val = "<div class='gzs_log_card gzs_log_card_"+args['purchased_card_id']+"'></div>";
									break;
								case 'html_file':
									if (args['can_file'] !== undefined && !args['can_file']) {
										val = {
											log: "<s>${file}</s>",
											args: {
												i18n: ['file'],
												file: 'File'
											}
										};
									} else {continue;}
									break;
								case 'html_pick':
									if (args['can_pick'] !== undefined && !args['can_pick']) {
										val = {
											log: "<s>${pick}</s>",
											args: {
												i18n: ['pick'],
												pick: 'Pick'
											}
										};
									} else {continue;}
									break;
								case 'html_research':
									if (args['can_research'] !== undefined && !args['can_research']) {
										val = {
											log: "<s>${research}</s>",
											args: {
												i18n: ['research'],
												research: 'Research'
											}
										};
									} else {continue;}
									break;
							default:
									val = "UNRECOGNIZED["+key+"]";
									break;
							}
							args[key] = val;
						}
                    }
                }
            } catch (e) {
                console.error(log,args,"Exception thrown", e.stack);
            }
			//console.log("after: ", log, args);
            return this.inherited({callee: format_string_recursive}, arguments);
        },
		/**
         * This method will attach mobile to a new_parent without destroying, unlike original attachToNewParent which destroys mobile and
         * all its connectors (onClick, etc)
         */
		attachToNewParentNoDestroy: function (mobile_in, new_parent_in, relation, place_position) {
			const mobile = $(mobile_in);
			const new_parent = $(new_parent_in);

			var src = dojo.position(mobile);
			if (place_position)
				mobile.style.position = place_position;
			dojo.place(mobile, new_parent, relation);
			mobile.offsetTop;//force re-flow
			var tgt = dojo.position(mobile);
			var box = dojo.marginBox(mobile);
			var cbox = dojo.contentBox(mobile);
			var left = box.l + src.x - tgt.x;
			var top = box.t + src.y - tgt.y;

			mobile.style.position = "absolute";
			mobile.style.left = left + "px";
			mobile.style.top = top + "px";
			box.l += box.w - cbox.w;
			box.t += box.h - cbox.h;
			mobile.offsetTop;//force re-flow
			return box;
		},		
		placeInZoneNoDestroy: function( id, weight )
		{
			console.log( "placeInZoneNoDestroy: "+id );
			if( typeof weight == 'undefined' )
			{   weight = 0; }
			
			if( this.isInZone( id) )
			{   return ;    }   // Already in zone

			this.items.push( {id:id, weight:weight} );
			
			this.page.attachToNewParentNoDestroy( $( id ), this.container_div );
			
			var sort_function = function(a,b) { 
									if( a.weight>b.weight ) {   return 1;   }
									else if( a.weight<b.weight ) {   return -1;   }
									else { return 0; }
								};
			this.items.sort( sort_function );				
			this.updateDisplay();        
		},
		onPreferenceChange(prefId, prefValue) {
			prefId = parseInt(prefId);
			if (prefId == 202) { // Auto-Pass Unusable Triggers
				this.ajaxcall( "/gizmos/gizmos/updatePlayerPref.html", {
					pref_id: prefId,
					pref_val: prefValue
				}, this, function( result ) {} );				
			}
		},
		initPreferencesObserver: function () {      
			// Call onPreferenceChange() when any value changes
			dojo.query('.preference_control').on('change', (e) => {
				const match = e.target.id.match(/^preference_[cf]ontrol_(\d+)$/);
				if (!match) {
					return;
				}
				const pref = match[1];
				const newValue = e.target.value;
				this.prefs[pref].value = newValue;
				this.onPreferenceChange(pref, newValue);
			});
		},
		selectDeck: function (level) {
			if (this.checkAction( 'deckSelected' )) 
			{
				this.ajaxcall( "/gizmos/gizmos/cardSelected.html", {
					selected_card_id: level,
					lock: true
				}, this, function( result ) {			
				} );				
			}
		},
		selectDeck1: function () {
			this.selectDeck(1);
		},
		selectDeck2: function () {
			this.selectDeck(2);			
		},
		selectDeck3: function () {
			this.selectDeck(3);			
		},
		drawEnergy: function() {
			if (this.checkAction( "triggerSphereRandom" )) {
				this.ajaxcall( "/gizmos/gizmos/draw.html", {lock: true}, this, function( result ) {} );
			}			
		},
		showResearch: function() {
			if (!Game.isResearching) {
				if (Number.isInteger(this.r_gizmos)) {
					// not active - show face down cards
					let gizmoDetails = {
						'level': this.r_level,
						'other_class': 'researched'
					};
					for (var i=0; i<this.r_gizmos; i++) {
						dojo.place( this.format_block('jstpl_fd_card', gizmoDetails), 'researched_gizmos' );					
					}
				} else {
					let gids = Object.keys(this.r_gizmos);
					let rgs = this.r_gizmos;
					console.log('showResearch', gids, rgs);
					gids.sort((a,b) => rgs[b].card_location_arg - rgs[a].card_location_arg);
					console.log('afterSort:', gids);
					for (var i=0; i<gids.length; i++) {
						this.placeResearchedGizmo(gids[i]);
					}
					dojo.query('.gzs_arrow_right').connect( 'onclick', this, 'onArrowRight' );
					dojo.query('.gzs_arrow_left').connect( 'onclick', this, 'onArrowLeft' );
				}
				dojo.style('research_outer', 'display', 'block');
				Game.repositionEnergyRing();
				Builder.handleButtonDisabled();
				Game.isResearching = true;
			}
			if (Game.selected_card_id > 0 && $(Gizmo.getEleId(Game.selected_card_id)) ) {
				dojo.addClass( Gizmo.getEleId(Game.selected_card_id), 'selected' );
			} else {
				dojo.query('.selected').removeClass('selected');
			}
		},
		onArrowRight: function( evt ) {
			let gizmo_ele = evt.target.parentNode;
			let research_div = gizmo_ele.parentNode;
			let research_gizmos = research_div.children;
			let nthChild = Array.prototype.indexOf.call(research_gizmos, gizmo_ele);
			if (nthChild+1 < research_gizmos.length) {
				dojo.place(gizmo_ele, research_gizmos[nthChild+1], 'after');
			} else {
				// If last, move to beginning
				dojo.place(gizmo_ele, research_gizmos[0], 'before');
			}
		},
		onArrowLeft: function( evt ) {
			let gizmo_ele = evt.target.parentNode;
			let research_div = gizmo_ele.parentNode;
			let research_gizmos = research_div.children;
			let nthChild = Array.prototype.indexOf.call(research_gizmos, gizmo_ele);
			if (nthChild > 0) {
				dojo.place(gizmo_ele, research_gizmos[nthChild-1], 'before');
			} else {
				// If last, move to beginning
				dojo.place(gizmo_ele, research_gizmos[research_gizmos.length-1], 'after');
			}
		},
		placeResearchedGizmo: function(gizmo_id) {
			let mt_gizmo = this.gamedatas.mt_gizmos[gizmo_id];
			var other_class = '';
			let gizmoDetails = {
				'id': gizmo_id,
				'level': mt_gizmo.level,
				'other_class': other_class
			};
			dojo.place( this.format_block('jstpl_research_card', gizmoDetails), 'researched_gizmos' );
			dojo.connect( $('card_' + gizmo_id), 'onclick', this, 'onCardSelect' );
			this.addGizmoTooltip(gizmo_id);
		},
		passResearch: function() {
			this.pass(
				_("Are you sure you want to pass without building or filing a card?")
			);
		},
		passTriggers: function() {
			this.pass(
				Game.hasUsableGizmos() ? _("Are you sure you want to pass without using your triggered gizmo(s)?") : undefined
			);			
		},
		passTurn: function() {
			this.pass(
				_("Are you sure you want to pass without building (may not be possible)?")
			);
		},
		pass: function(msg) {
			if (this.checkAction( "pass" )) {
				if (!msg) {
					this.ajaxcall( "/gizmos/gizmos/pass.html", {lock: true, research_order: Game.getOrderedResearch()}, this, function( result ) {} );	
				} else {
					this.confirmationDialog(msg, () => {
						this.ajaxcall( "/gizmos/gizmos/pass.html", {lock: true, research_order: Game.getOrderedResearch()}, this, function( result ) {} );					
					});
				}
			}			
		},
		insertNextSphere: function( sphere_id ) {
			let sphere_ele = Energy.getEnergyHtml(sphere_id, null, this);
			dojo.place( sphere_ele, 'sphere_row' );
			dojo.addClass( Energy.getEleId(sphere_id), 'next_nrg' );
			this.addTooltip( Energy.getEleId(sphere_id), this.format_string_recursive(Const.Tooltip_Next_Energy(), {i18n: ['color'], color: Energy.getColor(sphere_id)}), '' );
			dojo.attr( Energy.getEleId(sphere_id), 'style', '');		
		},
		insertSphereInRow: function ( sphere_id ) {
			let sphere_ele = Energy.getEnergyHtml(sphere_id, null, this);
			dojo.place( sphere_ele, 'sphere_row' );
			this.placeInZoneNoDestroy.call(Game.zones['sphere_row'], Energy.getEleId(sphere_id), Game.getNrgWeight() );
			this.addTooltip( Energy.getEleId(sphere_id), '', this.format_string_recursive(Const.Tooltip_Row_Energy(), {i18n: ['color'], color: Energy.getColor(sphere_id)}));
		},
		spendSpheresAndRebuildPlayerCard: function (player_id, spheres) {
			if (spheres) {
				let arrSpheres = spheres.split(',');
				for (var i=0; i<arrSpheres.length; i++) {
					let spid = arrSpheres[i];
					Builder.decrementSphereCount(player_id, spid);
					// place a sphere in player card then drag
					if (player_id == this.player_id) {
						let sp_ele_id = Energy.getEleId(spid);
						Game.zones['energy_ring'].removeFromZone(sp_ele_id);
					} else {
						dojo.place( Energy.getEnergyHtml(spid, null, this), $('player_board_'+player_id) );		
					}		
					this.slideToObjectAndDestroy( $('sphere_'+spid), $('dispenser') );
				}
			}
			this.buildPlayerCard(player_id);			
		},
		getPlayerVpCount: function( pid ) {
			return this.gamedatas.players[pid].victory_points;
		},
		getPlayerSphereCount: function( player, color ) {
			if (!Builder.sphere_counts || !Builder.sphere_counts[player]) {
				return 0;
			} else if (!color || color == 'all') {
				return Builder.sphere_counts[player].spheres.length;
			} else if (!Builder.sphere_counts[player][color]) {
				return 0;
			} else {
				return Builder.sphere_counts[player][color];
			}
		},
		initSphereRowAndPlayerCards: function(gamedatas) {
			Game.zones['sphere_row'] = new ebg.zone();
			Game.zones['sphere_row'].create( this, 'sphere_row', 50, 34);
			Game.zones['sphere_row'].setPattern( 'horizontalfit' );

			let spheres = this.gamedatas.spheres;
			Builder.reinitSphereCounts(this.gamedatas.players, spheres, this);
            
            // Setting up player boards
            for( var player_id in this.gamedatas.players )
            {
				this.buildPlayerCard(player_id);
				if (player_id == this.player_id) {
					// init energy ring
					let ring_id = 'energy_ring';
					Game.zones[ring_id] = new ebg.zone();
					Game.zones[ring_id].create( this, ring_id, 30, 30 );
					Game.zones[ring_id].setPattern( 'ellipticalfit' );
					//console.log("init zone for " + ring_id + "; player=" + this.player_id);
						
					let p_spheres = Builder.sphere_counts[player_id].spheres;
					//console.log(p_spheres);
					for (var i=0; i<p_spheres.length; i++) {
						let spid = p_spheres[i];
						this.addSphereToRing(spid);
					}					
				}				
				if (this.gamedatas.players[player_id].player_no == '1') {
					dojo.place('<span id="gzs_first_player">'+_('1st')+'</span>', 'icon_point_'+player_id, 'after' );
					this.addTooltip('gzs_first_player', _('This player went first'), '');
				}
            }
			this.setUpgradeScores(this.gamedatas.upgrade_scores);
				
		},
		addSphereToRing: function(spid, isConnect) {			
			let sphere = Energy.getEnergyHtml(spid, 'ring', this);
			dojo.place( sphere, 'energy_ring' );
			this.placeInZoneNoDestroy.call( Game.zones['energy_ring'], Energy.getEleId(spid) );			
			this.addTooltip( Energy.getEleId(spid), '', this.format_string_recursive(Const.Tooltip_Ring_Energy(), {i18n: ['color'], color: Energy.getColor(spid)}));
			if (isConnect) {				
				dojo.connect( Energy.getEleId(spid), 'onclick', this, 'onEnergySelect');
			}
		},
		buildPlayerCard: function (player_id) {
			// If tokens div already exists, destroy it to rebuild
			var player_tokens_div = $('token_counts_' + player_id);
			if (player_tokens_div) {
				dojo.destroy(player_tokens_div);
			}
			
			let totalNrg = this.getPlayerSphereCount(player_id, 'all');
			let limitNrg = this.gamedatas.players[player_id].energy_limit;
			let totalArch = dojo.query('#gizmos_container_'+player_id+' .filed').length;
			let limitArch = this.gamedatas.players[player_id].archive_limit;
			dojo.place( this.format_block('jstpl_player_board', {
				'id': player_id,
				'red_count': this.getPlayerSphereCount(player_id, 'red'), 
				'black_count': this.getPlayerSphereCount(player_id, 'black'), 
				'blue_count': this.getPlayerSphereCount(player_id, 'blue'), 
				'yellow_count': this.getPlayerSphereCount(player_id, 'yellow'),
				'all_count': totalNrg,
				'energy_limit': limitNrg,
				'energy_full': totalNrg >= limitNrg ? 'full' : '',
				'vp_count': this.getPlayerVpCount(player_id),
				'gizmos_all': dojo.query('#gizmos_container_'+player_id+' .card.built').length,
				'gizmos_3s': dojo.query('#gizmos_container_'+player_id+' .card_3.built').length,
				'archive': totalArch,
				'archive_limit': limitArch,
				'archive_full': totalArch >= limitArch ? 'full' : '',
				'research': this.gamedatas.players[player_id].research_quantity,
				'colorblind': (Game.isColorblindFriendly(this) ? 'colorblind' : '')
			}), $('player_board_'+player_id) );

			if (player_id == this.player_id) {
				$('ring_count').innerHTML = totalNrg + '/' + limitNrg;
				if (totalNrg >= limitNrg) {
					dojo.addClass('ring_count', 'full');
				} else {
					dojo.removeClass('ring_count', 'full');
				}
			}

			// add tooltips
			for (var i=0; i<4; i++) {
				let color = Energy.colors[i];
				this.addTooltip("pair_"+player_id+"_"+color, this.format_string_recursive( _('Number of ${color} energy'), {i18n:['color'], color: color}), '' );
			}
			this.addTooltip( dojo.string.substitute("pair_${pid}_all",{pid:player_id}), _("Total energy / limit"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_vps",{pid:player_id}), _("Number of victory point tokens"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_allgs",{pid:player_id}), _("Number of built Gizmos"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_3gs", {pid:player_id}), _("Number of built Level III Gizmos"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_archive",{pid:player_id}), _("Number of Gizmos filed / limit"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_research",{pid:player_id}), _("Research quantity"), '' );
		},
		buildSelectedCard: function ( evt ) {
			if ( this.checkAction("cardBuilt")
				&& Builder.validateSpending(this) ) {
				let s_spheres = Builder.selected_spheres.join(',');
				console.log(s_spheres);
				let s_converters = JSON.stringify( Builder.active_converters );
				console.log(s_converters);
				this.ajaxcall( "/gizmos/gizmos/buildSelectedCard.html", {
					"spheres": s_spheres,
					"converters": s_converters,
					research_order: Game.getOrderedResearch(),
					lock: true
				}, this, function( result ) {} );			
			}			
		},
		buildLevel1For0: function ( evt ) {
			this.ajaxcall( "/gizmos/gizmos/buildLevel1For0.html", {
				"gizmo_id": Game.selected_card_id,
				lock: true
			}, this, function( result ) {} );
		},
		researchSelectedDeck: function ( evt ) {
			let deck_count = Game.deck_counts['deck_'+Game.selected_card_id];
			if (deck_count == 0) {
				this.showMessage(_('No Gizmos left to Research!'), 'error');
			} else {
				let research_quantity = this.gamedatas.players[this.player_id].research_quantity;
				console.log("researchSelectedDeck", research_quantity, deck_count);
				if (research_quantity <= deck_count) {			
					if ( this.checkAction("research") ) {				
						this.ajaxcall( "/gizmos/gizmos/research.html", {lock: true}, this, function( result ) {} );
					}
				} else {
					let confirmMsg = this.format_string_recursive(_('Your research quantity is ${research_quantity} but there are only ${deck_count} Gizmos left in the deck.  Would you still like to research?'), {
						research_quantity: research_quantity, 
						deck_count: deck_count
					});
					this.confirmationDialog(confirmMsg, () => {			
						if ( this.checkAction("research") ) {				
							this.ajaxcall( "/gizmos/gizmos/research.html", {lock: true}, this, function( result ) {} );
						}
					});
				}
			}
		},
		fileSelectedCard: function ( evt ) {
			if ( this.checkAction("cardFile") ) {
				Builder.deselectAllConverters(this);
				// Archive limit checked server-side		
				this.ajaxcall( "/gizmos/gizmos/fileSelectedCard.html", {
					lock: true,
					"selected_card_id": Game.selected_card_id ?? 0,
					research_order: Game.getOrderedResearch()
				}, this, function( result ) {} );
			}
		},
		cancel: function ( evt ) {
			this.cancelSelectedCard(evt);
		},
		cancelSelectedCard: function ( evt ) {
			Builder.deselectAllConverters(this);
			dojo.query('.token .selected').removeClass('selected');
			if (this.checkAction( "cancel" )) {
                this.ajaxcall( "/gizmos/gizmos/cancel.html", {
					lock: true,
					research_order: Game.getOrderedResearch()
                }, this, function( result ) {} );				
			}
		},
		
		onCardSelectTrigger: function ( evt ) {
			this.ajaxcall( "/gizmos/gizmos/triggerSelected.html", {
				selected_card_id: Gizmo.getIdOfEle(evt.target.id),
				lock: true
			}, this, function( result ) {			
			} );							
		},
		
		addGizmoToRow: function (gizmo_id, row_div, level) {
			var gizmoDetails = {
				'id': gizmo_id,
				'level': level,
				'other_class': 'row_card row_' + level
			};
			dojo.place( this.format_block('jstpl_card', gizmoDetails), row_div );
			this.addGizmoTooltip(gizmo_id);	
			dojo.connect($(Gizmo.getEleId(gizmo_id)), 'onclick', this, 'onCardSelect');	
		},
		addGizmoTooltip: function(gizmo_id) {
			//console.log("adding tooltip:", this.gamedatas.mt_gizmos[gizmo_id].tooltip);
			this.addTooltipHtml( Gizmo.getEleId(gizmo_id), 
				this.format_block('jstpl_cardTooltip', {
					"id": gizmo_id,
					"tooltip": this.format_string_recursive( 
						this.gamedatas.mt_gizmos[gizmo_id].tooltip.log,
						this.gamedatas.mt_gizmos[gizmo_id].tooltip.args 
					)
				}) 
			);				
		},
		
		getPlayerFiledDiv: function(player_id) {
			return $('archive_'+player_id);			
		},
		handleSelectedCard: function() {			
			if (Game.selected_card_id > 0) {
				let ele_id = Gizmo.getEleId(Game.selected_card_id);
				dojo.addClass(ele_id, 'selected');
				if (Game.selected_card_id > 100) {
					let mt_card = this.gamedatas.mt_gizmos[Game.selected_card_id];
					Builder.spend_spheres[mt_card.color] = mt_card.cost;
				}
			}
		},
		
		addGizmoToPlayerCard: function(card, player_id, was_filed, other_class, div_for_this) {
			console.log('addGizmoToPlayerCard');
			console.log(card);
			var gizmo_id;
			if (typeof card == 'string') {
				gizmo_id = card;
			} else {
				gizmo_id = card['type_arg'];
			}
			
			if (!div_for_this) {
				if (was_filed) {
					div_for_this = this.getPlayerFiledDiv(player_id);
				} else {
					div_for_this = $( Game.getBuiltGizmoDiv(gizmo_id, player_id) );
					other_class = '';
				}
			}
			var mt_gizmo = this.gamedatas.mt_gizmos[gizmo_id];
			if (card.is_used == '1') {
				other_class += ' already_used';
			}
			if (!was_filed) {
				if (mt_gizmo && mt_gizmo['effect_type'] == 'converter' ) {
					other_class += ' converter';
				}
				if (Gizmo.isDiscountUpgrade(gizmo_id)) {
					other_class += ' discount';
				}
				other_class += ' built';
			}
			
			var gizmoDetails = {
				'id': gizmo_id,
				'level': mt_gizmo.level,
				'other_class': other_class
			};
			dojo.place( this.format_block('jstpl_card', gizmoDetails), div_for_this );
			this.addGizmoTooltip(gizmo_id);
			let new_gizmo_id = Gizmo.getEleId(gizmo_id);
			this.placeInZoneNoDestroy.call( Game.zones[div_for_this.id], new_gizmo_id );
			console.log("placed " + new_gizmo_id + " in zone: " + div_for_this);
			dojo.connect($(new_gizmo_id), 'onclick', this, 'onCardSelect');
			console.log("and connected onCardSelect");			
		},
		setupDeckTooltips: function() {
			for (var level=1; level<=3; level++) {
				this.addTooltipHtml( 'deck_' + level, '<div id="deck_tooltip_${level}" style="text-align:center">'+
					this.format_string_recursive(_('Click to Research Level ${level}'), {"level": Gizmo.levelNumerals(level)})+'</div>' );				
			}
			Game.updateDeckCounts(this.gamedatas.deck_counts);
		},
		setupCards: function ( gamedatas ) {
			let gizmo_cards = gamedatas.gizmo_cards;
			for (var level = 1; level<=3; level++) {
				let l_gizmos = gizmo_cards[level];
				
				var l_row_div = $('row_' + level);
				dojo.place( this.format_block('jstpl_deck', {"level": level}), l_row_div );
				for (var x in l_gizmos) {
					var gizmo = l_gizmos[x];
					this.addGizmoToRow(gizmo.type_arg, l_row_div, level);
				}		
			}
			this.setupDeckTooltips();
			
			// GOAL: show the current player above the board.  Show other players in turn order under the board
			// Players are returned from gamedatas in a "random" order because it's an object, not an array
			var sorted_player_ids = [];
			var player_count = Object.keys(this.gamedatas.players).length;
			
			for (var i=1; i<=player_count; i++) {
				for( var player_id in this.gamedatas.players ) {
					var p = this.gamedatas.players[player_id];
					if (p.player_no == i) {
						sorted_player_ids.push(player_id);
						break;
					}
				}				
			}
			
			var currentFound = false;
			var beforeCurrent = [];
			var afterCurrent = [];
            for ( var i=0; i<sorted_player_ids.length; i++ ) {
				var player_id = sorted_player_ids[i];
				if (currentFound) { 
					afterCurrent.push(player_id);
				} else if (player_id == this.player_id) {
					console.log("Setup current player:");
					this.setupPlayerGizmos(this.player_id,'current_player_gizmos',i==0);
					currentFound = true;
				} else {
					beforeCurrent.push(player_id);					
				}
            }
			console.log("setting up otherPlayers: ");
			console.log(afterCurrent);
			console.log(beforeCurrent);
			var otherPlayers = afterCurrent.concat(beforeCurrent);
			console.log(otherPlayers);
            for( var i=0; i<otherPlayers.length; i++ ) {
				var pid = otherPlayers[i];
				var p = this.gamedatas.players[pid];
				this.setupPlayerGizmos(pid,'player_gizmos',p.player_no == '1');					
			}
		},
		setZoneHeight: function(zone_id, num_cards, player_id) {
			var height;
			if (zone_id == Game.getPlayerArchive(player_id)) {
				height = this.card_height*num_cards/1.5;
			} else {
				height = this.card_height*((1-Game.stack) + Game.stack*num_cards);
			}
			dojo.style(zone_id, 'min-height', height+"px");
			console.log("setZoneHeight( " + zone_id + ", " + num_cards + ") => " + height);
			var playerColsDiv = $('gizmos_columns_' + player_id);
			var pcolsHeight = parseFloat( playerColsDiv.style.minHeight );
			console.log("comparing to total columns height: " + pcolsHeight);
			if ( height > pcolsHeight ) {
				playerColsDiv.style.minHeight = height+"px";
				if (player_id == this.player_id) {	
					Game.repositionEnergyRing();
				}				
				console.log(height + " > " + pcolsHeight + " ->>> updated");
			} else {
				console.log("already greater => left alone");
			}
		},
		setupPlayerGizmos: function(player_id, div_id, is_first) {

			let gizmo_columns = ['upgrade','converter','trigger_file','trigger_pick','trigger_build'];
			let gizmo_cards = this.gamedatas.gizmo_cards;
			let player = this.gamedatas.players[player_id];
			console.log(player);
			// create player-specific div
			dojo.place( this.format_block('jstpl_gizmos_container', {
				"id": player_id, 
				"name": player.name,
				"class": player_id == this.getActivePlayerId() ? 'active_player' : '',
				"first": is_first ? '_first' : '',
				"color": player.color,
				"archive_translated": _('Archive')
			}), $(div_id) );	
			var playerColsDiv = $('gizmos_columns_' + player_id);
			var colsWidth = playerColsDiv.offsetWidth;
			this.card_height = 0.155 * colsWidth;
			this.card_width = this.card_height;
			for (var i=0; i<gizmo_columns.length; i++) {
				var col = gizmo_columns[i];
				dojo.place( this.format_block('jstpl_gizmos_column', {
					"id": player_id, 
					"col": col
				}), playerColsDiv );
				var placed_id = col + "_" + player_id;
				Game.zones[placed_id] = new ebg.zone();
				Game.zones[placed_id].create( this, placed_id, this.card_width, this.card_height );
				Game.zones[placed_id].setPattern( 'verticalfit' );					
			}
			console.log(player.name + " (" + player_id + ") | ALL Cards: ");
			console.log(gizmo_cards[player_id]);
			var maxCardsCol = 0;
			if (gizmo_cards[player_id] && gizmo_cards[player_id].built_by_type) {
				var built_by_type = gizmo_cards[player_id].built_by_type;
				for (var gtype in built_by_type) {
					var zone_id = gtype + "_" + player_id;
					var cardsOfType = built_by_type[gtype];
					dojo.style(zone_id, 'min-height', (this.card_height*((1-Game.stack) + Game.stack*cardsOfType.length))+"px");
					//this.setZoneHeight( zone_id, cardsOfType.length, player_id );
					for (var card_id in cardsOfType) {
						var card = cardsOfType[card_id];
						var gizmo_id = card.type_arg;
						this.addGizmoToPlayerCard(card, player_id, false);
						this.addGizmoTooltip(gizmo_id);
					}
					if (cardsOfType.length > maxCardsCol) {
						maxCardsCol = cardsOfType.length;
					}
				}
			}			
			
			// populate filed/archive
			let fileds = gizmo_cards[player_id]?.filed;
			dojo.place( this.format_block('jstpl_gizmos_column', {
				"id": player_id, 
				"col": "archive"
			}), playerColsDiv );
			let archive_id = Game.getPlayerArchive(player_id);
			Game.zones[archive_id] = new ebg.zone();
			Game.zones[archive_id].create( this, archive_id, this.card_width/2, this.card_height/1.5 );
			Game.zones[archive_id].setPattern( 'verticalfit' );			
			if (fileds) {
				dojo.style(archive_id, 'min-height', (this.card_height*fileds.length/1.5)+"px");
				for (var card_id in fileds) {
					let card = fileds[card_id];
					this.addGizmoToPlayerCard(card, player_id, true, 'selectable filed', $(archive_id));
					this.addGizmoTooltip(card.type_arg);
				}
				if ( fileds.length/2 > maxCardsCol ) {
					maxCardsCol = fileds.length / 2;
				}
			}
			
			/* Goal: show the top 25% of each card so effect is readable
			X cards
			1*H + (X-1)*0.25*H = 
			H + 0.25HX - 0.25H = 
			0.75H + 0.25HX =
			H(0.75 + 0.25X)
			This makes the cards stack/overlap nicely
			*/
			var minHeight = ((1-Game.stack) + Game.stack*maxCardsCol) * this.card_height;
			//console.log("Calculating cols min-height: this.card_height=" + + this.card_height + "; maxCardsCol=" + maxCardsCol + "; RESULT=" + minHeight);
			playerColsDiv.style.minHeight = minHeight+"px";
			if (player_id == this.player_id) {	
				Game.repositionEnergyRing();
			}
		},
		


        ///////////////////////////////////////////////////
        //// Player's action
        
        /*
        
            Here, you are defining methods to handle player's action (ex: results of mouse click on 
            game objects).
            
            Most of the time, these methods:
            _ check the action is possible at this game state.
            _ make a call to the game server
        
        */
		
		onEnergySelect: function( evt ) {
			if (Game.isLocked() || dojo.hasClass(evt.target.id, 'next_nrg')) {
				console.log('onEnergySelect LOCKED');
				return;
			} else {
				Game.action_lock = true;
			}
			console.log('onEnergySelect', evt.target);
			let sphere_id = Energy.getIdOfEle(evt.target.id);
			if (evt.target.classList.contains('convert_from')) { 
				let gizmo_id = evt.target.parentNode.id;
				console.log(Builder.active_converters);
				Builder.deselectConverter( Gizmo.getIdOfEle(gizmo_id), this );
			} else if (evt.target.classList.contains('ring')) {
				if (Builder.picking > 0) {
					Builder.spendEnergy( sphere_id );
					Builder.toggleConverter( Builder.picking, this, sphere_id );
				} else if (evt.target.classList.contains('selected')) {
					Builder.despendEnergy( sphere_id );
					Builder.refreshHeader(this);
				} else if (Game.selected_card_id > 100) {
					// Do not allow selecting wrong color
					let sel_color = Gizmo.details(Game.selected_card_id).color;
					let nrg_color = Energy.getColor(sphere_id);
					if (sel_color != 'multi' && sel_color != nrg_color) {						
						this.showMessage( dojo.string.substitute( _("Cannot use ${nrg_color} energy to build ${sel_color} gizmo"), {
							nrg_color: nrg_color,
							sel_color: sel_color
						} ), 'error' );
					} else {
						Builder.spendEnergy( sphere_id );
						Builder.refreshHeader(this);
					}
				}
			} else if (evt.target.classList.contains('picker')) {
				if (Builder.picking > 0 && Builder.validateConvertColor(evt.target.id, this)) {
					let nrgEle = evt.target;
					this.attachToNewParentNoDestroy( nrgEle.id, Gizmo.getEleId(Builder.picking) );
					dojo.addClass( nrgEle.id, 'convert_to' );
					dojo.removeClass( nrgEle.id, 'picker' );
					let anim = this.slideToObjectPos( evt.target.id, Gizmo.getEleId(Builder.picking), this.card_height-50);
					anim.onEnd = function(parent) {
						return function() {
							dojo.attr(nrgEle.id, 'style', 'position:absolute;');
							Builder.applyColorConverter( Builder.picking, null, Energy.getEleColor(nrgEle), null, parent );
							Energy.hidePicker(parent);
							Builder.temp_energy.push(nrgEle.id);
							Game.anim_lock = false;
						}
					}(this);
					Game.anim_lock = true;
					anim.play();
				}
			} else if (evt.target.classList.contains('convert_to')) {
				if (Builder.picking > 0) {
					Builder.toggleConverter( Builder.picking, this, evt.target.id );
				}
			} else if (this.checkAction( "sphereSelect" )) {
				// ensure user is not at limit
				let sphere_count = this.getPlayerSphereCount(this.getActivePlayerId());
				if (sphere_count >= this.gamedatas.players[this.getActivePlayerId()].energy_limit) {
					this.showMessage(_("You cannot hold more energy"), "error");
				} else {
					Game.selectEnergy(sphere_id);
					if (Game.isShowEnergyConfirm(this)) {
						Game.showEnergyConfirm(this);
					} else {
						this.doPickEnergy();
					}					
				}
			} else {
				//console.log("sphereSelect not allowed in this state");
			}
			Game.action_lock = false;
		},
		doPickEnergy: function( evt ) {
			this.ajaxcall( "/gizmos/gizmos/sphereSelect.html", {
				'sphere_id': Game.selected_energy,
				lock: true
			}, this, function( result ) {
			} );
			Game.deselectEnergy();
		},
		cancelPickEnergy: function( evt ) {
			Game.deselectEnergy();
			Game.resetDescription(this);
		},
		onCardSelect: function( evt ) {
			if (Game.isLocked() || !this.checkLock(false)) {
				console.log("onCardSelect LOCKED");
				return;
			} else {
				Game.action_lock = true;
			}
			console.log("Selected a card: " + Game.stateName);
			//console.log(evt);
			
			let card_ele = evt.target;
			let selected_card_id = Gizmo.getIdOfEle(card_ele.id);

			if (Game.stateName == 'buildLevel1For0') {
				if (dojo.hasClass(card_ele.id, 'built')) {
					this.showMessage( _("Cannot build an already built card!"), "error");
				} else if (selected_card_id < 100 || selected_card_id > 200) {
					this.showMessage( _("Must select a Level I Gizmo"), "error" );
				} else {				
					if (Game.selected_card_id == selected_card_id) {
						Game.selected_card_id = 0;
						dojo.removeClass(card_ele.id, 'selected');
						dojo.addClass('button_build', 'disabled');
					} else {
						if (Game.selected_card_id > 0) {
							dojo.removeClass(Gizmo.getEleId(Game.selected_card_id), 'selected');
						}
						Game.selected_card_id = selected_card_id;
						dojo.addClass(card_ele.id, 'selected');
						dojo.removeClass('button_build', 'disabled');
					}
				}
			} else if (Game.stateName == 'triggerFile') {
				if (Gizmo.getIdOfEle(card_ele.id) < 100) {
					// Do nothing - this is a deck and cannot be filed
				} else if (dojo.hasClass(card_ele.id, 'built')) {
					this.showMessage( _("Cannot File an already built card!"), "error");
				} else if (dojo.hasClass(card_ele.id, 'filed')) {
					this.showMessage( _("Cannot File an already filed card!"), "error" );
				} else {				
					if (Game.selected_card_id == selected_card_id) {
						Game.selected_card_id = 0;
						dojo.removeClass(card_ele.id, 'selected');
						dojo.addClass('button_file', 'disabled');
					} else {
						if (Game.selected_card_id > 0) {
							dojo.removeClass(Gizmo.getEleId(Game.selected_card_id), 'selected');
						}
						Game.selected_card_id = selected_card_id;
						dojo.addClass(card_ele.id, 'selected');
						dojo.removeClass('button_file', 'disabled');
					}
				}
			} else if ( card_ele.classList.contains('already_used') ) {
				this.showMessage( _('Already used this turn'), 'error' );
			} else if (card_ele.classList.contains('triggerable')) {
				this.onCardSelectTrigger(evt);
			} else if (card_ele.classList.contains('discount')) {
				if (card_ele.classList.contains('selectable')) {
					if (Builder.picking > 0) {
						Builder.deselectConverter(Builder.picking, this);
					}
					if (card_ele.classList.contains('selected')) {
						dojo.removeClass(card_ele.id, 'selected');
						Builder.discount--;
						Builder.refreshHeader(this);
					} else {
						dojo.addClass(card_ele.id, 'selected');
						Builder.discount++;
						Builder.refreshHeader(this);
					}
				} else {
					this.showMessage(_("Discount does not apply to selected Gizmo"), "error");
				}
			} else if (card_ele.classList.contains('selectable')) {
				var checkAction;
				if (card_ele.classList.contains('deck')) {
					checkAction = 'deckSelected';
				} else {
					checkAction = 'cardSelected';					
				}
				//console.log("checking action[" + checkAction + "] for card: " + card_ele.id);
				if (this.checkAction( checkAction )) 
				{
					//console.log('action allowed => ajax');
					this.ajaxcall( "/gizmos/gizmos/cardSelected.html", {
						selected_card_id: selected_card_id,
						research_order: Game.getOrderedResearch(),
						lock: true
					}, this, function( result ) {			
					} );				
				}
			} else if (card_ele.classList.contains('converter') && dojo.query('#gizmos_container_'+Game.activePlayer+' #'+card_ele.id).length > 0) {
				if ( this.checkAction("cardBuilt", true) ) {
					Builder.toggleConverter(selected_card_id, this);
				} else {
					this.showMessage(_('Must select a Gizmo before converting'), 'error');
				}
			}
			Game.action_lock = false;
		},

        
        ///////////////////////////////////////////////////
        //// Reaction to cometD notifications

        /*
            setupNotifications:
            
            In this method, you associate each of your game notifications with your local method to handle it.
            
            Note: game notification names correspond to "notifyAllPlayers" and "notifyPlayer" calls in
                  your gizmos.game.php file.
        
        */
        setupNotifications: function()
        {
            //console.log( 'notifications subscriptions setup' );
			//dojo.subscribe( 'cardOrDeckSelected', this, "notif_cardOrDeckSelected" );
			dojo.subscribe( 'sphereSelect', this, "notif_sphereSelect" );
			dojo.subscribe( 'cardBuiltOrFiled', this, "notif_cardBuiltOrFiled" );
			dojo.subscribe( 'sphereDrawn', this, "notif_sphereDrawn" );
			dojo.subscribe( 'victoryPoint', this, "notif_victoryPoint" );
			dojo.subscribe( 'research', this, "notif_research" );
			dojo.subscribe( 'lastTurn', this, "notif_lastTurn" );
			dojo.subscribe( 'scoreSpecial', this, "notif_scoreSpecial" );
        },  
        
		notif_sphereSelect: function( notif ) {
			let sphere_id = notif.args.purchased_sphere_id;
			var player_id = notif.args.player_id;
			let sp_ele_id = Energy.getEleId(sphere_id);
			// Increment player's spheres
			Builder.incrementSphereCount(player_id, sphere_id);			
			if (this.player_id == player_id) {
				dojo.addClass(sp_ele_id, 'ring');
				Game.zones['sphere_row'].removeFromZone(sp_ele_id);
				this.attachToNewParentNoDestroy( sp_ele_id, 'energy_ring' );
				let anim = this.slideToObject( sp_ele_id, 'energy_ring' );
				anim.onEnd = function(parent) {
					return function() {
						parent.placeInZoneNoDestroy.call( Game.zones['energy_ring'], sp_ele_id );
						Game.anim_lock = false;
					}
				}(this);
				Game.anim_lock = true;
				anim.play();
			} else {
				// slide sphere to player card
				Game.zones['sphere_row'].removeFromZone(sp_ele_id); //, false, 'energy_ring');
				this.slideToObjectAndDestroy( $(sp_ele_id), $('player_header_'+player_id) );				
			}
			
			$('token_counts_' + player_id).remove();
			this.buildPlayerCard(player_id);
			
			// Get next sphere ele
			let q = dojo.query('#sphere_row .next_nrg');
			if (!q || q.length == 0) {
				this.showMessage('Please refresh the page to load next energy', 'info');
			} else {
				let next_ele_id = q[0].id;
				this.removeTooltip( next_ele_id );
				dojo.removeClass(next_ele_id, 'next_nrg');
				// add to row zone (should work for animation)
				this.placeInZoneNoDestroy.call( Game.zones['sphere_row'], next_ele_id, Game.getNrgWeight());
				dojo.connect($(next_ele_id), 'onclick', this, 'onEnergySelect');
				this.addTooltip( next_ele_id, '', this.format_string_recursive(Const.Tooltip_Row_Energy(), {i18n: ['color'], color: Energy.getColor( Energy.getIdOfEle(next_ele_id) )}));
				let new_sphere_id = notif.args.new_sphere_id;
				this.insertNextSphere(new_sphere_id);
			}
			this.setUpgradeScore(player_id, notif.args.upgrade_score);
		},
		notif_cardBuiltOrFiled: function ( notif ) {
			Game.waitHideResearch = true;
			let purchased_id = notif.args.purchased_card_id;
			let action = notif.args.action;
			let player_id = notif.args.player_id;
			let spheres = notif.args.spent_spheres;
			let built_from_file = notif.args.built_from_file;
			let new_score = notif.args.new_score;
			let limits = notif.args.limits;
			let was_filed = notif.args.was_filed;

			Game.updateDeckCounts(notif.args.deck_counts);

			// update limits in gamedatas (if built, ignored for filing):
			if (limits) {
				this.gamedatas.players[player_id].energy_limit = limits['energy'];
				this.gamedatas.players[player_id].archive_limit = limits['archive'];
				this.gamedatas.players[player_id].research_quantity = limits['research'];
			}

			if (new_score > 0) {
				this.scoreCtrl[player_id].setValue( new_score );
			}
			
			// slide purchased card to player
			let pcid = Gizmo.getEleId(purchased_id);
			var mt_gizmo = Gizmo.details(purchased_id);
			let zone_id = (was_filed ?
				Game.getPlayerArchive(player_id) :
				Game.getBuiltGizmoDiv(purchased_id, player_id)
			);

			if (!$(pcid)) {
				// Cards are hidden for non-active player - need to place it first
				this.placeResearchedGizmo(purchased_id);
			}

			this.attachToNewParentNoDestroy( pcid, zone_id );

			let level = mt_gizmo.level;
			dojo.removeClass(pcid, 'selected');
			dojo.removeClass(pcid, 'row_card');
			dojo.removeClass(pcid, 'researched');
			dojo.removeClass(pcid, 'row_' + level);
			if (was_filed) {
				dojo.addClass(pcid, 'filed');
			} else {					
				dojo.removeClass(pcid, 'selectable');
				dojo.removeClass(pcid, 'filed');
				dojo.addClass(pcid, 'built');
				if (mt_gizmo.effect_type == 'converter') {
					dojo.addClass(pcid, 'converter');
				}
				if (Gizmo.isDiscountUpgrade(purchased_id)) {
					dojo.addClass(pcid, 'discount');
				}
			}
			if (built_from_file) {
				let archive_id = Game.getPlayerArchive(player_id);
				this.setZoneHeight( archive_id, Game.zones[archive_id].getItemNumber()-1, player_id );
				Game.zones[archive_id].removeFromZone( pcid );
			}
			this.setZoneHeight( zone_id, Game.zones[zone_id].getItemNumber()+1, player_id );
			let anim = this.slideToObject( pcid, zone_id );
			anim.onEnd = function(parent) {
				return function() {
					parent.placeInZoneNoDestroy.call( Game.zones[zone_id], pcid );
					Game.waitHideResearch = false;
					Game.anim_lock = false;
					Game.hideResearch(parent);
				}
			}(this);
			Game.anim_lock = true;
			anim.play();
			
			// slide new card into row if not was_filed NOR researched
			var new_card_id = notif.args.new_card_id;
			if (!built_from_file && new_card_id) {
				//console.log("NEW CARD: " + new_card_id);
				if (!new_card_id) {		
					this.showMessage(_("Deck is empty"), "error");				
				} else {
					let new_level = this.gamedatas.mt_gizmos[new_card_id].level;
					this.addGizmoToRow(new_card_id, $('row_'+new_level), new_level);
				}
			}

			this.spendSpheresAndRebuildPlayerCard(player_id, spheres);
			this.setUpgradeScore(player_id, notif.args.upgrade_score);
		},
		notif_sphereDrawn: function ( notif ) {
			let sphere_id = notif.args.sphere_id;
			//let sphere_color = Energy.getColor(sphere_id);
			let player_id = notif.args.player_id;
			//let player_name = notif.args.player_name;
			Builder.incrementSphereCount(player_id, sphere_id);
			let sp_html = Energy.getEnergyHtml(sphere_id, null, this);
			let sp_ele_id = Energy.getEleId(sphere_id);
			if (player_id == this.player_id) {
				dojo.place( sp_html, 'sphere_row' );
				dojo.addClass(sp_ele_id, 'ring');
				this.attachToNewParentNoDestroy( sp_ele_id, 'energy_ring' );
				this.addTooltip( sp_ele_id, '', this.format_string_recursive(Const.Tooltip_Ring_Energy(), {i18n: ['color'], color: Energy.getColor(sphere_id)}));
				dojo.connect($(sp_ele_id), 'onclick', this, 'onEnergySelect');
				let anim = this.slideToObject( sp_ele_id, 'energy_ring' );
				anim.onEnd = function(parent) {
					return function() {
						parent.placeInZoneNoDestroy.call( Game.zones['energy_ring'], sp_ele_id );
						Game.anim_lock = false;
					}
				}(this);
				Game.anim_lock = true;
				anim.play();
			} else {
				this.slideTemporaryObject( sp_html, 'sphere_row', 'dispenser', 'player_header_'+player_id ).play();
			}
			this.buildPlayerCard(player_id);
			this.setUpgradeScore(player_id, notif.args.upgrade_score);
			//console.log(player_name + ' drew a ' + sphere_color + ' sphere');	
		},
		notif_victoryPoint: function ( notif ) {
			let player_id = notif.args.player_id;
			this.gamedatas.players[player_id].victory_points = notif.args.vp_count;		
			this.buildPlayerCard(player_id);
			this.scoreCtrl[player_id].setValue( notif.args.player_score );	
			this.setUpgradeScore(player_id, notif.args.upgrade_score);
		},
		notif_lastTurn: function ( notif ) {
			$('gzs_end_banner').innerHTML = _("This is the last round!");
			dojo.style('gzs_end_banner', 'display', 'block');
			Game.repositionEnergyRing();
		},
		notif_scoreSpecial: function ( notif ) {
			this.scoreCtrl[notif.args.player_id].setValue( notif.args.player_score );
			dojo.query('.gzs_upgrade_score').style('display','none');
		},
		notif_research: function ( notif ) {		
			Game.updateDeckCounts(notif.args.deck_counts);
		}
   });             
});

