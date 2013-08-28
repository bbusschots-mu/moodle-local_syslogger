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
 * Library of functions for the syslogger module.
 *
 * @package    local
 * @subpackage syslogger
 * @author     Bart Busschots <bart.busschots@nuim.ie>
 * @copyright  2013 The National University of Ireland Maynooth
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Write a message to syslog using the logger binary
 * @param string $message the message to be logged.
 * @return the exit code from logger
 */
function local_syslogger_send_syslog($message) {
    // Get the config details from config_plugins table.
    $logger   = get_config('local_syslogger', 'logger_bin');
    $priority = get_config('local_syslogger', 'syslog_priority');
    $tag      = get_config('local_syslogger', 'syslog_tag');

    // Assemble the logger command.
    $command  = $logger.' -p '.escapeshellarg($priority);
    $command .= ' -t '.escapeshellarg($tag);
    $command .= ' '.escapeshellarg($message);

    // Shell out to logger.
    $outputlines = array();
    $exitcode    = -1;
    $output = exec($command, $outputlines, $exitcode);

    // Return the exit code.
    return $exitcode;
}

/**
 * Cron function to do the actualy syncing of logs to syslog.
 * @return true or false
 */
function local_syslogger_cron() {
    global $DB;

    // If the plugin is not enabled, do nothing and return.
    if (get_config('local_syslogger', 'enabled') != 1) {
        return 1;
    }

    // Get the current unix time stampt and subtract 1 - this will serve as the
    // ... upper limit on the logs to syslog.
    $upperuts = time() -1;

    // Get the last logged timestamp from the config table - this will serve as
    // ... the lower limit on the logs to syslog.
    $loweruts = get_config('local_syslogger', 'syslogged_upto_uts');
    if (!$loweruts) {
        $loweruts = 0; // Will only happen on  first run of plugin ever.
    }

    // Query the DB for all log entries between the two timestamps.
    $getlogssql  = 'SELECT l.time AS time, l.ip AS ip, u.username AS username, ';
    $getlogssql .= 'l.module AS module, l.action AS action, l.url AS url, l.info AS info ';
    $getlogssql .= 'FROM {log} l, {user} u ';
    $getlogssql .= 'WHERE l.userid=u.id AND l.time > ? AND l.time <= ? ';
    $getlogssql .= 'ORDER BY l.time ASC';
    $logs = $DB->get_records_sql($getlogssql, array($loweruts, $upperuts));

    // Syslog the found entries.
    foreach ($logs as $log) {
        // Construct message.
        $logarray = array(date('c', $log->time), $log->ip, $log->username, $log->module, $log->action, $log->url, $log->info);
        $logmsg = implode('|', $logarray);

        // Log the message.
        local_syslogger_send_syslog($logmsg);
    }

    // Write the last logged timestamp to the config table.
    set_config('syslogged_upto_uts', $upperuts, 'local_syslogger');

    // Always return true, at least for now.
    return true;
}