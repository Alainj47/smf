<?php

require_once("$CFG->libdir/externallib.php");

/**
 * Class local_smf_external
 */

defined('MOODLE_INTERNAL') || die();

class local_smf_external extends external_api {

     /**
     * Get parameter list.
     * @return external_function_parameters
     */
    public static function get_courses_parameters() {
        return new external_function_parameters (array(
            'email'    => new external_value(PARAM_RAW, 'Email del Profesor'), 
        ));
    }

    /**
     * Get list of courses with active sessions for today.
     * @param string $descripcion
     * @return array
     */
    public static function get_courses($email) {
        global $DB;

        $teacher = $DB->get_record('user',array('email' => $email));
        $courses = enrol_get_all_users_courses($teacher->id, true);
        $allcourses= [];
        foreach($courses as $course){
            $context = \context_course::instance($course->id);
            if (has_capability('local/smf:teacher_access_course', $context)){
                $data = [
                    'id'        => $course->id,
                    'category'  => $course->category,
                    'shortname' => $course->shortname,
                    'fullname'  => $course->fullname,
                    'startdate' => $course->startdate,                        
                ];
                array_push($allcourses,$data);                    
            }
        }

        $profesor = [
            'id'        => $teacher->id,
            'nombre'    => fullname($teacher),
            'email'     => $teacher->email,
            'cursos'    => $allcourses
        ];        
        
        return $profesor;
                
    }

    public static function get_courses_returns() {
        return new external_single_structure(
            [
                'profesor' =>  new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_RAW, 'id', VALUE_OPTIONAL, ''),
                        'nombre' => new external_value(PARAM_RAW, 'nombre', VALUE_OPTIONAL, ''),
                        'email' => new external_value(PARAM_RAW, 'email', VALUE_OPTIONAL, ''),
                        'cursos' =>  new external_multiple_structure(
                            new external_single_structure(
                                [
                                    'id' => new external_value(PARAM_RAW, 'id'),
                                    'category' => new external_value(PARAM_RAW, 'category'),
                                    'shortname' => new external_value(PARAM_RAW, 'shortname'),
                                    'fullname' => new external_value(PARAM_RAW, 'fullname'),
                                    'startdate' => new external_value(PARAM_RAW, 'startdate'),
                                ],
                                VALUE_OPTIONAL,
                                array()
                            )
                        )
                    )
                ),
            ]
        );    
    }

}
