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
 * Write a message to syslog using the logger binary
 * @param string $message the message to be logged.
 * @return the exit code from logger
 */
function send_syslog($message){
    // get the config details from config_plugins table
    $logger   = get_config('local_syslogger', 'logger_bin');
    $priority = get_config('local_syslogger', 'syslog_priority');
    $tag      = get_config('local_syslogger', 'syslog_tag');
    
    // assemble the logger command
    $command  = $logger.' -p '.escapeshellarg($priority);
    $command .= ' -t '.escapeshellarg($tag);
    $command .= ' '.escapeshellarg($message);
    
    // shell out to logger
    $output_lines = array();
    $exit_code    = -1;
    $output = exec($command, $output_lines, $exit_code);
    
    // return the exit code
    return $exit_code;
}

