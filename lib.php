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

/**
 * Cron function to do the actualy syncing of logs to syslog.
 * @return true or false
 */
function local_syslogger_cron(){
    global $DB;
    
    // if the plugin is not enabled, do nothing and return
    if(get_config('local_syslogger', 'enabled') != 1){
        return 1;
    }
    
    // get the current unix time stampt and subtract 1 - this will serve as the
    // upper limit on the logs to syslog
    $upper_UTS = time() -1;
    
    // get the last logged timestamp from the config table - this will serve as
    // the lower limit on the logs to syslog
    $lower_UTS = get_config('local_syslogger', 'syslogged_upto_uts');
    if(!$lower_UTS){
        $lower_UTS = 0; // will only happen on  first run of plugin ever
    }
    
    // query the DB for all log entries between the two timestamps
    $get_logs_sql  = 'SELECT l.time AS time, l.ip AS ip, u.username AS username, ';
    $get_logs_sql .= 'l.module AS module, l.action AS action, l.url AS url, l.info AS info ';
    $get_logs_sql .= 'FROM {log} l, {user} u ';
    $get_logs_sql .= 'WHERE l.userid=u.id AND l.time > ? AND l.time <= ? ';
    $get_logs_sql .= 'ORDER BY l.time ASC';
    $logs = $DB->get_records_sql(get_logs_sql, array($lower_UTS, $upper_UTS));
    
    // syslog the found entries
    foreach($logs as $log){
        // construct message
        my $log_array = array($log->time, $log->ip, $log->username, $log->module, $log->action, $log->url, $log->info);
        my $log_msg = implode('|', $log_array);
        
        // log the message
        send_syslog($log_msg);
    }
    
    
    // write the last logged timestamp to the config table
    set_config('local_syslogger', 'syslogged_upto_uts', $upper_UTS);
    
    // always return true, at least for now
    return true;
}