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
 * Prints a particular instance of groupformation questionnaire
 *
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

require_once($CFG->dirroot . '/mod/groupformation/classes/questionnaire/basic_question.php');

class mod_groupformation_dropdown_question extends mod_groupformation_basic_question {

    /** @var string Type of question */
    protected $type = 'dropdown';

    /**
     * Print HTML of drop-down inputs
     *
     * @param $highlight
     * @param $required
     */
    public function print_html($highlight, $required) {

        $category = $this->category;
        $questionid = $this->questionid;
        $question = $this->question;
        $options = $this->options;
        $answer = $this->answer;

        $questioncounter = 1;

        if ($answer == false) {
            $answer = -1;
        }

        if ($answer != -1) {
            echo '<tr>';
            echo '<th scope="row">' . $question . '</th>';
        } else if ($highlight) {
            echo '<tr class="noAnswer">';
            echo '<th scope="row">' . $question . '</th>';
        } else {
            echo '<tr>';
            echo '<th scope="row">' . $question . '</th>';
        }

        echo '<td colspan="100%" class="center">';
        $categoryquestionid = $category . $questionid;
        echo '<select style="height:35px" class="form-control" name="';
        echo $categoryquestionid;
        echo '" id="' . $categoryquestionid . '">';
        echo '<option value="0"> - </option>';
        foreach ($options as $option) {
            if (intval($answer) == $questioncounter) {
                echo '<option value="' . $questioncounter . '" selected="selected">' . $option . '</option>';
            } else {

                echo '<option value="' . $questioncounter . '">' . $option . '</option>';
            }
            $questioncounter++;
        }

        echo '</select>
            </td>
        </tr>';
    }

    /**
     * @return array|null
     */
    public function read_answer() {
        $parameter = $this->category . $this->questionid;
        $answer = optional_param($parameter, null, PARAM_RAW);
        if (isset($answer) && $answer != 0) {
            return array('save', $answer);
        } else {
            return null;
        }
    }

    /**
     * Returns random answer
     *
     * @return int
     */
    public function create_random_answer() {
        return rand(1, count($this->options));
    }
}
