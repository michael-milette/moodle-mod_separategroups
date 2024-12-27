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
 * Rendering the page.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../config.php');
require_login();

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url('/mod/separategroups/index.php');
$PAGE->set_title(get_string('pluginname', 'mod_separategroups'));
$PAGE->set_heading(get_string('pluginname', 'mod_separategroups'));

$output = $PAGE->get_renderer('mod_separategroups');
echo $output->header();

$main = new \mod_separategroups\output\main();
echo $output->render($main);

echo $output->footer();
