<?php

namespace local_smf;

class utils {

    public static function load_chats($courseid) {
        global $USER, $COURSE, $SESSION;

        $context = \context_course::instance($courseid);

            error_log('webservices');
            error_log(print_r($USER->id.'->'.$courseid, true));
        $endpoint = get_config('local_smf', 'load_chats');
        $roles = get_user_roles_in_course($USER->id, $courseid);
        $students = [];
        if (has_capability('local/smf:teacher_access_course', $context)) {
            $enrolled = get_enrolled_users($context, 'local/smf:student_access_course');
            foreach ($enrolled as $enrol) {
                $rol = get_user_roles_in_course($enrol->id, $courseid);
                $student = [
                    "nombre" => $enrol->firstname . " " . $enrol->lastname,
                    "mail" => $enrol->email,
                    "rol" => strip_tags($rol)
                ];
                array_push($students, $student);
            }
        } 

        
        $data = [
            'rol' => strip_tags($roles),
            'email' => $USER->email,
            'curso' => $courseid,
            'estudiantes' => $students,
            'secret_id' => $SESSION->hashsecred,
        ];

        $cURLConnection = curl_init();
        curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
        curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(
            'Content-Type:application/json'
        ));
        $response = curl_exec($cURLConnection);
        error_log(print_r($response,True));
        $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
        curl_close($cURLConnection);
    }

}