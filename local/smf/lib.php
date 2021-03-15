<?php

function local_smf_extend_navigation(global_navigation $navigation) {
    global $SESSION,$COURSE;
    $context = \context_course::instance($COURSE->id);
   if(isloggedin() && has_capability('local/smf:teacher_access_course', $context) && $COURSE->id>1){
    
       $url = new moodle_url('/local/smf/index.php',array('id'=>$COURSE->id));
       $node = navigation_node::create(
           'Conf. plugins Eventos',
           $url,
           navigation_node::TYPE_SETTING,
           null,
           null,
           new pix_icon('i/settings','Conf. plugins Eventos')
       );
       $node->showinflatnavigation = true;
       $node->classes = array('localconfigcoursesmf');
       $node->key = 'localconfigcoursesmf';

       if (isset($node) && isloggedin()) {
           $navigation->add_node($node);
       }
       //$nod = $navigation->find('calendar',navigation_node::TYPE_UNKNOWN);
       //$nod->remove(); 
   }
}

function local_smf_extend_settings_navigation(settings_navigation $nav, context $context) {
    global $COURSE, $DB;
    $context = \context_course::instance($COURSE->id);
    $url = new moodle_url('/local/smf/index.php',array('id'=>$COURSE->id));
    $requiredcapabilities = array('moodle/course:update');
     if(isloggedin() && has_capability('local/smf:teacher_access_course', $context) && $COURSE->id>1){

        $branch = $nav->get('courseadmin');
        // Core plugin mod_assign must be enabled.
        if ($branch) {
     
          
            $managetext = 'Conf. plugins Eventos';
            $icon = new pix_icon('i/settings', $managetext);
            $branch->add($managetext, $url, $nav::TYPE_CONTAINER, null, null, $icon);
        }
    }
}


function local_smf_before_footer() {
    global $COURSE, $USER, $PAGE, $SESSION;

    if (isloggedin()) {
        $context = \context_course::instance($COURSE->id);
        $actual_page = (String) $PAGE->url;
        $course_page = (String) new moodle_url('/course/view.php', array('id' => $COURSE->id));
        if ($context && ($COURSE->id > 1) && ($actual_page == $course_page)) {
            if (has_capability('local/smf:teacher_student_access_course', $context) && !is_siteadmin()) {
                $endpoint = get_config('local_smf', 'events');
                $roles = \local_smf\utils::get_user_roles_in_course($USER->id, $COURSE->id);
                $data = [
                    'rol' => $roles,
                    'email' => $USER->email,
                    'curso' => $COURSE->id,
                    'secret_id' => $SESSION->hashsecred
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

            if (has_capability('local/smf:teacher_access_course', $context) && !is_siteadmin()) {
                $endpoint = get_config('local_smf', 'add_events');
                $roles = \local_smf\utils::get_user_roles_in_course($USER->id, $COURSE->id);
                $enrolled = get_enrolled_users($context, 'local/smf:student_access_course');
                $students = [];

                foreach ($enrolled as $enrol) {
                    $rol = \local_smf\utils::get_user_roles_in_course($enrol->id, $COURSE->id);
                    $student = [
                        "nombre" => $enrol->firstname . " " . $enrol->lastname,
                        "mail" => $enrol->email,
                        "rol" => strip_tags($rol)
                    ];
                    array_push($students, $student);
                }

                $data = [
                    'rol' => strip_tags($roles),
                    'email' => $USER->email,
                    'curso' => $COURSE->id,
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
                $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
                curl_close($cURLConnection);
               
            }
        }
    }
}

function local_smf_before_standard_top_of_body_html() {
    global $COURSE, $PAGE, $SESSION, $USER;
    if (isloggedin()) {
        $SESSION->hashsecred = sha1(time() . $USER->email);
        $actual_page = (String) $PAGE->url;
        $course_page = (String) new moodle_url('/course/view.php', array('id' => $COURSE->id));

        if ($COURSE->id > 1 && ($actual_page == $course_page)) {
            $PAGE->requires->js_call_amd('local_smf/evens_smf', 'init', array($COURSE->id));
        }
    }

    if($COURSE->id > 1){
        $cache = \cache::make('core', 'coursemodinfo');
        $cache->purge();
    }


}
