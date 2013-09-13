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
 * Language strings.
 *
 * @package    local
 * @subpackage syslogger
 * @author     Bart Busschots <bart.busschots@nuim.ie>
 * @copyright  2013 The National University of Ireland Maynooth
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['syslogger'] = 'Syslogger';
$string['pluginname'] = 'Syslogger';
$string['enabled'] = 'Enabled';
$string['enabled_desc'] = 'Enable log duplication to syslog using the Linux logger command';
$string['path'] = 'logger Path';
$string['path_desc'] = 'The full path to the Linux logger binary.';
$string['syslog_priority'] = 'Syslog Priority';
$string['syslog_priority_desc'] = 'The syslog priority argument to pass to the Linux logger command - should be for the form syslog_facility.syslog_priority, e.g. local7.info';
$string['syslog_tag'] = 'Syslog Tag';
$string['syslog_tag_desc'] = 'The syslog tag to pass to the Linux logger command - should be alphanumeric (including _ and -)';
