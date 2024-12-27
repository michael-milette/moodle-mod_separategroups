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
 * Library of functions and constants for the Separate Groups module.
 *
 * This file contains the main functions and constants used by the Separate Groups module.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Declare the features that the Separate Groups module supports.
 *
 * @uses FEATURE_GROUPS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_COMPLETION_HAS_RULES
 * @uses FEATURE_SHOW_DESCRIPTION
 * @uses FEATURE_MOD_PURPOSE
 * @param string $feature FEATURE_xx constant for requested feature.
 * @return mixed True if module supports feature, false if not, null if doesn't know or string for the module purpose.
 */
function separategroups_supports($feature) {
    switch ($feature) {
        case FEATURE_GROUPS:
            return true; // This module supports groups.
        case FEATURE_GROUPINGS:
            return false; // This module does not support groupings.
        case FEATURE_MOD_INTRO:
            return true; // This module can include a description.
        case FEATURE_COMPLETION_TRACKS_VIEWS:
            return true; // This module supports completion by viewing.
        case FEATURE_COMPLETION_HAS_RULES:
            return true; // This module supports completion rules.
        case FEATURE_GRADE_HAS_GRADE:
            return false; // This module does not support grades.
        case FEATURE_GRADE_OUTCOMES:
            return false; // This module does not support outcomes.
        case FEATURE_BACKUP_MOODLE2:
            return false; // This module cannot be backed up with Moodle 2 backup.
        case FEATURE_SHOW_DESCRIPTION:
            return true; // This module can display its description on the course page.
        case FEATURE_MOD_PURPOSE:
            // Ref: https://moodledev.io/docs/4.1/apis/plugintypes/mod .
            return MOD_PURPOSE_COLLABORATION; // This module type is for collaboration.
        default:
            return null;
    }
}

/**
 * Add separategroups instance.
 * @param stdClass $data
 * @param mod_separategroups_mod_form $mform
 * @return int new separategroups instance id
 */
function separategroups_add_instance($data, $mform = null) {
    global $DB;

    $data->timecreated = time();
    $data->timemodified = $data->timecreated;
    return $DB->insert_record('separategroups', $data);
}

/**
 * Update separategroups instance.
 * @param object $data
 * @param object $mform
 * @return bool true
 */
function separategroups_update_instance($data, $mform) {
    global $DB;

    $data->timemodified = time();
    $data->id = $data->instance;
    return $DB->update_record('separategroups', $data);
}

/**
 * Delete separategroups instance.
 * @param int $id
 * @return bool true
 */
function separategroups_delete_instance($id) {
    global $DB;

    if (!$data = $DB->get_record('separategroups', ['id' => $id])) {
        return false;
    }

    $cm = get_coursemodule_from_instance('separategroups', $id);
    \core_completion\api::update_completion_date_event($cm->id, 'separategroups', $id, null);

    // Delete the main activity instance record.
    if (!$DB->delete_records('separategroups', ['id' => $data->id])) {
        $result = false;
    } else {
        $result = true;
    }

    return $result;
}
