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
    global $COURSE;

    $context = \context_course::instance($COURSE->id);
    if ($context && ($COURSE->id > 1) ){
        if (has_capability('local/smf:teacher_access_course', $context)){
            echo "ACCCA";
        }
        
    }
   
}