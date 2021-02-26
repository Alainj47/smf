<?php


function local_smf_extend_navigation(global_navigation $navigation) {   
    global $SESSION;

    if(isloggedin()){
        $url = new moodle_url('/local/smf/index.php');
        $node = navigation_node::create(
            get_string('pluginname', 'local_smf'),
            $url,
            navigation_node::TYPE_SETTING,
            null,
            null,
            new pix_icon('i/scheduled', '')
        );
        $node->showinflatnavigation = true;
        $node->classes = array('localcalendar');
        $node->key = 'localcalendar';

        if (isset($node) && isloggedin()) {
            $navigation->add_node($node);
        }
        //$nod = $navigation->find('calendar',navigation_node::TYPE_UNKNOWN);
        //$nod->remove(); 
    }
}


function local_smf_before_footer() {
    global $COURSE, $USER;

    $context = \context_course::instance($COURSE->id);
    if ($context && ($COURSE->id > 1) ){
        if (has_capability('local/smf:teacher_student_access_course', $context) && !is_siteadmin() ){

            $endpoint = get_config('local_smf', 'events');              
            $roles = get_user_roles_in_course($USER->id,$COURSE->id);
            $data = [
                'rol'   => $roles,
                'email' => $USER->email,
                'curso' => $COURSE->id,
                'secret_id' => 'secret'
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

        if (has_capability('local/smf:teacher_access_course', $context) && !is_siteadmin() ){            
            $endpoint = get_config('local_smf', 'add_events');              
            $roles = get_user_roles_in_course($USER->id,$COURSE->id);

            $enrolled = get_enrolled_users($context,'local/smf:student_access_course');
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
}