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
 * Internal library of functions for module newmodule
 *
 * All the newmodule specific functions, needed to implement the module
 * logic, should go here. Never include this file from your lib.php!
 *
 * @package mod_groupformation
 * @copyright 2014 Nora Wester
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined ( 'MOODLE_INTERNAL' ) || die ();
/*
 * Does something really useful with the passed things
 *
 * @param array $things
 * @return object
 * function newmodule_do_something_useful(array $things) {
 * return new stdClass();
 * }
 */


require_once($CFG->dirroot.'/mod/groupformation/classes/logging/logging_controller.php');

/**
 * Adds jQuery
 *
 * @param unknown $PAGE        	
 * @param string $filename        	
 */
function addjQuery($PAGE, $filename = null) {
	$PAGE->requires->jquery ();
	$PAGE->requires->jquery_plugin ( 'ui' );
	$PAGE->requires->jquery_plugin ( 'ui-css' );
	
	if (! is_null ( $filename )) {
		$PAGE->requires->js ( '/mod/groupformation/js/' . $filename );
	}
}

/**
 * Logs action
 *
 * @param unknown $PAGE
 * @param string $filename
 */
function groupformation_log($userid, $groupformationid, $message) {
	
	$logging_controller = new mod_groupformation_logging_controller();
	
	return $logging_controller->handle($userid,$groupformationid,$message);
}

/**
 * Triggers event
 * 
 * @param stdClass $cm
 * @param stdClass $course
 * @param stdClass $groupformation
 * @param stdClass $context
 */
function groupformation_trigger_event($cm,$course,$groupformation,$context){
	$event = \mod_groupformation\event\course_module_viewed::create(array(
			'objectid' => $groupformation->id,
			'context' => $context,
	));
	$event->add_record_snapshot('course', $course);
	$event->add_record_snapshot($cm->modname, $groupformation);
	$event->trigger();
}


// function answeredAllQuestions($userid, $groupformationid) {
// 	global $DB;
// 	$categorysets = array (
// 			1 => array (
// 					'topic',
// 					'knowledge',
// 					'general',
// 					'grade',
// 					'team',
// 					'character',
// 					'motivation' 
// 			),
// 			2 => array (
// 					'topic',
// 					'knowledge',
// 					'general',
// 					'grade',
// 					'team',
// 					'character',
// 					'learning' 
// 			),
// 			3 => array (
// 					'topic',
// 					'general' 
// 			) 
// 	);
// 	$scenario = $DB->get_record ( 'groupformation', array (
// 			'id' => $groupformationid 
// 	) )->szenario;
// 	$categories = array ();
// 	foreach ( $DB->get_records ( 'groupformation_q_version' ) as $record ) {
// 		$categories [$record->category] = intval ( $record->numberofquestion );
// 	}
// 	$stats = array ();
// 	foreach ( $categories as $key => $value ) {
// 		if (in_array ( $key, $categorysets [$scenario] )) {
// 			$count = $DB->count_records ( 'groupformation_answer', array (
// 					'groupformation' => $groupformationid,
// 					'userid' => $userid,
// 					'category' => $key 
// 			) );
// 			if ($value - $count > 0) {
// 				return true;
// 			}
// 		}
// 	}
// 	return false;
// }
