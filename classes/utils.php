<?php

namespace local_smf;

class utils {

    public static function load_chats(){
        global $USER, $COURSE;

        $context = \context_course::instance($COURSE->id);

        $endpoint = get_config('local_smf', 'load_chats');              
            $roles = get_user_roles_in_course($USER->id,$COURSE->id);

            $enrolled = get_enrolled_users($context,'local/smf:teacher_access_course');
            $students = [];
                            
            foreach($enrolled as $enrol){ 
                $rol = get_user_roles_in_course($enrol->id,$COURSE->id);
                $student = [
                    "nombre"    => $enrol->firstname." ".$enrol->lastname,
                    "mail"      => $enrol->email,
                    "rol"       => strip_tags($rol)
                ];
                array_push($students,$student);
            }

            $data = [
                'rol'   => strip_tags($roles),
                'email' => $USER->email,
                'curso' => $COURSE->id,
                'estudiantes' => $students,
                'secret_id' => time(),               
            ];

            $cURLConnection = curl_init();
            curl_setopt($cURLConnection, CURLOPT_URL, $endpoint);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($cURLConnection, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, array(                    
                    'Content-Type:application/json'
             ));
             $response = curl_exec($cURLConnection);
             $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
             curl_close($cURLConnection);
    
    }
}