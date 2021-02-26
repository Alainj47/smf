<?php
defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_smf_courses_by_teacher' => array(
        'classname'   => 'local_smf_external',
        'methodname'  => 'get_courses',
        'classpath'   => 'local/smf/externallib.php',
        'description' => 'Obtener cursos por profesor',
        'type'        => 'read',
        'ajax'        => true
    ),
);

$services = array(
    'smf' => array(
        'functions' => array(
            'local_smf_courses_by_teacher',
        ),
        'component' => 'local_smf',
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'local_smf_ws'
    )
);