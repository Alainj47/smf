<?php

namespace local_smf\external;

use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;

defined('MOODLE_INTERNAL') || die();

class get_courses extends external_api {

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
                $students = [];
                $enrolled = get_enrolled_users($context, 'local/smf:student_access_course');
                foreach ($enrolled as $enrol) {
                    $rol = get_user_roles_in_course($enrol->id, $course->id);
                    $student = [
                        "nombre" => $enrol->firstname . " " . $enrol->lastname,
                        "mail" => $enrol->email,
                    ];
                    array_push($students, $student);
                }

                $data = [
                    'id'        => $course->id,
                    'category'  => $course->category,
                    'shortname' => $course->shortname,
                    'fullname'  => $course->fullname,
                    'startdate' => $course->startdate,   
                    'students' => $students
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
        
        return array(
            "profesor" => $profesor
        );
                
    }

    public static function get_courses_returns() {
        return new external_single_structure(
            array(
                'profesor' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_RAW, 'profesor'),
                        'nombre' => new external_value(PARAM_RAW, 'profesor'),
                        'email' => new external_value(PARAM_RAW, 'profesor'),
                        'nombre' => new external_multiple_structure( 
                                new external_single_structure(
                                    array(
                                        'id' => new external_value(PARAM_INT, 'event id'),
                                        'category' => new external_value(PARAM_RAW, 'event name'),
                                        'shortname' => new external_value(PARAM_RAW, 'event name'),
                                        'fullname' => new external_value(PARAM_RAW, 'event name'),
                                        'startdate' => new external_value(PARAM_RAW, 'event name'),
                                        'students' => new external_multiple_structure( 
                                            new external_single_structure(
                                                array(
                                                    'nombre' => new external_value(PARAM_RAW, 'event id'),
                                                    'email' => new external_value(PARAM_RAW, 'event name'),
                                                ), 'event')
                                         ),
                                    ), 'event')
                             ),
                    )
                )
            )
        );
    }

}
