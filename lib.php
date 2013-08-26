<?php
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
 * Write a single Moodle log entry to syslog
 * @param object $log Object containing fields from the log table
 * @return TBD
 */
function local_syslogger_send_syslog($log){
    global $CFG;
    
    // get the path to logger from the config
    $logger = get_config('local_syslogger', 'logger_bin');
}