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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * This file contains the import form class
 *
 * @package     mod_groupformation
 * @author      Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . "/lib/formslib.php");

/**
 * Imports the form
 *
 * @package     mod_groupformation
 * @copyright   2015 MoodlePeers
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_groupformation_import_form extends moodleform {

    /**
     * Adds elements to form
     *
     * @throws coding_exception
     */
    public function definition() {
        $maxbytes = 2 * 2000000;
        $mform = $this->_form;
        $mform->addElement('filepicker', 'userfile', get_string('file'), null, array(
            'maxbytes' => $maxbytes, 'accepted_types' => '*.xml'));

        $buttonarray = array();

        $buttonarray [] = &$mform->createElement('submit', 'submit', get_string('submit'));
        $buttonarray [] = &$mform->createElement('submit', 'cancel', get_string('cancel'));

        $mform->addGroup($buttonarray, 'buttonar', '', array(
            ' '), false);

        $mform->addElement('hidden', 'cmid', 0);
        $mform->setType('cmid', PARAM_TEXT);

        $mform->addElement('hidden', 'id', 0);
        $mform->setType('id', PARAM_TEXT);

        $mform->closeHeaderBefore('buttonar');

        $this->add_action_buttons(true);
    }

    /**
     * Adds error to element
     *
     * @param string $element
     * @param string $msg
     */
    public function set_error($element, $msg) {
        $this->_form->setElementError($element, $msg);
    }
}