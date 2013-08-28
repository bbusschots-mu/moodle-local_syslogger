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
 * Admin settings.
 *
 * @package    local
 * @subpackage syslogger
 * @author     Bart Busschots <bart.busschots@nuim.ie>
 * @copyright  2013 The National University of Ireland Maynooth
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) { // Needs condition or error on login page.

    // Create a settings page for the syslogger plugin.
    $settings = new admin_settingpage(
            'local_syslogger', get_string('syslogger', 'local_syslogger'));
    $ADMIN->add('localplugins', $settings);

    // Global option to enable or disable syslogging.
    $settings->add(new admin_setting_configcheckbox('local_syslogger/enabled',
            get_string('enabled', 'local_syslogger'), get_string('enabled_desc', 'local_syslogger'),
            1));

    // The path to the logger binary.
    $settings->add(new admin_setting_configtext(
            'local_syslogger/logger_bin', get_string('path', 'local_syslogger'),
            get_string('path_desc', 'local_syslogger'), '/usr/bin/logger', PARAM_PATH));

    // The syslog priority to log with.
    $settings->add(new admin_setting_configtext(
            'local_syslogger/syslog_priority', get_string('syslog_priority', 'local_syslogger'),
            get_string('syslog_priority_desc', 'local_syslogger'), 'local1.info', PARAM_TEXT));

    // The syslog tag to log with.
    $settings->add(new admin_setting_configtext(
            'local_syslogger/syslog_tag', get_string('syslog_tag', 'local_syslogger'),
            get_string('syslog_tag_desc', 'local_syslogger'), 'moodle', PARAM_ALPHANUMEXT));
}
