<?php
// This file is part of Separate Groups for Moodle - https://moodle.org/
//
// Separate Groups is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Separate Groups is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Form for creating and editing Separate Groups instances.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');

/**
 * Class mod_separategroups_mod_form
 * Defines the form for creating and editing Separate Groups instances.
 *
 * @package    mod_separategroups
 */
class mod_separategroups_mod_form extends moodleform_mod {
    /**
     * Defines the form elements.
     */
    public function definition() {
        $mform = $this->_form;

        // Add the general header.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Add the name field.
        $mform->addElement('text', 'name', get_string('separategroupsname', 'mod_separategroups'), ['size' => '64']);
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', null, 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'separategroupsname', 'mod_separategroups');

        // Add the intro and intro format fields.
        $this->standard_intro_elements();

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add action buttons.
        $this->add_action_buttons();
    }
}
