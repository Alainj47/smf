<?php

namespace local_smf\external;

/**
 * Class get_courses
 */
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
        
        return array(
            "profesor" => $profesor
        );
                
    }

    public static function get_courses_returns() {
        return new external_single_structure(
            array(
                '$profesor' => new external_value(PARAM_RAW, '$profesor'),
            )
        );
    }

}
