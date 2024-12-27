<img src="pix/monologo.png" align="right" />

Separate Groups Plugin for Moodle LMS
=====================================
![PHP](https://img.shields.io/badge/PHP-v8.1%20to%20v8.3-blue.svg)
![Moodle](https://img.shields.io/badge/Moodle-v4.1%20to%20v4.4-orange.svg)
[![GitHub Issues](https://img.shields.io/github/issues/michael-milette/moodle-filter_filtercodes.svg)](https://github.com/michael-milette/moodle-filter_filtercodes/issues)
[![Contributions welcome](https://img.shields.io/badge/contributions-welcome-green.svg)](#contributing)
[![License](https://img.shields.io/badge/License-GPL%20v3-blue.svg)](#license)

## Overview
The purpose of the Separate Groups plugin for Moodle LMS is to provide developers with a concept example of how to create a Moodle activity module plugin that supports separate groups. It allows users to select one of the groups that they belong to and then choose a member from that group. It then fetches and displays member information. If there are no groups, it will display the current user's information.

## Features
- Dropdown selection of groups for users.
- Dynamic population of member dropdown based on selected group.
- Admins, managers, teachers, and non-editing teachers will see all members of all groups.
- Display of user details including full name, country, timezone, and a custom profile field for Date of Birth (dob) if it exists.
- Accessibility compliant user interface.
- Optimized database queries for performance with caching mechanisms.
- If separate groups are not enabled or there are no groups, the plugin will simply display the current user's information.

## Installation
1. Download the Separate Groups plugin from the repository.
2. Unzip the contents into the `/mod/separategroups` directory.
3. Navigate to Site Administration > Plugins > Install plugins.
4. Follow the on-screen instructions to complete the installation.

## Usage
- After installation, users can access the Separate Groups plugin from their dashboard.
- Users will see a dropdown of groups they belong to.
- Upon selecting a group, the members of that group will be populated in a second dropdown.
- Selecting a member will display their details on the page.

## Developer Guide
This plugin is designed to be a starting point for developers who want to create Moodle plugins that support separate groups. You can adapt this plugin for various purposes beyond displaying profile fields. Here are some key points to help you get started:

- **Template Rendering**: The plugin uses Mustache templates for rendering the user interface. You can customize the templates in the `templates` directory to suit your needs.
- **Data Handling**: The plugin demonstrates how to handle data passed between the backend and the frontend. You can extend the data structure to include additional fields or modify the existing ones.
- **Permissions**: The plugin checks user capabilities to determine what data to display. You can add or modify capability checks to control access to different parts of your plugin.

## License
This plugin is licensed under the GNU General Public License v3.0. Please see the LICENSE file for more details.
