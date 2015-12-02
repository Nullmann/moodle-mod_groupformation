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
 *
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/storage_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/moodle_interface/user_manager.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/xml_writer.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/csv_writer.php');
require_once($CFG->dirroot . '/mod/groupformation/classes/util/template_builder.php');

class mod_groupformation_import_export_controller {
    private $store;
    private $user_manager;
    private $groupformationid;
    private $cm;
    private $cmid;
    private $view = null;

    /**
     * Constructs instance of import export controller
     *
     * @param integer $groupformationid
     */
    public function __construct($groupformationid, $cm) {
        $this->groupformationid = $groupformationid;
        $this->cmid = $cm->id;
        $this->cm = $cm;

        $this->user_manager = new mod_groupformation_user_manager ($groupformationid);
        $this->store = new mod_groupformation_storage_manager ($groupformationid);
    }

    /**
     * Generates answers and creates a file for download
     *
     * @param integer $userid
     * @return string
     */
    private function generate_answers_url($userid, $categories) {
        $xmlwriter = new mod_groupformation_xml_writer ();

        // Generate content for answer file for export.
        $content = $xmlwriter->write($userid, $this->groupformationid, $categories);

        $filename = 'exportable_answers.xml';

        $context = context_module::instance($this->cmid);

        $fileinfo = array(
            'contextid' => $context->id, 'component' => 'mod_groupformation', 'filearea' => 'groupformation_answers',
            'itemid' => $userid, 'filepath' => '/', 'filename' => $filename);

        $file_storage = get_file_storage();

        if ($file_storage->file_exists($fileinfo ['contextid'], $fileinfo ['component'], $fileinfo ['filearea'],
                                       $fileinfo ['itemid'], $fileinfo ['filepath'], $fileinfo ['filename'])
        ) {
            $file = $file_storage->get_file($fileinfo ['contextid'], $fileinfo ['component'], $fileinfo ['filearea'],
                                            $fileinfo ['itemid'], $fileinfo ['filepath'], $fileinfo ['filename']);
            $file->delete();
        }

        $file = $file_storage->create_file_from_string($fileinfo, $content);

        $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                                               $file->get_itemid(), $file->get_filepath(), $file->get_filename());

        $urlstring = $url->out();

        return $urlstring;
    }

    /**
     * Renders import and export options
     *
     * @param integer $userid
     */
    public function render_overview($userid) {
        global $DB;

        $this->view = new mod_groupformation_template_builder ();
        $this->view->set_template('wrapper_student_import_export');

        $export_description = get_string('export_description_no', 'groupformation');
        $export_button = false;
        $export_url = '';

        $categories = $this->store->get_exportable_categories();

        if ($this->user_manager->already_answered($userid, $categories)) {

            $url = $this->generate_answers_url($userid, $categories);

            $export_description = get_string('export_description_yes', 'groupformation');
            $export_button = true;
            $export_url = $url;
        }

        $import_button = true;
        $import_description = get_string('import_description_yes', 'groupformation');

        if (!$this->store->is_questionnaire_available() || $this->user_manager->is_completed($userid)) {
            $import_button = false;
            $import_description = get_string('import_description_no', 'groupformation');

        }

        $this->view->assign('export_description', $export_description);
        $this->view->assign('export_button', $export_button);
        $this->view->assign('export_url', $export_url);

        $this->view->assign('import_description', $import_description);
        $url = new moodle_url ('/mod/groupformation/import_view.php', array(
            'id' => $this->cmid));
        $this->view->assign('import_form', $url->out());
        $this->view->assign('import_button', $import_button);

        return $this->view->load_template();
    }

    /**
     * Renders two-parted template with form
     *
     * @param unknown $mform
     * @param string $show_warning
     */
    public function render_form($mform, $show_warning = false) {
        $this->view = new mod_groupformation_template_builder ();
        $this->view->set_template('student_import_form_header');
        $this->view->assign('file_error', $show_warning);

        echo $this->view->load_template();

        $mform->display();

        $this->view = new mod_groupformation_template_builder ();
        $this->view->set_template('student_import_form_footer');
        echo $this->view->load_template();
    }

    /**
     * Renders result page of import
     *
     * @param unknown $mform
     * @param string $show_warning
     */
    public function render_result($successful) {
        $this->view = new mod_groupformation_template_builder ();
        $this->view->set_template('student_import_result');

        $url = new moodle_url ('/mod/groupformation/import_view.php', array(
            'id' => $this->cmid));

        $viewurl = new moodle_url ('/mod/groupformation/view.php', array(
            'id' => $this->cmid, 'do_show' => 'view'));
        $this->view->assign('import_export_url', $viewurl->out());
        $this->view->assign('import_form', $url->out());
        $this->view->assign('successful', $successful);

        echo $this->view->load_template();

    }

    /**
     * Handles xml string and import
     *
     * @param string $content
     * @throws InvalidArgumentException
     */
    public function import_xml($content) {
        global $DB, $USER, $CFG;

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);

        if (!$xml) {
            $errors = libxml_get_errors();

            foreach ($errors as $error) {
                throw new InvalidArgumentException ("Wrong format");
            }

            libxml_clear_errors();
        }

        $name = $xml->getName();
        if (!($name == 'answers')) {
            throw new InvalidArgumentException ("Wrong format");
        }

        $attr = $xml->attributes();

        $userid = intval($attr->userid);
        if (!($userid == $USER->id)) {
            throw new InvalidArgumentException ("Wrong format");
        }

        $categories = $this->store->get_categories();

        $all_records = array();

        foreach ($xml->categories->category as $category) {

            $name = strval($category->attributes()->name);

            // Check if category is needed to be imported.
            if (in_array($name, $categories)) {

                // Try importing answers.
                $records = $this->create_answer_records($name, $category->answer);

                $all_records = array_merge($all_records, $records);
            }
        }

        $DB->insert_records('groupformation_answer', $all_records);
        $this->user_manager->set_answer_count($userid);
    }

    /**
     * Creates answer records for import
     *
     * @param string $category
     * @param array $answers
     * @throws InvalidArgumentException
     * @return array
     */
    public function create_answer_records($category, $answers) {
        global $DB, $USER;

        $userid = $USER->id;

        $all_records = array();
        $questionids = array();

        foreach ($answers as $answer) {

            $attr = $answer->attributes();
            $questionid = intval($attr->questionid);
            $value = intval($attr->value);

            if ($questionid <= 0 || $value <= 0 || in_array($questionid, $questionids)) {
                throw new InvalidArgumentException ("Wrong format");
            }

            $questionids [] = $questionid;

            if ($record = $DB->get_record('groupformation_answer', array(
                'userid' => $userid, 'category' => $category, 'questionid' => $questionid))
            ) {

                // Nothing to do here.
            } else {

                // Create record for import.
                $record = new stdClass ();

                $record->groupformation = $this->groupformationid;
                $record->category = $category;
                $record->questionid = $questionid;
                $record->userid = $userid;
                $record->answer = $value;

                $all_records [] = $record;
            }
        }

        return $all_records;
    }

    /**
     * Renders the export options for teachers
     */
    public function render_export() {
        $this->view = new mod_groupformation_template_builder ();
        $this->view->set_template('wrapper_teacher_export');

        $export_answers = get_string('export_answers', 'groupformation');
        $export_answers_url = $this->generate_export_url('answers');
        $this->view->assign('export_answers', $export_answers);
        $this->view->assign('export_answers_url', $export_answers_url);

        $export_users = get_string('export_users', 'groupformation');
        $export_users_url = $this->generate_export_url('users');
        $this->view->assign('export_users', $export_users);
        $this->view->assign('export_users_url', $export_users_url);

        $export_groups = get_string('export_groups', 'groupformation');
        $export_groups_url = $this->generate_export_url('groups');
        $this->view->assign('export_groups', $export_groups);
        $this->view->assign('export_groups_url', $export_groups_url);

        $export_group_users = get_string('export_group_users', 'groupformation');
        $export_group_users_url = $this->generate_export_url('group_users');
        $this->view->assign('export_group_users', $export_group_users);
        $this->view->assign('export_group_users_url', $export_group_users_url);

        $export_logging = get_string('export_logging', 'groupformation');
        $export_logging_url = $this->generate_export_url('logging');
        $this->view->assign('export_logging', $export_logging);
        $this->view->assign('export_logging_url', $export_logging_url);

        return $this->view->load_template();
    }

    /**
     * Generates export url for csv file
     *
     * @param string $type
     * @return string
     * @throws file_exception
     * @throws stored_file_creation_exception
     */
    private function generate_export_url($type = 'answers') {
        $csvwriter = new mod_groupformation_csv_writer ($this->cm, $this->groupformationid);

        // Generate content for answer file for export.
        $content = $csvwriter->get_data($type);

        $filename = 'archived_' . $type . '.csv';

        $context = context_module::instance($this->cmid);

        $fileinfo = array(
            'contextid' => $context->id, 'component' => 'mod_groupformation', 'filearea' => 'groupformation_answers',
            'itemid' => $this->groupformationid, 'filepath' => '/', 'filename' => $filename);

        $file_storage = get_file_storage();

        if ($file_storage->file_exists($fileinfo ['contextid'], $fileinfo ['component'], $fileinfo ['filearea'],
                                       $fileinfo ['itemid'], $fileinfo ['filepath'], $fileinfo ['filename'])
        ) {
            $file = $file_storage->get_file($fileinfo ['contextid'], $fileinfo ['component'], $fileinfo ['filearea'],
                                            $fileinfo ['itemid'], $fileinfo ['filepath'], $fileinfo ['filename']);
            $file->delete();
        }

        $file = $file_storage->create_file_from_string($fileinfo, $content);

        $url = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                                               $file->get_itemid(), $file->get_filepath(), $file->get_filename());

        $urlstring = $url->out();

        return $urlstring;
    }
}