<?php
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_smf_courses_by_teacher' => array(
        'classname'   => 'local_smf\external\get_courses',
        'methodname'  => 'get_courses',
        'description' => 'Obtener cursos por profesor',
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'type'        => 'read',
        'ajax'        => true,
        'loginrequired' => false,
    ),
    'local_smf_events' => array(
        'classname'   => 'local_smf\external\course',
        'methodname'  => 'get_template',
        'description' => 'devuelve un htmls con iframes',
        'type'        => 'read',
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'ajax'          => true,
        'loginrequired' => false,
    ),
    'local_smf_load_chats' => array(
        'classname'   => 'local_smf\external\course',
        'methodname'  => 'load_chat',
        'description' => 'Conecta con ws de chat',
        'type'        => 'read',
        'services'    => array(MOODLE_OFFICIAL_MOBILE_SERVICE),
        'ajax'          => true,
        'loginrequired' => false,
    ),
);

