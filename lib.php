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
