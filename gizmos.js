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
            console.log('gizmos constructor');
			Game.zones = {};              
			this.card_width = 170.5;
			this.card_height = 170.5;
			this.archive_limit = 1;			
			this.energy_limit = 5;
			this.research_quantity = 3;
			Game.selected_card_id = 0;
			this.deck_counters = [];
			Builder.active = {};
			Builder.spend_spheres = {};
			this.gamedatas = {};
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
			this.energy_limit = gamedatas.energy_limit;
			this.archive_limit = gamedatas.archive_limit;
			this.research_quantity = gamedatas.research_quantity;
			Game.selected_card_id = gamedatas.selected_card_id;	
			if (gamedatas.is_last_round == 1) {
				dojo.style('end_banner', 'display', 'block');
			}
			
			this.setupCards(gamedatas);
			this.initSphereRowAndPlayerCards(gamedatas);
			
			dojo.query( '.token' ).connect( 'onclick', this, 'onEnergySelect' );
			dojo.query( '.deck' ).connect( 'onclick', this, 'onCardSelect' );
			//dojo.query( '.card' ).connect( 'onclick', this, 'onCardSelect' );
			 
            // Setup game notifications to handle (see "setupNotifications" method below)
            this.setupNotifications();

            console.log( "Ending game setup" );
        },

        ///////////////////////////////////////////////////
        //// Game & client states
        
        // onEnteringState: this method is called each time we are entering into a new game state.
        //                  You can use this method to perform some user interface changes at this moment.
        //
        onEnteringState: function( stateName, args )
        {
			if (args && args.args && args.args.tg_gizmo_id && $(Gizmo.getEleId(args.args.tg_gizmo_id) )) {
				dojo.removeClass( Gizmo.getEleId(args.args.tg_gizmo_id), 'triggerable' );
				dojo.addClass( Gizmo.getEleId(args.args.tg_gizmo_id), 'half_selected' );
			} else {
				dojo.query('.half_selected').removeClass('half_selected');
			}

			Game.stateName = stateName;
            console.log( 'Entering state: '+stateName );
			
            switch( stateName )
            {
				case 'playerTurn':
					dojo.query('.triggerable').removeClass('triggerable');
					dojo.query('.already_used').removeClass('already_used');
					Game.activePlayer = this.getActivePlayerId();
					dojo.query('.row_card').addClass('selectable');

					if (args && args.args && args.args.energy) {
						Builder.reinitSphereCounts(this.gamedatas.players, args.args.energy, this);
					}
				case 'triggerResearch':
					dojo.query('.deck').addClass('selectable');
					break;
				case 'triggerSelect':
					//console.log('highlighting triggerable cards:');
					console.log(args);					
					if (args && args.args && args.args.triggered_gizmos) {
						let tg_gizmos = args.args.triggered_gizmos;
						for (var gizmo_id in tg_gizmos) {
							let gizmo = tg_gizmos[gizmo_id];
							dojo.addClass(Gizmo.getEleId(gizmo_id), gizmo.is_used == "1" ? 'already_used' : 'triggerable');
							dojo.removeClass(Gizmo.getEleId(gizmo_id), gizmo.is_used == "1" ? 'triggerable' : 'already_used');
						}
					}					
					break;
				case 'research':
					// console.log("POPULATING RESEARCH:");
					// console.log(args);
					if (args && args.args && args.args.r_cards) {
						this.r_gizmos = args.args.r_cards;
						this.showResearch();
					}
					break;
				case 'cardSelected':
					if (this.isCurrentPlayerActive() && !Builder.canPurchase()) {
						dojo.addClass( 'button_build', 'disabled');//disable the button
					}
				case 'deckSelected':
					this.handleSelectedCard();
				case 'researchedCardSelected':
					//console.log(args);
					if (args && args.args) {						
						this.archive_limit = args.args.archive_limit;
						this.energy_limit = args.args.energy_limit;
						this.research_quantity = args.args.research_quantity;

						if (args.args.r_cards) {
							Game.selected_card_id = args.args.selected_card_id;
							this.r_gizmos = args.args.r_cards;
							this.showResearch();
						}
					}
					Builder.checkApplyDiscounts();
					if (this.isCurrentPlayerActive()) {
						Builder.autoselectSpend();
					}					
					Builder.refreshHeader(this);
					break;
				case 'buildLevel1For0':
					if (this.isCurrentPlayerActive()) {
						dojo.query('#row_1 .card_1').addClass('selectable');
						dojo.query(Game.getPlayerArchive() + " .card_1").addClass('selectable');
					}
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
				case 'triggerResearch':
					dojo.query('.deck').removeClass('selectable');
					break;
				case 'cardSelected':
				case 'deckSelected':
				case 'researchedCardSelected':
					if (this.player_id == this.getActivePlayerId()) {
						dojo.removeClass('converter_' + this.player_id, 'half_selected');
					}
					dojo.query('.selected').removeClass('selected');
					dojo.query('.half_selected').removeClass('half_selected');
					dojo.query('.discount').removeClass('selectable');
					dojo.query('.tempnrg').forEach(dojo.destroy);
					if (!Game.waitHideResearch) {
						dojo.empty('researched_gizmos');
						dojo.style('researched_gizmos', 'display', 'none');
						Game.repositionEnergyRing();
					}
					Builder.resetVars();					
					break;
				case 'triggerSelect':
					break;
				case 'research':
					if (!Game.waitHideResearch) {
						dojo.empty('researched_gizmos');
						dojo.style('researched_gizmos', 'display', 'none');
						Game.repositionEnergyRing();
					}
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
				this.archive_limit = args.archive_limit;			
				this.energy_limit = args.energy_limit;
				this.research_quantity = args.research_quantity;
				console.log('Set selected_card_id=' + Game.selected_card_id);
			}
            console.log( 'onUpdateActionButtons: '+stateName );                      
            if( this.isCurrentPlayerActive() )
            {            
                switch( stateName )
                {
					case 'deckSelected':
						this.addActionButton( 'button_research', 
							this.format_string_recursive( _('Research Level ${level} (${quantity})'), {
									level: Gizmo.levelNumerals(Game.selected_card_id), 
									quantity: this.research_quantity
							}), 'researchSelectedDeck' );
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancelSelectedCard' );
						break;
					case 'triggerSphereSelect':
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancel' );
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
									args: Builder.getSpendSpheresArgs()
								}
							}), 
							'buildSelectedCard' );
						this.addActionButton( 'button_file', _('File'), 'fileSelectedCard' );
						this.addActionButton( 'button_pass', _('Cancel'), 'cancelSelectedCard' );
						break;
					case 'triggerSelect':
						this.addActionButton( 'button_pass', _('Pass'), 'passTriggers' );
						break;
					case 'triggerDraw':
						this.addActionButton( 'button_draw', _('Draw'), 'drawEnergy' );
						this.addActionButton( 'button_cancel', _('Cancel'), 'cancel' );					
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
            }
        },        

        ///////////////////////////////////////////////////
        //// Utility methods
        
        /*
        
            Here, you can defines some utility methods that you can use everywhere in your javascript
            script.
        
        */
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
			// this.research = new ebg.popindialog();
			// this.research.create( 'research_dialog' );
			// this.research.setTitle( _("Researched Cards") );
			// var html = this.format_block( 'jstpl_research_dialog', {} );				
			// this.research.setContent( html );
			// if ( dontShow !== true ) {
			// 	this.research.show();
			// 	var width = $('row_2').offsetWidth;
			// 	dojo.style('popin_research_dialog', 'width', width + "px");
			// 	console.log('set dialog width=' + width);
			// }
			for (var card_id in this.r_gizmos) {
				var gizmo = this.r_gizmos[card_id];
				var gizmo_id = gizmo.type_arg;
				var mt_gizmo = this.gamedatas.mt_gizmos[gizmo_id];
				var other_class = 'researched selectable';
				if (gizmo_id == Game.selected_card_id) {
					other_class += ' selected';
				}				
				var gizmoDetails = {
					'id': gizmo_id,
					'level': mt_gizmo.level,
					'other_class': other_class
				};
				dojo.place( this.format_block('jstpl_card', gizmoDetails), 'researched_gizmos' );
				this.connect( $('card_' + gizmo_id), 'onclick', 'onCardSelect' );
				this.addGizmoTooltip(gizmo_id);
			}
			dojo.style('researched_gizmos', 'display', 'block');
			Game.repositionEnergyRing();
		},
		passResearch: function() {
			this.pass(
				_("Are you sure you want to pass without building or filing a card?")
			);
		},
		passTriggers: function() {
			this.pass(
				_("Are you sure you want to pass without using your triggered gizmo(s)?")
			);			
		},
		pass: function(msg) {
			if (this.checkAction( "pass" )) {
				this.confirmationDialog(msg, () => {
					this.ajaxcall( "/gizmos/gizmos/pass.html", {lock: true}, this, function( result ) {} );					
				});
			}			
		},
		
		getSelectedCardCost: function() {
			if (Game.selected_card_id) {
				var card = this.gamedatas.mt_gizmos[Game.selected_card_id];
				if (card) {
					if (Builder.active && Object.keys(Builder.active).length > 0) {
						return "CONVERTER";
					} else {					
						return card.cost + " " + card.color;
					}
				} else {
					return "NOT FOUND";
				}
			} else {
				return "NO SELECTION";
			}
		},	
		
		insertSphereInRow: function ( sphere_id, slide_from_dispenser ) {
			let sphere_ele = Energy.getEnergyHtml(sphere_id);
			dojo.place( sphere_ele, 'sphere_row' );
			Game.zones['sphere_row'].placeInZone( Energy.getEleId(sphere_id), Game.getNrgWeight() );
			this.addTooltip( Energy.getEleId(sphere_id), '', dojo.string.substitute(Const.Tooltip_Row_Energy(), {color: Energy.getColor(sphere_id)}));
			// if (slide_from_dispenser) {
			// 	let anim = this.slideToObject( Energy.getEleId(sphere_id), 'sphere_row' );
			// 	anim.onEnd = function(parent) {
			// 		return function() {
			// 			Game.zones['sphere_row'].placeInZone( Energy.getEleId(sphere_id), Game.getNrgWeight() );
			// 			parent.addTooltip( Energy.getEleId(sphere_id), '', dojo.string.substitute(Const.Tooltip_Row_Energy(), {color: Energy.getColor(sphere_id)}));
			// 		}
			// 	}(this);
			// 	anim.play();
			// } else {
			// 	Game.zones['sphere_row'].placeInZone( Energy.getEleId(sphere_id) );
			// 	this.addTooltip( Energy.getEleId(sphere_id), '', dojo.string.substitute(Const.Tooltip_Row_Energy(), {color: Energy.getColor(sphere_id)}));
			// }
		},
		spendSpheres: function (player_id, spheres) {
			let arrSpheres = spheres.split(',');
			for (var i=0; i<arrSpheres.length; i++) {
				let spid = arrSpheres[i];
				Builder.decrementSphereCount(player_id, spid);
				// place a sphere in player card then drag
				if (player_id == this.player_id) {
					let sp_ele_id = Energy.getEleId(spid);
					this.disconnect( $(sp_ele_id), 'onEnergySelect' );
					Game.zones['energy_ring'].removeFromZone(sp_ele_id);
				} else {
					dojo.place( 
						Energy.getEnergyHtml(spid, Energy.getColor(spid), ''), $('player_board_'+player_id) );		
				}		
				this.slideToObjectAndDestroy( $('sphere_'+spid), $('dispenser') );
				this.buildPlayerCard(player_id);
			}
			
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
            }			
		},
		addSphereToRing: function(spid, isConnect) {			
			let sphere = Energy.getEnergyHtml(spid, 'ring');
			dojo.place( sphere, 'energy_ring' );
			Game.zones['energy_ring'].placeInZone( Energy.getEleId(spid) );			
			this.addTooltip( Energy.getEleId(spid), '', dojo.string.substitute(Const.Tooltip_Ring_Energy(), {color: Energy.getColor(spid)}));
			if (isConnect) {				
				this.connect( Energy.getEleId(spid), 'onclick', 'onEnergySelect');
			}
		},
		buildPlayerCard: function (player_id) {
			// If tokens div already exists, destroy it to rebuild
			var player_tokens_div = $('token_counts_' + player_id);
			if (player_tokens_div) {
				dojo.destroy(player_tokens_div);
			}
			
			let allCount = dojo.query('#gizmos_container_'+player_id+' .card.built').length;
			//console.log(player_id +" has " + allCount + " gizmos");

			dojo.place( this.format_block('jstpl_player_board', {
				'id': player_id,
				'red_count': this.getPlayerSphereCount(player_id, 'red'), 
				'black_count': this.getPlayerSphereCount(player_id, 'black'), 
				'blue_count': this.getPlayerSphereCount(player_id, 'blue'), 
				'yellow_count': this.getPlayerSphereCount(player_id, 'yellow'),
				'vp_count': this.getPlayerVpCount(player_id),
				'gizmos_all': allCount,
				'gizmos_3s': dojo.query('#gizmos_container_'+player_id+' .card_3.built').length
			}), $('player_board_'+player_id) );

			// add tooltips
			for (var i=0; i<4; i++) {
				let color = Energy.colors[i];
				this.addTooltip("pair_"+player_id+"_"+color, dojo.string.substitute( _("Number of ${color} energy"), {color: color}), '' );
			}
			this.addTooltip( dojo.string.substitute("pair_${pid}_vps",{pid:player_id}), _("Number of victory point tokens"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_allgs",{pid:player_id}), _("Number of built gizmos"), '' );
			this.addTooltip( dojo.string.substitute("pair_${pid}_3gs", {pid:player_id}), _("Number of built Level 3 gizmos"), '' );
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
					lock: true
				}, this, function( result ) {} );			
			}			
		},
		buildLevel1For0: function ( evt ) {
			this.ajaxcall( "/gizmos/gizmos/buildLevel1For0.html", {
				"gizmo_id": this.selected_card_id,
				lock: true
			}, this, function( result ) {} );
		},
		researchSelectedDeck: function ( evt ) {			
			if ( this.checkAction("research") ) {				
				this.ajaxcall( "/gizmos/gizmos/research.html", {lock: true}, this, function( result ) {} );
			}
		},
		fileSelectedCard: function ( evt ) {			
			if ( this.checkAction("cardFile") ) {
				// ensure player's archive is not full
				let filed = dojo.query( dojo.string.substitute('#${archive_id} .card', {archive_id: Game.getPlayerArchive(this.getActivePlayerId())}) );
				if (filed.length >= this.archive_limit) {
					this.showMessage(_("Your archive is full"), "error");
				} else {
					this.ajaxcall( "/gizmos/gizmos/fileSelectedCard.html", {
						lock: true,
						"selected_card_id": this.selected_card_id ?? 0
					}, this, function( result ) {} );					
				}
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
					lock: true
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
			this.connect($(Gizmo.getEleId(gizmo_id)), 'onclick', 'onCardSelect');	
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
					other_class = 'selectable';
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
			Game.zones[div_for_this.id].placeInZone( new_gizmo_id );
			console.log("placed " + new_gizmo_id + " in zone");
			this.connect($(new_gizmo_id), 'onclick', 'onCardSelect');
			console.log("and connected onCardSelect");			
		},
		setupDeckTooltips: function() {
			for (var level=1; level<=3; level++) {
				this.addTooltipHtml( 'deck_' + level, this.format_block('jstpl_deckTooltip', {"level": level, "count": this.gamedatas.deck_counts[level.toString()]}) );				
			}
		},
		setupCards: function ( gamedatas ) {
			var gizmo_cards = gamedatas.gizmo_cards;
			console.log("setupCards from cards:");
			console.log(gizmo_cards);
			console.log(gamedatas.deck_counts);
			for (var level = 1; level<=3; level++) {
				var l_gizmos = gizmo_cards[level];
				console.log("adding level " + level + " gizmos:");
				console.log(l_gizmos);	
				var l_row_div = $('row_' + level);
				dojo.place( this.format_block('jstpl_deck', {"level": level}), l_row_div );
				for (var x in l_gizmos) {
					var gizmo = l_gizmos[x];
					this.addGizmoToRow(gizmo.type_arg, l_row_div, level);
				}
				// this.deck_counters[i] = new ebg.counter();
				// this.deck_counters[i].create('deck_count_' + level);				
			}
			this.setupDeckTooltips();
			
			// ensure that current player is on 
			console.log("===PLAYER_SETUP===");
			console.log(this.gamedatas.players);
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
			var height = (this.card_height*((1-Game.stack) + Game.stack*num_cards));
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
				"color": player.color
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
			Game.zones[archive_id].create( this, archive_id, this.card_width/2, this.card_height/2 );
			Game.zones[archive_id].setPattern( 'verticalfit' );			
			if (fileds) {
				dojo.style(archive_id, 'min-height', ((this.card_height/2)*(1-(Game.stack) + Game.stack*fileds.length))+"px");
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
				if (Builder.picking > 0) {
					let nrgEle = evt.target;
					this.disconnect( $(nrgEle.id), 'onEnergySelect' );
					this.attachToNewParent( $(nrgEle.id), $(Gizmo.getEleId(Builder.picking)) );
					this.connect($(nrgEle.id), 'onclick', 'onEnergySelect');
					dojo.addClass( nrgEle.id, 'convert_to' );
					dojo.removeClass( nrgEle.id, 'picker' );
					let anim = this.slideToObjectPos( evt.target.id, Gizmo.getEleId(Builder.picking), this.card_height-50);
					anim.onEnd = function(parent) {
						return function() {
							dojo.attr(nrgEle.id, 'style', 'position:absolute;');
							Builder.applyColorConverter( Builder.picking, null, Energy.getEleColor(nrgEle), null, parent );
							Energy.hidePicker(parent);
							Builder.temp_energy.push(nrgEle.id);
						}
					}(this);
					//dojo.connect(anim, 'onEnd', dojo.hitch(this, 'tempSlideCallback', evt.target));
					anim.play();
				}
			} else if (evt.target.classList.contains('convert_to')) {
				if (Builder.picking > 0) {
					Builder.toggleConverter( Builder.picking, this, evt.target.id );
				}
			} else if (this.checkAction( "sphereSelect" )) {
				// ensure user is not at limit
				var sphere_count = this.getPlayerSphereCount(this.getActivePlayerId());
				if (sphere_count == this.sphere_limit) {
					this.showMessage(_("You cannot hold more energy"), "error");
				} else {
					//console.log("onEnergySelect submitting sphereSelect ajax");
					this.ajaxcall( "/gizmos/gizmos/sphereSelect.html", {
						'sphere_id': sphere_id,
						lock: true
					}, this, function( result ) {
					} );
				}
			} else {
				//console.log("sphereSelect not allowed in this state");
			}
		},		
		onCardSelect: function( evt ) {
			//console.log("Selected a card: " + Game.stateName);
			//console.log(evt);
			
			let card_ele = evt.target;
			let selected_card_id = Gizmo.getIdOfEle(card_ele.id);

			if (Game.stateName == 'buildLevel1For0') {
				if (dojo.hasClass(card_ele.id, 'built')) {
					this.showMessage( _("Cannot build an already built card!"), "error");
				} else if (selected_card_id < 100 || selected_card_id > 200) {
					this.showMessage( _("Must select a Level I Gizmo"), "error" );
				} else {				
					if (this.selected_card_id == selected_card_id) {
						this.selected_card_id = 0;
						dojo.removeClass(card_ele.id, 'selected');
						dojo.addClass('button_build', 'disabled');
					} else {
						if (this.selected_card_id) {
							dojo.removeClass(Gizmo.getEleId(this.selected_card_id), 'selected');
						}
						this.selected_card_id = selected_card_id;
						dojo.addClass(card_ele.id, 'selected');
						dojo.removeClass('button_build', 'disabled');
					}
				}
			} else if (Game.stateName == 'triggerFile') {
				if (dojo.hasClass(card_ele.id, 'built')) {
					this.showMessage( _("Cannot File an already built card!"), "error");
				} else if (dojo.hasClass(card_ele.id, 'filed')) {
					this.showMessage( _("Cannot File an already filed card!"), "error" );
				} else {				
					if (this.selected_card_id == selected_card_id) {
						this.selected_card_id = 0;
						dojo.removeClass(card_ele.id, 'selected');
						dojo.addClass('button_file', 'disabled');
					} else {
						if (this.selected_card_id) {
							dojo.removeClass(Gizmo.getEleId(this.selected_card_id), 'selected');
						}
						this.selected_card_id = selected_card_id;
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
						lock: true
					}, this, function( result ) {			
					} );				
				}
			} else if (card_ele.classList.contains('converter')) {
				if ( this.checkAction("cardBuilt") ) {
					Builder.toggleConverter(selected_card_id, this);
				}
			}
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
			//dojo.subscribe( 'research', this, "notif_research" );
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
				this.disconnect( $(sp_ele_id), 'onEnergySelect' );
				this.removeTooltip( sp_ele_id );
				dojo.addClass(sp_ele_id, 'ring');
				Game.zones['sphere_row'].removeFromZone(sp_ele_id);
				this.attachToNewParent( $(sp_ele_id), $('energy_ring') );
				let anim = this.slideToObject( sp_ele_id, 'energy_ring' );
				anim.onEnd = function(parent) {
					return function() {
						Game.zones['energy_ring'].placeInZone( sp_ele_id );
						parent.addTooltip( sp_ele_id, '', dojo.string.substitute(Const.Tooltip_Ring_Energy(), {color: Energy.getColor(sphere_id)}));
						parent.connect($(sp_ele_id), 'onclick', 'onEnergySelect');
					}
				}(this);
				anim.play();
			} else {
				// slide sphere to player card
				this.disconnect( $(sp_ele_id), 'onEnergySelect' );
				Game.zones['sphere_row'].removeFromZone(sp_ele_id); //, false, 'energy_ring');
				this.slideToObjectAndDestroy( $(sp_ele_id), $('player_header_'+player_id) );				
			}
			
			$('token_counts_' + player_id).remove();
			this.buildPlayerCard(player_id);
			
			let new_sphere_id = notif.args.new_sphere_id;
			//console.log("Inserting new sphere: " + new_sphere_id);
			this.insertSphereInRow(new_sphere_id, true);
			this.connect($(Energy.getEleId(new_sphere_id)), 'onclick', 'onEnergySelect');
			//this.showMessage(" ", "info");
		},
		notif_cardBuiltOrFiled: function ( notif ) {
			Game.waitHideResearch = true;
			let purchased_id = notif.args.purchased_card_id;
			let action = notif.args.action;
			let player_id = notif.args.player_id;
			let spheres = notif.args.spent_spheres;
			let built_from_file = notif.args.built_from_file;
			let new_score = notif.args.new_score;

			if (new_score > 0) {
				this.scoreCtrl[player_id].setValue( new_score );
			}
			if (spheres) {
				//console.log("spent spheres: ");
				//console.log(spheres);
				// decrement spent_spheres
				this.spendSpheres(player_id, spheres);
			}
			
			// slide purchased card to player
			let pcid = Gizmo.getEleId(purchased_id);
			var mt_gizmo = Gizmo.details(purchased_id);
			let zone_id = (action == 'Files' ?
				Game.getPlayerArchive(player_id) :
				Game.getBuiltGizmoDiv(purchased_id, player_id)
			);
			
			this.disconnect( $(pcid), 'onCardSelect' );
			this.attachToNewParent( $(pcid), $(zone_id) );

			let level = mt_gizmo.level;
			dojo.removeClass(pcid, 'selected');
			dojo.removeClass(pcid, 'row');
			dojo.removeClass(pcid, 'researched');
			dojo.removeClass(pcid, 'row_' + level);
			if (action == 'Files') {
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
				Game.zones[Game.getPlayerArchive(player_id)].removeFromZone( pcid );					
			}
			this.setZoneHeight( zone_id, Game.zones[zone_id].getItemNumber()+1, player_id );
			let anim = this.slideToObject( pcid, zone_id );
			anim.onEnd = function(parent) {
				return function() {
					Game.zones[zone_id].placeInZone( pcid );	
					parent.connect($(pcid), 'onclick', 'onCardSelect');
					dojo.empty('researched_gizmos');
					dojo.style('researched_gizmos', 'display', 'none');
					Game.repositionEnergyRing();
					Game.waitHideResearch = false;
				}
			}(this);
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
		},
		notif_sphereDrawn: function ( notif ) {
			let sphere_id = notif.args.sphere_id;
			//let sphere_color = Energy.getColor(sphere_id);
			let player_id = notif.args.player_id;
			//let player_name = notif.args.player_name;
			Builder.incrementSphereCount(player_id, sphere_id);
			let sp_html = Energy.getEnergyHtml(sphere_id);
			let sp_ele_id = Energy.getEleId(sphere_id);
			if (player_id == this.player_id) {
				// this.slideToObject( $( sp_ele_id ), $('energy_ring') );	
				// Game.zones['energy_ring'].placeInZone( sp_ele_id );
				// dojo.addClass(sp_ele_id, 'ring');				
				// this.addTooltip( sp_ele_id, '', dojo.string.substitute(Const.Tooltip_Ring_Energy(), {color: Energy.getColor(sphere_id)}));
				// this.connect( $( sp_ele_id ), 'onclick', 'onEnergySelect' );
				dojo.place( sp_html, 'sphere_row' );
				dojo.addClass(sp_ele_id, 'ring');
				this.attachToNewParent( $(sp_ele_id), $('energy_ring') );
				let anim = this.slideToObject( sp_ele_id, 'energy_ring' );
				anim.onEnd = function(parent) {
					return function() {
						Game.zones['energy_ring'].placeInZone( sp_ele_id );
						parent.addTooltip( sp_ele_id, '', dojo.string.substitute(Const.Tooltip_Ring_Energy(), {color: Energy.getColor(sphere_id)}));
						parent.connect($(sp_ele_id), 'onclick', 'onEnergySelect');
					}
				}(this);
				anim.play();
			} else {
				this.slideTemporaryObject( sp_html, 'sphere_row', 'dispenser', 'player_header_'+player_id ).play();
			}
			this.buildPlayerCard(player_id);
			//console.log(player_name + ' drew a ' + sphere_color + ' sphere');	
		},
		notif_victoryPoint: function ( notif ) {
			this.gamedatas.players[notif.args.player_id].victory_points = notif.args.vp_count;		
			this.buildPlayerCard(notif.args.player_id);
			this.scoreCtrl[notif.args.player_id].setValue( notif.args.player_score );	
		},
		notif_lastTurn: function ( notif ) {
			dojo.style('end_banner', 'display', 'block');
		},
		notif_scoreSpecial: function ( notif ) {
			this.scoreCtrl[notif.args.player_id].setValue( notif.args.player_score );	
		}
   });             
});
