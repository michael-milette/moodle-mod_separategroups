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
 * View page for Separate Groups.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_login();

$id = required_param('id', PARAM_INT);
$cm = get_coursemodule_from_id('separategroups', $id, 0, false, MUST_EXIST);
$course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
$separategroups = $DB->get_record('separategroups', ['id' => $cm->instance], '*', MUST_EXIST);

$context = context_module::instance($cm->id);
$PAGE->set_context($context);
$PAGE->set_url('/mod/separategroups/view.php', ['id' => $id]);
$PAGE->set_title(format_string($separategroups->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_cm($cm, $course);

$output = $PAGE->get_renderer('mod_separategroups');
echo $output->header();

$main = new \mod_separategroups\output\main();
echo $output->render($main);

echo $output->footer();
