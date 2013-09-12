moodle-local_syslogger
======================

A Moodle plugin to duplicate Moodle log entries to Syslog using the Linux
`logger` command. Because this plugin shells out to the `logger` command, it 
obvioulsy only works on systems where the `logger` command is available.

This is a local plugin, so should be installed to `[moodle_base]/local/syslogger`.

Once installed you must explicitly enable the plugin by visiting 
`Site Administration -> Plugins -> Local plugins -> Syslogger` and checking the
`Enabled` checkbox. You should also check that the path to `logger` is correct,
and that you are happy with the priority and syslog tag that the plugin will use
when forwarding log messages to syslog.

The plugin does not forward log messages in real-time, but instead uses Moodle's
cron functionality to send all new log messages since the previous execution of
cron at once. The plugin will not execute unless it has been at least 3 minutes
since the last time it executed.

When the plugin runs for the first time it will try send all log messages in the
database. If you are installing this plugin into a fresh Moodle instance this
will not be a problem, but it might be an issue if you are installing the plugin
into an existing Moodle instance. To prevent this from happening you can
manually add an entry into the `log_plugins` table with the following values
(replacing `[UTS_TIME_STAMP]` with the Unix Time Stamp for the earliest time
you want logs sent from, probably the current Unix Time Stamp):

    plugin=local_syslogger
    name=syslogged_upto_uts
    value=[UTS_Time_Stamp]

This plugin has only been tested on Moodle 2.4, but the API calls used etc.
should work with all versions of Moodle 2.*.