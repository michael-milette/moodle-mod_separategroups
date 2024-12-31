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
 * Implements the interfaces for displaying separate groups which includes information about the groups, members & member details.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_separategroups\output;

use renderable;
use renderer_base;
use templatable;
use stdClass;
use moodle_url;
use context_course;

/**
 * Class main
 * @package mod_separategroups
 * Implements renderable and templatable for displaying separate groups.
 */
class main implements renderable, templatable {
    /**
     * Prepares data for the Mustache template.
     *
     * @param renderer_base $output The renderer base.
     * @return stdClass The data for the template.
     */
    public function export_for_template(renderer_base $output): stdClass {
        global $USER, $COURSE, $PAGE;

        $data = new stdClass();
        $data->url = new moodle_url('/mod/separategroups/view.php');
        $data->id = $PAGE->cm->id;
        $data->showmembersdropdown = false; // Set default value.

        if ($PAGE->cm->groupmode === 0) {
            $data->selectedmember = $this->get_user_info($USER);
            $data->hasgroups = false;
            $data->hasmembers = false;
            return $data;
        }

        $context = context_course::instance($COURSE->id);
        $groups = $this->get_user_groups($context, $COURSE->id, $USER->id);
        $data->hasgroups = !empty($groups);

        // If only one group, select it by default.
        $selectedgroupid = count($groups) === 1 ? reset($groups)->id : optional_param('groupid', 0, PARAM_INT);

        // Ensure the user is a member of the selected group.
        if ($selectedgroupid && !in_array($selectedgroupid, array_column($groups, 'id'))) {
            $selectedgroupid = 0;
        }

        $data->groups = $this->prepare_groups($groups, $selectedgroupid);
        $selectedmemberid = optional_param('member', 0, PARAM_INT);

        if ($selectedgroupid) {
            $members = $this->get_group_members($selectedgroupid, $selectedmemberid);
            $data->members = $members;
            $data->hasmembers = !empty($data->members);
            $data->showmembersdropdown = true;

            // Ensure the selected member belongs to the selected group.
            if ($selectedmemberid && !in_array($selectedmemberid, array_column($members, 'id'))) {
                $selectedmemberid = 0;
            }

            $data->selectedmember = $this->get_selected_member($selectedmemberid, $data->hasmembers);
        } else {
            $data->hasmembers = false;
            $data->selectedmember = null;
        }

        // Clear selected member data if group is changed.
        if ($selectedgroupid && !$selectedmemberid) {
            $data->selectedmember = null;
        }

        // If no groups, show user's own information.
        if (empty($groups)) {
            $data->selectedmember = $this->get_user_info($USER);
        }

        return $data;
    }

    /**
     * Gets the user groups based on the user's capabilities.
     *
     * @param context_course $context The course context.
     * @param int $courseid The course ID.
     * @param int $userid The user ID.
     * @return array The user groups.
     */
    private function get_user_groups(context_course $context, int $courseid, int $userid): array {
        if (has_capability('moodle/site:accessallgroups', $context)) {
            return groups_get_all_groups($courseid);
        } else {
            return groups_get_all_groups($courseid, $userid);
        }
    }

    /**
     * Prepares the groups data for the template.
     *
     * @param array $groups The groups.
     * @param int $selectedgroupid The selected group ID.
     * @return array The prepared groups.
     */
    private function prepare_groups(array $groups, int $selectedgroupid): array {
        $preparedgroups = [];
        foreach ($groups as $group) {
            $preparedgroups[] = [
                'id' => $group->id,
                'name' => $group->name,
                'selected' => ($group->id == $selectedgroupid),
            ];
        }
        return $preparedgroups;
    }

    /**
     * Gets the members of a group.
     *
     * @param int $groupid The group ID.
     * @param int $selectedmemberid The selected member ID.
     * @return array The group members.
     */
    private function get_group_members(int $groupid, int $selectedmemberid): array {
        global $DB;
        $members = groups_get_members(
            $groupid,
            'u.id, u.firstname, u.lastname, u.middlename, u.alternatename, u.email'
        );
        $preparedmembers = [];
        foreach ($members as $member) {
            $preparedmembers[] = [
                'id' => $member->id,
                'group_id' => $groupid,
                'lastname' => $member->lastname,
                'firstname' => $member->firstname,
                'middlename' => $member->middlename,
                'alternatename' => $member->alternatename,
                'email' => $member->email,
                'selected' => ($member->id == $selectedmemberid),
            ];
        }
        return $preparedmembers;
    }

    /**
     * Gets the selected member's data.
     *
     * @param int $selectedmemberid The selected member ID.
     * @param bool $hasmembers Whether the group has members.
     * @return stdClass|null The selected member's data or null if not found.
     */
    private function get_selected_member(int $selectedmemberid, bool $hasmembers): stdClass|null {
        global $DB, $CFG;
        if ($selectedmemberid && $hasmembers) {
            $selectedmember = $DB->get_record(
                'user',
                ['id' => $selectedmemberid],
                'id, firstname, middlename, lastname, email, country, timezone, alternatename, firstnamephonetic, lastnamephonetic'
            );
            if ($selectedmember) {
                $selectedmemberdata = new stdClass();
                $selectedmemberdata->fullname = fullname($selectedmember);
                if (!empty($selectedmember->country)) {
                    $selectedmemberdata->country = get_string($selectedmember->country, 'countries');
                } else {
                    $selectedmemberdata->country = '';
                }
                $selectedmemberdata->timezone = ($selectedmember->timezone == 99) ? $CFG->timezone : $selectedmember->timezone;

                $dobfieldid = $DB->get_field('user_info_field', 'id', ['shortname' => 'dob']);
                if ($dobfieldid) {
                    $selectedmemberdata->dob = $DB->get_field(
                        'user_info_data',
                        'data',
                        ['userid' => $selectedmember->id, 'fieldid' => $dobfieldid]
                    );
                } else {
                    $selectedmemberdata->dob = '';
                }
                return $selectedmemberdata;
            }
        }
        return null;
    }

    /**
     * Gets the user's own information.
     *
     * @param stdClass $user The user object.
     * @return stdClass The user's information.
     */
    private function get_user_info(stdClass $user): stdClass {
        global $DB, $CFG;
        $userinfo = new stdClass();
        $userinfo->fullname = fullname($user);
        $userinfo->country = !empty($user->country) ? get_string($user->country, 'countries') : '';
        $userinfo->timezone = ($user->timezone == 99) ? $CFG->timezone : $user->timezone;

        $dobfieldid = $DB->get_field('user_info_field', 'id', ['shortname' => 'dob']);
        if ($dobfieldid) {
            $userinfo->dob = $DB->get_field('user_info_data', 'data', ['userid' => $user->id, 'fieldid' => $dobfieldid]);
        } else {
            $userinfo->dob = '';
        }
        return $userinfo;
    }
}
