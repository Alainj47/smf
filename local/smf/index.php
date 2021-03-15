<?php
// File: /mod/mymodulename/view.php
require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");

class simplehtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG;
 
        $mform = $this->_form; // Don't forget the underscore! 
 
        $mform->addElement('checkbox', 'status_collapse','Activar Próximos Eventos Webex del curso  '); 
        $mform->setType('status_collapse', PARAM_RAW);                   //Set type of element
         //Default value

        $this->add_action_buttons();
        
    }
    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

  $course_id = required_param('id', PARAM_INT);
// $cm = get_coursemodule_from_id('mymodulename', $cmid, 0, false, MUST_EXIST);
// $course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
 
require_login($course_id);
$url = new moodle_url('/local/smf/index.php',array('id'=>$course_id));
$PAGE->set_url($url);

// $PAGE->set_title('My modules page title');
// $PAGE->set_heading('Educ');
echo $OUTPUT->header();
$status_db=$DB->get_record('active_collapse_dash',array('course_id' => $course_id));
$toform=new stdClass();

if(!empty($status_db)){
$toform->status_collapse=$status_db->status;
}

//Instantiate simplehtml_form 
$mform = new simplehtml_form($url);
$mform->set_data($toform);
 
//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
	$dataobjects=new stdClass();
	$dataobjects->status=empty($fromform->status_collapse) ? 0: $fromform->status_collapse;
   if(!empty($status_db)){ 	 
   	  $dataobjects->id=$status_db->id;
      $DB->update_record('active_collapse_dash', $dataobjects);
   }else{
     
        $dataobjects->course_id=$course_id;        
        $dataobjects->created_at=time();
        $DB->insert_record('active_collapse_dash', $dataobjects);
   }
 $string= $dataobjects->status==0 ? 'Desactivado': 'Activado'; 
 redirect(new moodle_url('/course/view.php',array('id'=> $course_id)),'Plugin '.$string.' Exitosamente');
} 
 $mform->display();
 


echo $OUTPUT->footer();
 
// The rest of your code goes below this.





?>