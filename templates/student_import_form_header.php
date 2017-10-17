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
if (!defined('MOODLE_INTERNAL')) {
    die ('Direct access to this script is forbidden.');
}

?>
    <div class="gf_pad_header"><?php echo get_string('import', 'groupformation'); ?></div>
    <div class="gf_pad_content">
        <p><?php echo get_string('import_form_description', 'groupformation'); ?>

        <p>
            <?php if ($this->_['file_error']): ?>
                <div class="beta_version_warning">
                    <p>
                    <?php echo get_string('file_error', 'groupformation')?>
                    </p>
                </div>
            <?php endif; ?>
        </p>