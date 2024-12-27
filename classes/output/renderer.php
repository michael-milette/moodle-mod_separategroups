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
 * Renderer for the Separate Groups plugin.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_separategroups\output;

/**
 * Renderer for the Separate Groups plugin.
 *
 * @package    mod_separategroups
 * @copyright  2024 TNG Consulting Inc. - {@link https://www.tngconsulting.ca/}
 * @author     Michael Milette
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends \plugin_renderer_base {
    /**
     * Render the main output for the Separate Groups plugin.
     *
     * @param main $main The main renderable object.
     * @return string The rendered output.
     */
    public function render_main(main $main) {
        // Use the page renderer to ensure we pass the correct renderer context.
        $renderer = $this->page->get_renderer('mod_separategroups');

        // Export the data for the Mustache template.
        $data = $main->export_for_template($renderer); // Pass the correct renderer.

        // Now render the data using the specified template.
        return $this->render_from_template('mod_separategroups/main', $data);
    }
}
