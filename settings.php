<?php

defined('MOODLE_INTERNAL') || die;

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_smf', 'Configuración Endpoints del plugin smf');

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
        
    // Number of iframes.
    $name = 'local_smf/numberofiframes';
    $title = 'Numero de iframes';
    $description = 'Numero de iframes';
    $default = 3;
    $choices = array(
        1 => '1',
        2 => '2',
        3 => '3',
        4 => '4',
        5 => '5',
        6 => '6',
        7 => '7',
        8 => '8',
        9 => '9',
        10 => '10',
        11 => '11',
        12 => '12',
    );
    $settings->add(new admin_setting_configselect($name, $title, $description, $default, $choices));

    // Iframes show settings.
    $numberofslides = get_config('local_smf', 'numberofiframes');
    for ($i = 1; $i <= $numberofslides; $i++) {
        // Titulo iframe
        $name = 'local_smf/iframe_name_' . $i;
        $title = 'Titulo iframe ' . $i;
        $description = 'Titulo iframe ' . $i;
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $settings->add($setting);
        // Url iframe
        $name = 'local_smf/iframe_url_' . $i;
        $title = 'Url iframe ' . $i;
        $description = 'Url iframe ' . $i;
        $default = '';
        $setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_TEXT);
        $settings->add($setting);
        
    }

}
