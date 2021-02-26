<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_smf', 'Configuración Endpoints del plugin');

    // Create 
    $ADMIN->add('localplugins', $settings);

        $settings->add(new admin_setting_configtext(
                'local_smf/events', 
                get_string('events', 'local_smf'), 
                get_string('events_dsc', 'local_smf'),
                'http://127.0.0.1:8000/mdl_events/events',
                PARAM_RAW)
        );

        $settings->add(new admin_setting_configtext(
            'local_smf/add_events', 
            get_string('add_events', 'local_smf'), 
            get_string('add_events_dsc', 'local_smf'),
            'http://127.0.0.1:8000/mdl_add_events/add_events',
            PARAM_RAW)
        );

        $settings->add(new admin_setting_configtext(
            'local_smf/load_chats', 
            get_string('load_chats', 'local_smf'), 
            get_string('load_chats_dsc', 'local_smf'),
            'http://127.0.0.1:8000/mdl_chats/load_chats',
            PARAM_RAW)
        );

}
