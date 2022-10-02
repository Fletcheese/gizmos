
-- ------
-- BGA framework: © Gregory Isabelli <gisabelli@boardgamearena.com> & Emmanuel Colin <ecolin@boardgamearena.com>
-- gizmos implementation : © Fletcheese <1337ch33z@gmail.com>
-- 
-- This code has been produced on the BGA studio platform for use on http://boardgamearena.com.
-- See http://en.boardgamearena.com/#!doc/Studio for more information.
-- -----

ALTER TABLE `player` ADD `player_energy_limit` SMALLINT UNSIGNED NOT NULL DEFAULT '5';
ALTER TABLE `player` ADD `player_archive_limit` SMALLINT UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `player` ADD `player_research_quantity` SMALLINT UNSIGNED NOT NULL DEFAULT '3';
ALTER TABLE `player` ADD `victory_points` SMALLINT UNSIGNED NOT NULL DEFAULT '0';

CREATE TABLE IF NOT EXISTS `gizmo_cards` (
	`card_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	-- card_type === effect_type in materials - used for querying triggers
	`card_type` varchar(16) NOT NULL,
	-- card_type_arg is the assigned card id as seen in materials.  Hundreds digit is level
	`card_type_arg` int(11) NOT NULL,
	-- card_location can be deck_X, row_X, built, filed where X can be 1, 2, or 3 representing level decks
	`card_location` varchar(16) NOT NULL,
	-- card_location_arg is used for ordering within decks/row or owner player_id for built/filed
	`card_location_arg` int(11) NOT NULL,
	-- used to track which gizmos have met triggers on a given turn - resets during nextPlayer state
	`is_triggered` boolean NOT NULL DEFAULT 0,
	-- used to track which triggered gizmos have been used on a given turn - resets during nextPlayer state
	`is_used` boolean NOT NULL DEFAULT 0,
	PRIMARY KEY (`card_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- color of sphere is mt_colors[sphere_id % 4]
CREATE TABLE IF NOT EXISTS `sphere` (
	`sphere_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	-- location is one of 'dispenser', 'row', or '<player_id>'
	`location` varchar(16) NOT NULL,
	PRIMARY KEY (`sphere_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;