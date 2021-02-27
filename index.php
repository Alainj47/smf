<?php
// File: /mod/mymodulename/view.php
require_once('../../config.php');

// $cmid = required_param('id', PARAM_INT);
// $cm = get_coursemodule_from_id('mymodulename', $cmid, 0, false, MUST_EXIST);
// $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
 
require_login();
$PAGE->set_url('/local/smf/index.php');
// $PAGE->set_title('My modules page title');
// $PAGE->set_heading('Educ');
echo $OUTPUT->header();
// echo "<h1>HOLA MUNDO</h1>";
// print_r($USER);
echo '<iframe style="width:115%" height="600" src="http://comunica2.educa.madrid.org/" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
echo $OUTPUT->footer();
 
// The rest of your code goes below this.





?>