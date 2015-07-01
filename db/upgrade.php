<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <http://www.gnu.org/licenses/>.
/**
 * This file keeps track of upgrades to the groupformation module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do. The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package mod_groupformation
 * @copyright 2014 Nora Wester
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined ( 'MOODLE_INTERNAL' ) || die ();
/**
 * Execute groupformation upgrade from the given old version
 *
 * @param int $oldversion        	
 * @return bool
 */
function xmldb_groupformation_upgrade($oldversion) {
	global $DB;
	$dbman = $DB->get_manager (); // Loads ddl manager and xmldb classes.
	
	if ($oldversion < 2015041701) {
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'szenario', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'grade' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'knowledge', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'szenario' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'knowledgelines', XMLDB_TYPE_TEXT, 'medium', null, null, null, null, 'knowledge' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'topics', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'knowledgelines' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'topiclines', XMLDB_TYPE_TEXT, 'medium', null, null, null, null, 'topics' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'maxmembers', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'topiclines' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'maxgroups', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'maxmembers' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'evaluationmethod', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'maxgroups' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		upgrade_mod_savepoint ( true, 2015041701, 'groupformation' );
	}
	
	if ($oldversion < 2015041900) {
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'groupoption', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '0', 'topiclines' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		upgrade_mod_savepoint ( true, 2015041900, 'groupformation' );
	}
	
	if ($oldversion < 2015051300) {
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'maxpoints', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, XMLDB_NOTNULL, null, '100', 'evaluationmethod' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		upgrade_mod_savepoint ( true, 2015051300, 'groupformation' );
	}
	
	if ($oldversion < 2015052802) {
		
		$table = new xmldb_table ( 'groupformation' );
		$field = new xmldb_field ( 'groupname', XMLDB_TYPE_TEXT, 'medium', null, null, null, 'group', 'maxgroups' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		upgrade_mod_savepoint ( true, 2015052802, 'groupformation' );
	}
	
	if ($oldversion < 2015060100) {
		
		$table = new xmldb_table ( 'groupformation_started' );
		$field = new xmldb_field ( 'timecompleted', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, '0', 'completed' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		$table = new xmldb_table ( 'groupformation_started' );
		$field = new xmldb_field ( 'groupid', XMLDB_TYPE_INTEGER, '10', XMLDB_UNSIGNED, null, null, null, 'timecompleted' );
		
		if (! $dbman->field_exists ( $table, $field )) {
			$dbman->add_field ( $table, $field );
		}
		
		upgrade_mod_savepoint ( true, 2015060100, 'groupformation' );
	}
	
	if ($oldversion < 2015060500) {
		
		$table = new xmldb_table('groupformation_logging');

        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);

       	$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        upgrade_mod_savepoint ( true, 2015060500, 'groupformation' );
	}
	
	if ($oldversion < 2015060501) {
		// Define field timestamp to be added to groupformation_logging.
		$table = new xmldb_table('groupformation_logging');
		$field = new xmldb_field('timestamp', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');
		
		// Conditionally launch add field timestamp.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field userid to be added to groupformation_logging.
		$table = new xmldb_table('groupformation_logging');
		$field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'timestamp');
		
		// Conditionally launch add field userid.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field groupformationid to be added to groupformation_logging.
		$table = new xmldb_table('groupformation_logging');
		$field = new xmldb_field('groupformationid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'userid');
		
		// Conditionally launch add field groupformationid.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field message to be added to groupformation_logging.
		$table = new xmldb_table('groupformation_logging');
		$field = new xmldb_field('message', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null, 'groupformationid');
		
		// Conditionally launch add field message.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
	
		// Groupformation savepoint reached.
		upgrade_mod_savepoint ( true, 2015060501, 'groupformation' );
	}
	
	if ($oldversion < 2015061700) {
	
	
		// Define table groupformation_logging to be created.
		$table = new xmldb_table('groupformation_jobs');
	
		// Adding fields to table groupformation_logging.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
	
		// Adding keys to table groupformation_logging.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
	
		// Conditionally launch create table for groupformation_logging.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
	
		// Groupformation savepoint reached.
		upgrade_mod_savepoint ( true, 2015061700, 'groupformation' );
	}
	
	if ($oldversion < 2015061801) {
		
		// Define field groupformationid to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('groupformationid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null, 'id');

        // Conditionally launch add field groupformationid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field waiting to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('waiting', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'groupformationid');
        
        // Conditionally launch add field waiting.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }
        
        // Define field started to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('started', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'waiting');
        
        // Conditionally launch add field started.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }
        
        // Define field aborted to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('aborted', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'started');
        
        // Conditionally launch add field aborted.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }
        
        // Define field done to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('done', XMLDB_TYPE_INTEGER, '1', null, null, null, '0', 'aborted');
        
        // Conditionally launch add field done.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }
        
        // Define field timecreated to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'done');
        
        // Conditionally launch add field timecreated.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }
        
        // Define field timestarted to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('timestarted', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'timecreated');
        
        // Conditionally launch add field timestarted.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }
        
        // Define field timefinished to be added to groupformation_jobs.
        $table = new xmldb_table('groupformation_jobs');
        $field = new xmldb_field('timefinished', XMLDB_TYPE_INTEGER, '10', null, null, null, '0', 'timestarted');
        
        // Conditionally launch add field timefinished.
        if (!$dbman->field_exists($table, $field)) {
        	$dbman->add_field($table, $field);
        }      
	
		// Groupformation savepoint reached.
		upgrade_mod_savepoint ( true, 2015061801, 'groupformation' );
	}
	
	if ($oldversion < 2015061809) {
	
		// Define field optionmax to be added to groupformation_motivation.
		$table = new xmldb_table('groupformation_motivation');
		$field = new xmldb_field('optionmax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'position');
	
		// Conditionally launch add field optionmax.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field id to be added to groupformation_team.
		$table = new xmldb_table('groupformation_team');
		$field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
		
		// Conditionally launch add field id.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field optionmax to be added to groupformation_grade.
		$table = new xmldb_table('groupformation_grade');
		$field = new xmldb_field('optionmax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'position');
		
		// Conditionally launch add field optionmax.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field id to be added to groupformation_learning.
		$table = new xmldb_table('groupformation_learning');
		$field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
		
		// Conditionally launch add field id.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field optionmax to be added to groupformation_character.
		$table = new xmldb_table('groupformation_character');
		$field = new xmldb_field('optionmax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0', 'position');
		
		// Conditionally launch add field optionmax.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define field id to be added to groupformation_general.
		$table = new xmldb_table('groupformation_general');
		$field = new xmldb_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null, null);
		
		// Conditionally launch add field id.
		if (!$dbman->field_exists($table, $field)) {
			$dbman->add_field($table, $field);
		}
		
		// Define table groupformation_srl to be created.
		$table = new xmldb_table('groupformation_srl');
		
		// Adding fields to table groupformation_srl.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
		$table->add_field('question', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
		$table->add_field('options', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
		$table->add_field('language', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
		$table->add_field('position', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('optionmax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		
		// Adding keys to table groupformation_srl.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		
		// Conditionally launch create table for groupformation_srl.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table groupformation_self to be created.
		$table = new xmldb_table('groupformation_self');
		
		// Adding fields to table groupformation_self.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
		$table->add_field('question', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
		$table->add_field('options', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
		$table->add_field('language', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
		$table->add_field('position', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('optionmax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		
		// Adding keys to table groupformation_self.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		
		// Conditionally launch create table for groupformation_self.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table groupformation_sellmo to be created.
		$table = new xmldb_table('groupformation_sellmo');
		
		// Adding fields to table groupformation_sellmo.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('type', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
		$table->add_field('question', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
		$table->add_field('options', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
		$table->add_field('language', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
		$table->add_field('position', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('optionmax', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		
		// Adding keys to table groupformation_sellmo.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		
		// Conditionally launch create table for groupformation_sellmo.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
	
		// Groupformation savepoint reached.
		upgrade_mod_savepoint(true, 2015061809, 'groupformation');
	}
	
	if ($oldversion < 2015070100) {
	
		// Define table groupformation_groups to be created.
		$table = new xmldb_table('groupformation_groups');
		
		// Adding fields to table groupformation_groups.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('groupformation', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
		$table->add_field('groupname', XMLDB_TYPE_CHAR, '255', null, null, null, null);
		
		// Adding keys to table groupformation_groups.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('groupformation', XMLDB_KEY_FOREIGN, array('groupformation'), 'groupformation', array('id'));
		
		// Conditionally launch create table for groupformation_groups.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}
		
		// Define table groupformation_group_users to be created.
		$table = new xmldb_table('groupformation_group_users');
		
		// Adding fields to table groupformation_group_users.
		$table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
		$table->add_field('groupformation', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
		$table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
		$table->add_field('groupid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
		
		// Adding keys to table groupformation_group_users.
		$table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
		$table->add_key('groupformation', XMLDB_KEY_FOREIGN, array('groupformation'), 'groupformation', array('id'));
		
		// Conditionally launch create table for groupformation_group_users.
		if (!$dbman->table_exists($table)) {
			$dbman->create_table($table);
		}		
	
		// Groupformation savepoint reached.
		upgrade_mod_savepoint(true, 2015070100, 'groupformation');
	}
	
	
	
	return true;
}
