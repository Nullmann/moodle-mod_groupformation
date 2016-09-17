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
 * @package mod_groupformation
 * @author Eduard Gallwas, Johannes Konert, Rene Roepke, Nora Wester, Ahmed Zukic
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
?>
<div class="grid row_highlight">
    <div class="col_m_100"><?php echo get_string('are', 'groupformation');?>
        <b><?php echo ' '.$this->_['statistics_enrolled']; ?></b>
        <?php echo ' '.(($this->_['statistics_enrolled']==1) ? get_string('students_available_single', 'groupformation') : get_string('students_available_multiple', 'groupformation')); ?></div>
</div>
<div class="grid row_highlight">
    <div class="col_m_100"><b><?php echo $this->_['statistics_processed']; ?></b>
        <?php echo ($this->_['statistics_processed']==1) ? get_string('students_answered_single', 'groupformation') : get_string('students_answered_multiple', 'groupformation'); ?></div>
</div>
<div class="grid row_highlight">
    <div class="col_m_100"><b><?php echo $this->_['statistics_submited']; ?></b>
        <?php echo ' '.($this->_['statistics_submited']==1) ? get_string('students_commited_single', 'groupformation') : get_string('students_commited_multiple', 'groupformation'); ?></div>
</div>
<!-- HOTFIX FOR HRZ SOSE2016
<div class="grid row_highlight">
	<div class="col_m_100"><b><?php //echo $this->_['statistics_submited_incomplete']; ?></b>
        <?php //echo ' '.get_string('commited_not_completed', 'groupformation');?></div>
</div> -->
<div class="grid row_highlight">
    <div class="col_m_100"><?php echo get_string('are_now', 'groupformation');?> <b>
            <?php echo ' '.$this->_['statistics_submited_complete']; ?></b>
        <?php echo ' '.get_string('completed_questionnaire', 'groupformation');?></div>
</div>

