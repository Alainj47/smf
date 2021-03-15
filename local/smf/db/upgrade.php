<?php
function xmldb_local_smf_upgrade($oldversion)
{
    global $DB;
    $dbman = $DB->get_manager();

if ($oldversion < 2021022816) {

        // Define table active_collapse_dash to be created.
        $table = new xmldb_table('active_collapse_dash');

        // Adding fields to table active_collapse_dash.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('course_id', XMLDB_TYPE_INTEGER, '20', null, null, null, null);
        $table->add_field('status', XMLDB_TYPE_INTEGER, '1', null, null, null, '0');
        $table->add_field('created_at', XMLDB_TYPE_INTEGER, '20', null, null, null, null);

        // Adding keys to table active_collapse_dash.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for active_collapse_dash.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Smf savepoint reached.
        upgrade_plugin_savepoint(true, 2021022816, 'local', 'smf');
    } 
    return true;
}


?>