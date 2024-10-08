let Const = {
	GIDs_Discount_Lvl2: [315,316],
	GIDS_Discount_FromFile: [327,328],
	GIDs_Discount_FromResearch: [329,330],
	GIDs_Discount_All: [315,316,327,328,329,330],
	Tooltip_Ring_Energy: function () {return _("${color} energy in your ring may be spent for building gizmos")},
	Tooltip_Row_Energy: function() {return _("${color} energy in the row may be picked")},
	Tooltip_Next_Energy: function() {return _("${color} energy will enter the row after the next pick")},
	// Red: _('red'),
	// Yellow: _('yellow'),
	// Blue: _('blue'),
	// Black: _('black')
	VP_Html: '<div class="gzs_log_vp"></div>',
	TrackSeg_Width: 572.7,
	Breakpoint: 1800,
	Tooltip_Converters: function() {return _("Gizmos that convert Energy into different types or different quantities.")},
	Tooltip_File: function() {return _("Choose a faceup Gizmo from the Display Area and add it to your Archive.")},
	Tooltip_Pick: function() {return _("Take 1 Energy from the 6 available in the Energy Row.")},
	Tooltip_Build: function() {return _("Choose a faceup Gizmo from the Display Area or your Archive.  Spend Energy to add it to your Active Gizmo Area.")},
	Tooltip_Research: function() {return _("Draw cards from 1 of the facedown Level Decks equal to your Research Amount.  You may choose 1 to Build or File.")},
}

let Game = {
	zones: {},
	activePlayer: -1,
	selected_card_id: -1,
	stateName: null,
	stack: 0.35,
	file_stack: 1,
	energy_weight: 100,
	waitHideResearch: false,
	isResearching: false,
	deck_counts: {},
	selected_energy: -1,
	saved_desc: null,
	action_lock: false,
	anim_lock: false,
	isLocked: function() {
		return this.action_lock || this.anim_lock;
	},
	unlock: function() {
		this.action_lock = false;
		this.anim_lock = false;
	},

	getPlayerArchive: function(pid) {
		if (!pid) {
			pid = Game.activePlayer;
		}
		return 'archive_'+pid;
	},
	getBuiltGizmoDiv: function(gizmo_id, player_id) {
		var gtype = Gizmo.details(gizmo_id).effect_type;
		if (gtype.indexOf('trigger_build') >= 0) {
			gtype = 'trigger_build';
		}
		return gtype+'_'+player_id;
	},
	getEndHtml: function() {
		return '<div id="spl_message" class="spl_message" style="">This is the last turn!</div>';
	},
	repositionEnergyRing: function() {
		if (window.innerWidth < Const.Breakpoint) {
			let height = $("current_player_gizmos").offsetHeight + $("research_outer").offsetHeight;
			height += $('gzs_end_banner').offsetHeight;
			dojo.style( 'energy_ring', 'top', height+"px" );
			dojo.style( 'energy_ring', 'left', "unset" );
		} else {
			dojo.style( 'energy_ring', 'top', $('gzs_end_banner').offsetHeight+"px" );
			let width = $('board_left').offsetWidth - 10;
			dojo.style( 'energy_ring', 'left', width+"px" );
		}
		//console.log("setRingTop=" + height+"px");			
		//}
	},
	getNrgWeight: function() {
		if (!Game.energy_weight) {
			Game.energy_weight = 100;
		}

		Game.energy_weight--;
		return Game.energy_weight;
	},
	updateDeckCounts: function(deck_counts) {
		$('deck_count_1').innerHTML = deck_counts['deck_1'];
		$('deck_count_2').innerHTML = deck_counts['deck_2'];
		$('deck_count_3').innerHTML = deck_counts['deck_3'];
		Game.deck_counts = deck_counts;
	},
	getOrderedResearch: function() {
		let card_eles = dojo.query('#researched_gizmos .card');
		return card_eles.map( (ele) => { return parseInt(Gizmo.getIdOfEle(ele.id)) } ).join(',');
	},
	hideResearch: function(parent) {
		if (!Game.waitHideResearch) {	
			let eles = dojo.query('#gizmos_board .arrow');
			for (var i=0; i<eles.length; i++) {
				dojo.destroy( eles[i] );
			}
			dojo.empty('researched_gizmos');
			dojo.style('research_outer', 'display', 'none');
			Game.repositionEnergyRing();
			Game.isResearching = false;
		}
	},	
	isShowEnergyConfirm: function(parent) {
		let pref = parent.prefs[201].value;
		console.log('isShowEnergyConfirm?', pref)
		switch (pref) {
			case '2': // never
				console.log('2 => never');
				return false;
			case '3': // always
				console.log('3 => always');
				return true;
			case '1': // Touch device only
			default:
				let isTouch = window.matchMedia('(hover:none)').matches;
				console.log('1/default => if touch: ', isTouch);
				return isTouch;
		}		
	},
	isColorblindFriendly: function(parent) {
		let pref = parent.prefs[203].value;
		//console.log('isColorblindFriendly?', pref)
		switch (pref) {
			case '2': // Yes
				return true;
			case '1': // No (default)
			default:
				return false;
		}		
	},
	showEnergyConfirm: function(parent) {
		Game.resetDescription(parent);
		Game.saved_desc = parent.gamedatas.gamestate.descriptionmyturn;
		parent.gamedatas.gamestate.descriptionmyturn = _('Confirm Pick Energy?');
		parent.updatePageTitle();
		parent.removeActionButtons();
		parent.addActionButton( 'button_confirmPick', _('Confirm'), 'doPickEnergy' );
		parent.addActionButton( 'button_cancelPick', _('Cancel'), 'cancelPickEnergy' );
	},
	resetDescription: function(parent) {
		parent.gamedatas.gamestate.descriptionmyturn = Game.saved_desc;
		parent.updatePageTitle();
	},
	selectEnergy: function(id) {
		if (Game.selected_energy && id != Game.selected_energy) {
			Game.deselectEnergy();
		}
		Game.selected_energy = id;
		dojo.addClass( Energy.getEleId(id), 'selected' );
	},
	deselectEnergy: function() {
		let ele_id = Energy.getEleId(Game.selected_energy);
		if ($(ele_id))
			dojo.removeClass( ele_id, 'selected' );
			
		Game.selected_energy = -1;
	},
	hasUsableGizmos: function() {		
		let q_trg = dojo.query('.triggerable');
		let q_ill = dojo.query('.gzs_illegal');
		return q_trg && q_trg.length > 0 && (!q_ill || q_trg.length > q_ill.length);
	}
}

let Builder = {
	//spend_spheres: {},
	active_converters: {},
	sphere_counts: {},
	selected_spheres: [],
	spending_power: {},
	temp_energy: [],
	saved_desc: null,
	discount: 0,
	picking: 0,
	nextOrder: 0,
	getCost: function(mtg) {
		let cost = mtg.cost - this.discount;
		return cost < 0 ? 0 : cost;
	},
	logEnergy: function() {
		console.log(this.sphere_counts);
		console.log(this.selected_spheres);
		console.log(this.spending_power);
	},
	reinitSphereCounts: function(players, spheres, parent) {
		Builder.initSphereCounts(players);
		for (var sphere_id in spheres) {
			let sphere = spheres[sphere_id];
			if (sphere['location'] == 'next') {				
				parent.insertNextSphere(sphere_id);
			} else if (sphere['location'] != 'row') {
				Builder.incrementSphereCount(sphere['location'], sphere_id);
			} else {
				parent.insertSphereInRow(sphere_id);
			}
		}
	},
	initSphereCounts: function(players) {
		this.sphere_counts = {};
		for (var pid in players) {
			this.sphere_counts[pid] = {
				spheres: []
			};
		}
	},
	incrementSphereCount: function ( player_id, sphere_id ) {
		var color = Energy.getColor(sphere_id);
		if (!Builder.sphere_counts[player_id]) {
			Builder.sphere_counts[player_id] = {};
		} 
		if (!Builder.sphere_counts[player_id][color]) {
			Builder.sphere_counts[player_id][color] = 1;
		} else {
			Builder.sphere_counts[player_id][color]++;
		}
		
		if (!Builder.sphere_counts[player_id].spheres) {
			Builder.sphere_counts[player_id].spheres = [];
		}
		Builder.sphere_counts[player_id].spheres.push(sphere_id);			
	},	
	decrementSphereCount: function ( player_id, sphere_id ) {
		var iSphere = Builder.sphere_counts[player_id].spheres.indexOf(sphere_id);
		Builder.sphere_counts[player_id].spheres.splice(iSphere, 1);
		
		var color = Energy.getColor(sphere_id);
		Builder.sphere_counts[player_id][color]--;
	},
	resetVars: function() {
		this.active_converters = {};
		this.selected_spheres = [];
		this.spending_power = {};
		this.temp_energy = [];
		this.picking = 0;
		this.discount = 0;
		this.nextOrder = 0;
		Game.selected_card_id = 0;
	},
	updateDescription: function(parent, new_desc, gid) {
		if (!this.active_converters[gid])
			this.active_converters[gid] = {};

		let prevPicking = this.picking;
		this.picking = gid;
		if (prevPicking && prevPicking != gid)
			Builder.deselectConverter(prevPicking, parent);			

		if (!this.saved_desc)
			this.saved_desc = parent.gamedatas.gamestate.descriptionmyturn;

		dojo.addClass( Gizmo.getEleId(gid), 'half_selected' );
		parent.gamedatas.gamestate.descriptionmyturn = new_desc;
		Object.assign(this.active_converters[gid], {
			picking: true
		});
		parent.updatePageTitle();
	},
	assignOrder: function(gid, isSecond) {
		this.nextOrder++;

		// let keys = Object.keys(this.active_converters);
		// var o = 0;
		// for (var i=0; i<keys.length; i++) {
		// 	let ok = this.active_converters[keys[i]].order;
		// 	if (ok > o) {
		// 		o = ok;
		// 	}
		// 	let ok2 = this.active_converters[keys[i]].second_convert?.order;
		// 	if (ok2 > o) {
		// 		o = ok;
		// 	}
		// }
		if (isSecond)
			this.active_converters[gid].second_convert.order = this.nextOrder;
		else
			this.active_converters[gid].order = this.nextOrder;
	},
	refreshHeader: function(parent) {
		if (this.saved_desc) {
			parent.gamedatas.gamestate.descriptionmyturn = this.saved_desc;
			parent.updatePageTitle();
			this.saved_desc = null;
		}
		if ( $('button_build') ) {
			if (this.canPurchase()) {
				dojo.removeClass( 'button_build', 'disabled');//re-enable the button
			} else {
				dojo.addClass( 'button_build', 'disabled');//disable the button						
			}
			let args = Builder.getSpendSpheresArgs(parent);
			console.log("args: ", args);
			$('button_build').innerHTML = parent.format_string_recursive( _('Build (${energy})'), {
				energy: {
					log: '${x} / ${y} ${color}',
					args: args
				}
			});
		}
	},
	incSpending: function(color, num) {
		if (!this.spending_power[color]) {
			this.spending_power[color] = num;
		} else {
			this.spending_power[color]+=num;
		}
	},
	decSpending: function(color, num) {
		if (!this.spending_power[color] || this.spending_power[color] < num) {
			//this.showMessage("ERROR: cannot decSpending( " + color + " ) because < " + num, "error");
			return false;
		} else {
			this.spending_power[color]-=num;
			return true;
		}
	},
	spendEnergy: function(spid) {
		if (dojo.hasClass(Energy.getEleId(spid), 'selected')) {
		} else {
			dojo.addClass(Energy.getEleId(spid), 'selected');
			this.selected_spheres.push(spid);
			this.incSpending(Energy.getColor(spid), 1);
		}
	},
	despendEnergy: function(spid) {
		if (dojo.hasClass(Energy.getEleId(spid), 'selected') && this.decSpending(Energy.getColor(spid), 1)) {
			dojo.removeClass(Energy.getEleId(spid), 'selected');
			this.selected_spheres.splice( this.selected_spheres.indexOf(spid), 1);			
		}
	},
	getSpendSpheresArgs: function(parent) {
		if (Game.selected_card_id < 100) {
			return {};
		} else {
			let sel_color = Gizmo.details(Game.selected_card_id).color;
			var cost = Builder.getCost( Gizmo.details(Game.selected_card_id) );
			if (this.discount > 0) {
				cost += dojo.string.substitute("<sup class='discount_sup'> -${discount}</sup>",{discount: this.discount});
			}
			var total = 0;
			var color;
			if (sel_color == 'multi') {
				for (var key in this.spending_power) {
					total += this.spending_power[key];
				}
				color = sel_color;
			} else {
				total = (this.spending_power[sel_color] ?? 0);
				// Ensure that extra energies of different colors are not being spent - show if they are
				var adtlColors = {args:{}};
				var arrColors = [];
				for (var i in Energy.colors) {
					let aColor = Energy.colors[i];
					if (aColor != sel_color && this.spending_power[aColor] > 0) {
						arrColors.push("+"+this.spending_power[aColor]+" ${"+aColor+"}");
						adtlColors.args[aColor] = Energy.getHtmlForTooltip(aColor, parent, true);
					}
				}
				if (arrColors.length > 0) {
					adtlColors.log = "${"+sel_color+"} | "+arrColors.join(' | ');
					adtlColors.args[sel_color] = Energy.getHtmlForTooltip(sel_color, parent, true);
					color = adtlColors;
				} else {
					color = Energy.getHtmlForTooltip(sel_color, parent, true);
				}
			}
			console.log("getSpendSpheresArgs",color);
			return {
				color: color,
				x: total,
				y: cost
			};
		}
	},
	canPurchase: function() {
		if (Game.selected_card_id < 100) {
			return false;
		} else {
			let sel_color = Gizmo.details(Game.selected_card_id).color;
			let cost = Builder.getCost( Gizmo.details(Game.selected_card_id) );
			var total;
			if (sel_color == 'multi') {
				total = this.getMultiPower();
			} else {
				total = (this.spending_power[sel_color] ?? 0);
				// Ensure that extra energies of different colors are not being spent
				for (var i in Energy.colors) {
					if (Energy.colors[i] != sel_color && this.spending_power[Energy.colors[i]] > 0) {
						return false;
					}
				}
			}
			return total == parseInt(cost);
		}
	},
	getMultiPower: function() {
		let total = 0;
		for (var key in this.spending_power) {
			total += this.spending_power[key];
		}
		return total;
	},
	validateSpending: function (parent) {
		let mtg = Gizmo.details(Game.selected_card_id);
		let cost = Builder.getCost( mtg );
		if (mtg.color == 'multi') {
            var total = 0;
            for (var color in this.spending_power) {
				total += this.spending_power[color];
            }
            if (total != cost) {
				this.showMessage( parent.format_string_recursive( _("You selected ${total} energy for cost ${cost}.  Must pay exact cost"), {
					total: total,
					cost: cost
				} ), 'error');
				return false;
            }
        } else {
            for (var color in this.spending_power) {
				let number = this.spending_power[color];
                if (color == mtg.color && number != cost) {
                    this.showMessage( parent.format_string_recursive( _("You selected ${total} energy for cost ${cost}.  Must pay exact cost"), {
						total: {
							log: "${number} ${color}",
							args: {
								i18n: ['color'],
								number: number,
								color: color
							}
						},
						cost: cost
					}), 'error' );
					return false;
                }
				
				if (color != mtg.color && number > 0) {
                    this.showMessage( parent.format_string_recursive( _("Gizmo is ${mtgcolor}.  Cannot pay ${number} ${color}"), {
						i18n: ['mtgcolor', 'color'],
						mtgcolor: mtg.color,
						number: number,
						color: color
					}), 'error' );
					return false;
                }
            }
        }
		return true;
	},
	autoselectSpend: function() {
		if (Game.selected_card_id > 100) {
			let sel_color = Gizmo.details(Game.selected_card_id).color;
			let cost = Builder.getCost( Gizmo.details(Game.selected_card_id) );
			let spheres = this.getPlayerSpheresOfColor(Game.activePlayer, sel_color);
			var i=0;
			while (i<cost && i<spheres.length) {
				this.spendEnergy(spheres[i]);
				i++;
			}
		}
	},
	autoDeselectSpend: function() {
		if (Game.selected_card_id > 100) {
			let sel_color = Gizmo.details(Game.selected_card_id).color;
			let cost = Builder.getCost( Gizmo.details(Game.selected_card_id) );

			if (sel_color == 'multi') {

				let sel_energy = dojo.query('#energy_ring .token.selected');
				console.log('autoDeselectSpend', sel_color, cost, sel_energy);
				while (this.getMultiPower() > cost && sel_energy.length > 0) {
					this.despendEnergy( Energy.getIdOfEle(sel_energy.pop().id) );
				}

			} else {

				let sel_energy = dojo.query('#energy_ring .'+sel_color+'_token.selected');
				console.log('autoDeselectSpend', sel_color, this.spending_power[sel_color], cost, sel_energy);
				while (this.spending_power[sel_color] > cost && sel_energy.length > 0) {
					this.despendEnergy( Energy.getIdOfEle(sel_energy.pop().id) );
				}
			}
		}
	},
	getPlayerSpheresOfColor: function( player, color ) {
		console.log(this.temp_energy);
		var ret = this.temp_energy.filter(function(t) {
			return (Energy.getColor(t) == color || color == 'multi') && !dojo.hasClass(t, 'convert_from');
		});
		console.log(ret);

		if (this.sphere_counts && this.sphere_counts[player] && this.sphere_counts[player].spheres) {
			console.log('getPlayerSphereCount(' + player + ',' + color + ')=' + this.sphere_counts[player][color]);
			ret = ret.concat( 
				this.sphere_counts[player].spheres.filter(spid => (Energy.getColor(spid) == color || color == 'multi') && $(Energy.getEleId(spid)) && !dojo.hasClass(Energy.getEleId(spid), 'convert_from')) 
			);
		}
		console.log(ret);
		return ret;
	},
	addSupportedGizmo: function( parent, child ) {
		// Add the supported gizmo
		if (this.active_converters[parent].supporteds && this.active_converters[parent].supporteds.indexOf(child) < 0) {
			this.active_converters[parent].supporteds.push( child );
		} else {
			this.active_converters[parent].supporteds = [ child ];					
		}
	},
	applyColorConverter: function( gizmo_id, from_color, to_color, supported_gizmo_id, parent ) {
		console.log(this.active_converters[gizmo_id]);
		let mt_gizmo = Gizmo.details(gizmo_id);		
		var do_convert;
		var selClass;
		if (!this.active_converters[gizmo_id])
			this.active_converters[gizmo_id] = {};
		else if (this.active_converters[gizmo_id].picking) {
			this.picking = 0;
			this.active_converters[gizmo_id].picking = false;
			if (!from_color) {
				from_color = this.active_converters[gizmo_id].from ? this.active_converters[gizmo_id].from : mt_gizmo.convert_from;
			}
			dojo.removeClass( Gizmo.getEleId(gizmo_id), 'half_selected' );
		}
		
		// if (from_color == to_color) {
		// 	//this.showMessage( parent.format_string_recursive( "Energy is already ${to_color}!", {i18n: ['to_color'], to_color: to_color}), "error");
		// 	return;		
		// } else 
		if (!this.active_converters[gizmo_id].to_number) {
			Object.assign( 
				this.active_converters[gizmo_id], { 
					from: from_color,
					to_color: to_color,
					to_number: 1
				});
			Builder.assignOrder(gizmo_id);
			do_convert = true;
			selClass = mt_gizmo.convert_to == 'any2' ? 'half_selected' : 'selected';	
		} else if (mt_gizmo.convert_to == 'any2' && !this.active_converters[gizmo_id].second_convert) {
			this.active_converters[gizmo_id].second_convert = {
				from: from_color,
				to_color: to_color,
				to_number: 1
			}
			Builder.assignOrder(gizmo_id, true);
			do_convert = true;
			selClass = 'selected';
		} else {
			Builder.deselectConverter( gizmo_id, parent );
			return;
		}
		
		if (do_convert) {
			this.incSpending(to_color, 1);
			this.decSpending(from_color, 1);
			dojo.addClass(Gizmo.getEleId(gizmo_id), selClass);
		}
		
		if (supported_gizmo_id > 0) {
			this.addSupportedGizmo(gizmo_id, supported_gizmo_id);
		}
		this.autoDeselectSpend();
		this.refreshHeader(parent);
	},
	applyDoubleConverter: function( gizmo_id, color, parent ) {
		let mt_gizmo = Gizmo.details(gizmo_id);
		var selClass;
		if (!this.active_converters[gizmo_id]) {
			this.active_converters[gizmo_id] = {};
		} else if (this.active_converters[gizmo_id].picking) {
			this.picking = 0;	
			this.active_converters[gizmo_id].picking = false;
		}

		if (!this.active_converters[gizmo_id].order)
			Builder.assignOrder(gizmo_id);
		
		if (!this.active_converters[gizmo_id].to_number) {
			Object.assign(
				this.active_converters[gizmo_id], {
					from: color,
					to_color: color,
					to_number: 2,
					used: 0
				});
			if (mt_gizmo.convert_from.indexOf(',') > 0)
				selClass = 'half_selected';
			else
				selClass = 'selected';

			Builder.assignOrder(gizmo_id);
			this.incSpending(color, 1);
		} else if (mt_gizmo.convert_from.indexOf(',') > 0 && !this.active_converters[gizmo_id].second_convert) {
			this.active_converters[gizmo_id].second_convert = {
				from: color,
				to_color: color,
				to_number: 2,
				used: 0
			};
			Builder.assignOrder(gizmo_id, true);
			selClass = 'selected';
			this.incSpending(color, 1);
		} else {
			Builder.deselectConverter(gizmo_id, parent);
			return;
		}	
		let id1 = Energy.getTempId(gizmo_id, color);
		let h1 = Energy.getTempEnergyHtml(id1, gizmo_id, color, 'convert_to', parent);
		dojo.place(h1, Gizmo.getEleId(gizmo_id));
		dojo.connect( $(id1), 'onclick', parent, 'onEnergySelect' );
		let id2 = Energy.getTempId(gizmo_id, color);
		let h2 = Energy.getTempEnergyHtml(id2, gizmo_id, color, 'convert_to', parent);
		dojo.place(h2, Gizmo.getEleId(gizmo_id));
		dojo.connect( $(id2), 'onclick', parent, 'onEnergySelect' );
		this.temp_energy.push(id1, id2);

		dojo.removeClass( Gizmo.getEleId(gizmo_id), 'half_selected' );	
		dojo.addClass( Gizmo.getEleId(gizmo_id), selClass);
		this.autoDeselectSpend();
		this.refreshHeader(parent);
	},
	deselectAllConverters: function(parent) {
		for (let gid in this.active_converters) {
			this.deselectConverter( gid, parent);
		}
	},
	returnEnergyToRing: function(parent, energy_id) {
		parent.attachToNewParentNoDestroy( energy_id, 'energy_ring' );
		Builder.despendEnergy(Energy.getIdOfEle(energy_id));
		dojo.removeClass(energy_id, 'convert_from');
		dojo.removeClass(energy_id, 'f2');

		let anim = parent.slideToObject( $(energy_id), $('energy_ring') );
		anim.onEnd = function(parent, id) {
			return function() {
				parent.placeInZoneNoDestroy.call( Game.zones['energy_ring'], id);
				Game.anim_lock = false;
			}
		}(parent, energy_id);
		Game.anim_lock = true;
		anim.play();
	},

	deselectConverter: function ( gizmo_id, parent ) {
		var cdets = this.active_converters[gizmo_id];
		if (cdets) {
			cdets.deselecting = true;
			if (cdets.picking && gizmo_id == Builder.picking) {
				dojo.removeClass( 'energy_ring', 'half_selected' );	
				this.picking = 0;
				Energy.hidePicker(parent);
			} 
			if (cdets.to_number > 0) {				
				if (cdets.supporteds && cdets.supporteds.length > 0) {
					for (var i=0; i<cdets.supporteds.length; i++) {
						var gid = cdets.supporteds[i];
						console.log("automatically deselecting supported[" + i + "]=" + gid);
						if (this.active_converters[gid] && !this.active_converters[gid].deselecting)
							this.deselectConverter( gid, parent );
					}
				}
				console.log(this.spending_power);

				this.incSpending(cdets.from, 1);
				this.decSpending(cdets.to_color, cdets.to_number);

				if (cdets.second_convert) {
					this.incSpending(cdets.second_convert.from, 1);
					this.decSpending(cdets.second_convert.to_color, cdets.second_convert.to_number);
				}
			}
			console.log("DESELECTING " + gizmo_id);
			console.log(cdets);
			dojo.removeClass( Gizmo.getEleId(gizmo_id), 'half_selected' );				
			dojo.removeClass( Gizmo.getEleId(gizmo_id), 'selected');
			console.log(this.temp_energy);
			dojo.query( dojo.string.substitute("#${id} .token", {id: Gizmo.getEleId(gizmo_id)})).forEach(function (energy) {
				console.log(energy);
				if (energy.classList.contains('ring')) {
					console.log('has ring');
					Builder.returnEnergyToRing(parent, energy.id);					
				} else if (cdets[energy.id] && energy.classList.contains('convert_from')) {
					dojo.removeClass(energy, 'convert_from');
					dojo.removeClass(energy, 'f2');
					dojo.place(energy, Gizmo.getEleId( cdets[energy.id] ));
					dojo.addClass(energy, 'convert_to');			
				} else {
					let iNrg = Builder.temp_energy.findIndex(t => t == energy.id);
					if (iNrg >= 0)
						Builder.temp_energy.splice(iNrg, 1);
						
					dojo.destroy(energy);
				}
			});

			delete this.active_converters[gizmo_id];
			this.refreshHeader(parent);
		}
	},
	trySelectColor: function( from_color ) {
		let spheres = this.getPlayerSpheresOfColor(Game.activePlayer, from_color);
		if (spheres && spheres.length > 0) {
			let first = spheres[0];
			this.spendEnergy(first);
			console.log("trySelectColor returned true");
			return true;
		} else {
			console.log("trySelectColor returned false");
			return false;
		}
	},
	tryUseFromEnergy: function( gizmo_id ) {
		let isD = Gizmo.isDuplicator(gizmo_id);
		if (isD && this.active_converters[gizmo_id].used < 2) {
			return true;
		}
	},
	isConverterFullyOn: function(gizmo_id) {
		let mtg = Gizmo.details(gizmo_id);
		switch (mtg.convert_to) {
			case 'two':
			case '2':
				if (mtg.convert_from.indexOf(',') > 0)
					return !!this.active_converters[gizmo_id].second_convert;
			case 'any':
				return true;
			case 'any2':
				return !!this.active_converters[gizmo_id].second_convert;
			default:
				return false;
		}
	},
	checkApplyDiscounts: function() {
		if (Game.selected_card_id > 100) {
			let player_upgrades = dojo.query("#gizmos_board #upgrade_"+Game.activePlayer+" .discount");
			if (player_upgrades && player_upgrades.length > 0) {
				var discountIds = [];
				if (Gizmo.hasResearchDiscount()) {
					discountIds = discountIds.concat(Const.GIDs_Discount_FromResearch);
				}
				if (Gizmo.hasFileDiscount()) {
					discountIds = discountIds.concat(Const.GIDS_Discount_FromFile);
				}
				if (Gizmo.hasLvl2Discount()) {
					discountIds = discountIds.concat(Const.GIDs_Discount_Lvl2);
				}
				for (var i=0; i<player_upgrades.length; i++) {
					let ele_id = player_upgrades[i].id;
					let gid = Gizmo.getIdOfEle(ele_id);
					var debug = gid + "? ";
					if (discountIds.findIndex(id => id == gid) >= 0) {
						dojo.addClass(ele_id, 'selected');
						dojo.addClass(ele_id, 'selectable');
						Builder.discount++;
						debug += "YES -> discount";
					} else {
						debug += "NO";
					}
					console.log(debug);
				}
			}
		}
	},
	
	toggleConverter: function( gizmo_id, parent, picked_sphere_id ) {
		this.parent = parent;
		let mt_gizmo = Gizmo.details(gizmo_id);
		
		if ( this.picking > 0 && this.picking != gizmo_id ) {
			this.deselectConverter(this.picking, parent);
		} else if ( !picked_sphere_id && this.active_converters[gizmo_id] && (Builder.isConverterFullyOn(gizmo_id) || this.active_converters[gizmo_id].picking) ) {
			//console.log("deselecting...");
			this.deselectConverter( gizmo_id, parent );
		} else {
			if (mt_gizmo.effect_type == 'converter') {
				let sel_gizmo_id = Game.selected_card_id;
				if (sel_gizmo_id < 100) {
					//this.showMessage(_("Gizmo is not selected, nothing to convert"), "error");					
				} else {
					let pid = Game.activePlayer;
					let from_color = mt_gizmo.convert_from;
					let spheres = this.sphere_counts[pid];
					//console.log("checking from_color: " + from_color);
					if (from_color == 'any') {
						if (picked_sphere_id) {
							from_color = Energy.getColor(picked_sphere_id);
							//console.log("set from_color = color(picked_sphere_id): " + from_color);
						} else {							
							Builder.updateDescription(parent, _('Select an energy to convert'), gizmo_id);							
						}						
					} else if (from_color.indexOf(',') > 0) {
						let colors = from_color.split(',');
						let selColor = this.active_converters[gizmo_id]?.from;
						if (selColor) {
							let iC = colors.indexOf(selColor);
							colors.splice(iC, 1);
						}
						if (picked_sphere_id) {
							let spColor = Energy.getColor(picked_sphere_id);
							console.log('color is '+ spColor);
							if (colors.indexOf(spColor) < 0) {
								this.showMessage( parent.format_string_recursive( _("Must select ${colors} energy"), {
									colors: Energy.getColorsArgs(colors)
								}), "error");
								return;
							} else {
								from_color = spColor;
							}
						} else {
							Builder.updateDescription(parent, parent.format_string_recursive( _("Select a ${colors} energy to duplicate"), {
								colors: Energy.getColorsArgs(colors)
							}), gizmo_id);
						}
					}					
					
					let c_to = mt_gizmo.convert_to;
					let mt_sel_gizmo = Gizmo.details(sel_gizmo_id);
					let cost = Builder.getCost( mt_sel_gizmo );
					if (cost == 0) {
						this.showMessage(_("Gizmo costs 0; cannot convert"), "error");
					} else if (from_color == 'any' || from_color.indexOf(',') > 0) {} // waiting on pick energy
					else if ((this.spending_power[from_color] && this.spending_power[from_color] > 0)
						|| Builder.trySelectColor(from_color)) {
						//console.log("player has " +spheres[from_color]+from_color + " energy");
						if (c_to == 'any' || c_to == 'any2') {
							if (from_color == mt_sel_gizmo.color && cost == 1) {
								this.showMessage( parent.format_string_recursive( _("Selected gizmo is already ${from_color}; no reason to convert"), 
									{i18n: ['from_color'], from_color: from_color} ), "error");
								return;
							}

							this.slideEnergyToConverter(pid, gizmo_id, from_color, picked_sphere_id, parent);
							if (mt_gizmo.convert_from == 'any') {
								this.active_converters[gizmo_id].from = from_color;
							}
							
							//show a selection next to converter
							if (cost > 1) {
								Energy.showPicker(pid, gizmo_id, parent);
								Builder.updateDescription(parent, _('Convert to which color?'), gizmo_id);
							} else {
								// Cost 1 -> automatically convert the selected energy to the color of the gizmo
								let nrg_id = Energy.getTempId(gizmo_id, mt_sel_gizmo.color);
								let nrg_html = Energy.getTempEnergyHtml(nrg_id, gizmo_id, mt_sel_gizmo.color, 'convert_to', parent);
								dojo.place(nrg_html, Gizmo.getEleId(gizmo_id));
								this.temp_energy.push(nrg_id);
								dojo.connect( nrg_id, 'onclick', parent, 'onEnergySelect' );
								// if (picked_sphere_id > 0) {
								// 	this.deselectConverter(gizmo_id, parent);
								// }
								Builder.applyColorConverter( gizmo_id, from_color, mt_sel_gizmo.color, null, parent );
							}
						} else if (c_to == 'two' || c_to == 2) {
							if (cost < 2) {
								this.showMessage( dojo.string.substitute( _("Selected gizmo only costs ${cost}; no reason to convert"), {cost: cost} ), "error");						
							} else {								
								this.slideEnergyToConverter(pid, gizmo_id, from_color, picked_sphere_id, parent);
								Builder.applyDoubleConverter(gizmo_id, from_color, parent);
							}						
						} else {
							// Unexpected error does not need translating
							this.showMessage("Gizmo " + gizmo_id + " has unsupported convert_to: " + c_to, "error");							
						}
					} else {
						this.showMessage( parent.format_string_recursive( _("You do not have any ${from_color} energy to convert"), {i18n: ['from_color'], from_color: from_color}), "error");
					}
				}
			} else {		
				return false;
			}
		}
		Builder.handleButtonDisabled();
		//this.logEnergy();
	},

	slideEnergyToConverter: function(pid, gizmo_id, from_color, picked_sphere_id, parent) {
		let spid = picked_sphere_id ? picked_sphere_id : Builder.getPlayerSpheresOfColor(pid, from_color)[0];
		if (!this.active_converters[gizmo_id]) {
			this.active_converters[gizmo_id] = {};
		}
		let sp_ele_id = Energy.getEleId(spid);
		console.log("Trying to slide: "+sp_ele_id, this.active_converters[gizmo_id])
		//if ($(sp_ele_id)) {
			let parentGizmoId = Gizmo.getIdOfEle( $(sp_ele_id).parentNode.id );
			parent.removeTooltip( sp_ele_id );
			parent.attachToNewParentNoDestroy( sp_ele_id, Gizmo.getEleId(gizmo_id) );
			if ( dojo.hasClass(sp_ele_id, 'convert_to') ) {
				dojo.removeClass( sp_ele_id, 'convert_to' );
				this.addSupportedGizmo(parentGizmoId, gizmo_id);
				this.active_converters[gizmo_id][spid] = parentGizmoId;
			} else {
				this.spendEnergy(spid);
				Game.zones['energy_ring'].removeFromZone(sp_ele_id);
			}
			
			dojo.addClass( Energy.getEleId(spid), 'convert_from' );
			// Edge case for supporting any2 converters (converts two energies to any)
			if (Builder.active_converters[gizmo_id].to_number > 0) {
				dojo.addClass( Energy.getEleId(spid), 'f2' );
			}

			let anim = parent.slideToObjectPos( $(sp_ele_id), $(Gizmo.getEleId(gizmo_id)), 10, 10 );
			anim.onEnd = function() {
				dojo.attr( Energy.getEleId(spid), 'style', 'position:absolute;' );
				Game.anim_lock = false;
			};
			Game.anim_lock = true;
			anim.play();
		//}
	},
	
	handleButtonDisabled: function() {
		if ( $('button_build') ) {
			if (Builder.canPurchase()) {
				dojo.removeClass( 'button_build', 'disabled');
			} else {
				dojo.addClass( 'button_build', 'disabled');						
			}
		}
		if ( $('button_file')) {
			if ( Game.selected_card_id && $(Gizmo.getEleId(Game.selected_card_id)) && dojo.hasClass(Gizmo.getEleId(Game.selected_card_id), 'filed') ) {
				dojo.addClass( 'button_file', 'disabled');
			} else {
				dojo.removeClass( 'button_file', 'disabled');
			}
		}		
	},

	validateConvertColor: function(ele_id, parent) {
		let color = Energy.getColor( ele_id ); // ele is a temp energy
		let fc = Builder.active_converters[Builder.picking].from;
		let gc = Gizmo.details( Builder.picking ).convert_from;
		console.log("energy color ?= from_color OR gizmo_color", color, fc, gc, this.picking);
		if (color == fc || color == gc) {
			this.showMessage( parent.format_string_recursive(_("Already ${color}!"), {
				i18n: ['color'],
				color: color
			}), 'error');
		} else {
			return true;
		}
	},

	resetRingEnergies: function(parent) {
		dojo.query('.card .token.ring').forEach(function(energy) {
			Builder.returnEnergyToRing(parent, energy.id);
		});
		Energy.hidePicker();
	}

};

let Gizmo = {
	mt_gizmos: {},
	cards: {},
	getEleId: function(id) {
		if ((typeof id === 'string' || id instanceof String) && id.indexOf('_') > 0) {
			return id;
		} else if (id < 4) {
			return "deck_"+id;
		} else {
			return "card_"+id;
		}
	},
	getIdOfEle: function(ele_id) {
		if (ele_id.length == 6) {
			return ele_id.slice(-1);
		} else if (ele_id.length == 8) {
			return ele_id.slice(-3);			
		} else {
			return "???";
		}
	},
	details: function(id) {		
		return this.mt_gizmos[id];
	},
	getPlayersCards: function(player_id, of_type) {
		let pcards = this.cards[player_id];
		if (pcards) {
			if (of_type == 'filed') {
				return pcards.filed;
			} else if (of_type) {
				return pcards.built_by_type[of_type];
			} else {
				return pcards.built;
			}
		}
	},
	isDuplicator: function(id) {
		if (id.indexOf('card_') == 0) {
			id = Gizmo.getIdOfEle(id);
		}
		let gizmo = this.mt_gizmos[id];
		return gizmo.convert_to && (gizmo.convert_to == 'any2' || gizmo.convert_to == 'two' || gizmo.convert_to == 2);
	},
	hasFileDiscount: function() {
		return Game.selected_card_id && $(Gizmo.getEleId(Game.selected_card_id)) && dojo.hasClass( Gizmo.getEleId(Game.selected_card_id), 'filed');
	},
	hasResearchDiscount: function() {
		if (Game.selected_card_id)
		return Game.selected_card_id && $(Gizmo.getEleId(Game.selected_card_id)) && dojo.hasClass( Gizmo.getEleId(Game.selected_card_id), 'researched');
	},
	hasLvl2Discount: function() {
		return Game.selected_card_id && Gizmo.details(Game.selected_card_id).level == 2;
	},
	isDiscountUpgrade: function(gid) {
		return Game.selected_card_id && Const.GIDs_Discount_All.findIndex(id => id == gid) >= 0;
	},
	levelNumerals: function(level) {
		var ret = "I";
		var i = 1;
		while (i < level) {
			ret += "I";
			i++;
		}
		return ret;
	}
};

let Energy = {
	colors: {
		0: 'red',
		1: 'blue',
		2: 'black',
		3: 'yellow'
	},	
	getLogSphereHtml: function(color) {
		return "<div class='gzs_log_token gzs_log_"+color+"'></div>";
	},
	getEleId: function(id) {
		if ((typeof id === 'string' || id instanceof String) && id.indexOf('_') > 0) {
			return id;
		} else {
			return "sphere_"+id;
		}
	},
	getIdOfEle: function(ele_id) {
		let i = ele_id.indexOf('_');
		if (i < 0) {
			return "???";		
		} else {
			return ele_id.substring(i+1);
		}
	},
	getColor: function ( id ) {
		if (typeof id === 'string' || id instanceof String) {
			if ( id.indexOf('_') > 0 )
				return id.substring(id.indexOf('_')+1);
			else
				id = parseInt( id ); 
		}
		return this.colors[(id%4)];
	},
	getEleColor: function ( target ) {
		for (var i=0; i<target.classList.length; i++) {
			var cl = target.classList[i];
			var it = cl.indexOf('_token');
			if (it > 0) {
				return cl.substr(0, it);
			}
		}
		return 'ERROR';
	},
	getPickerHtml: function (gid, parent) {
		let temps = dojo.query( dojo.string.substitute(".${gid}.tempnrg", {gid: gid}) ).length+1;
		return dojo.string.substitute( '\<div id="color_picker">\
					<div id="${gid}-${temps}_black" class="picker black_token token tempnrg convert_to ${gid} t${temps} ${colorblind}"> </div>\
					<div id="${gid}-${temps}_blue" class="picker blue_token token tempnrg convert_to ${gid} t${temps} ${colorblind}"> </div>\
					<div id="${gid}-${temps}_red" class="picker red_token token tempnrg convert_to ${gid} t${temps} ${colorblind}"> </div>\
					<div id="${gid}-${temps}_yellow" class="picker yellow_token token tempnrg convert_to ${gid} t${temps} ${colorblind}"> </div>\
				</div>', {gid: gid, temps: temps, colorblind: (Game.isColorblindFriendly(parent) ? 'colorblind' : '')} );
	},
	hidePicker: function(parent) {
		console.log("hide picker");
		if ($('color_picker')) {
			dojo.destroy('color_picker');
		}
		console.log("hide picker DONE");
	},
	showPicker: function(pid, gid, parent) {
		console.log("show picker");
		Energy.hidePicker(parent);
		dojo.place( Energy.getPickerHtml(gid, parent), 'converter_'+pid );
		let styleTop = $(Gizmo.getEleId(gid)).style.top;
		let styleLeft = $(Gizmo.getEleId(gid)).offsetWidth;
		dojo.style( $('color_picker'), 'top', styleTop );
		dojo.style( $('color_picker'), 'left', styleLeft+"px" );
		dojo.query('#color_picker .picker').connect('onclick', parent, 'onEnergySelect' );
		console.log("show picker DONE");
	},	
	getEnergyHtml: function (sphere_id, classes, parent) {
		if (!classes)
			classes = "";
		if (Game.isColorblindFriendly(parent))
			classes += " colorblind";

		return Energy.energyHtmlTplt(sphere_id, Energy.getColor(sphere_id), (classes ? classes : ''));
	},
	energyHtmlTplt: function(id, color, other_classes) {
		return dojo.string.substitute('<div id="sphere_${id}" class="token ${color}_token ${other_classes}"></div>', {id: id, color: color, other_classes: other_classes});
	},
	getTempId: function(gid, color) {
		var i=1;
		var id = dojo.string.substitute('${gid}_${color}', {gid: gid, color: color});
		while ($(id)) {
			i++;
			id = dojo.string.substitute('${gid}-${i}_${color}', {gid: gid, i: i, color: color});
		}
		return id;
	},
	getTempEnergyHtml: function(id, gid, color, other_classes, parent) {
		if (!other_classes)
			other_classes = "";
		if (Game.isColorblindFriendly(parent))
			other_classes += " colorblind";

		let temps = dojo.query( dojo.string.substitute( '.${gid}.tempnrg', {gid: gid} )).length+1;
		return dojo.string.substitute( '<div id="${id}" class="${color}_token token tempnrg ${other_classes} ${gid} t${temps}"> </div>', {id: id, color: color, other_classes: other_classes, gid: gid, temps: temps});
	},
	re_temp: /[0-9]{3}_[a-z]{3,6}/,
	isTemp: function(id) {
		return this.re_temp.test(id);
	},
	getGizmoIdOfTemp: function(id) {
		if (this.isTemp(id)) {
			return id.substring(0,3);
		} else {
			return "NOT_TEMP";
		}
	},
	getColorsArgs: function (arr) {		
		if (arr && arr.length == 1) {
			return  arr[0];
		} else if (arr.length == 2) {
			return {
				log: '${color1} ${or} ${color2}',
				args: {
					i18n: ['color1', 'or', 'color2'],
					color1: arr[0],
					or: 'or',
					color2: arr[1]
				}
			};
		} else {
			return "getColorsArrStr(UNEXPECTED): " + arr;
		}
	},

	getHtmlForTooltip: function(color, parent, isLogSize) {
		var tClass = isLogSize ? 'gzs_log_token' : 'gzs_tooltip_token';
		return "<div class='"+tClass+" gzs_log_"+color+(Game.isColorblindFriendly(parent) ? ' colorblind' : '')+"'></div>";
	}
};