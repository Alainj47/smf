<?php

namespace local_smf;
use context_system;
use context_course;
class utils {

    public static function load_chats($courseid) {
        global $USER, $COURSE, $SESSION;

        $context = \context_course::instance($courseid);
        $endpoint = get_config('local_smf', 'load_chats');
        $roles = self::get_user_roles_in_course($USER->id, $courseid);
        $students = [];
        if (has_capability('local/smf:teacher_access_course', $context)) {
            $enrolled = get_enrolled_users($context, 'local/smf:student_access_course');
            foreach ($enrolled as $enrol) {
                $rol = self::get_user_roles_in_course($enrol->id, $courseid);
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
        $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
        curl_close($cURLConnection);
    }

    /**
    * This function is used to print roles column in user profile page.
    * It is using the CFG->profileroles to limit the list to only interesting roles.
    * (The permission tab has full details of user role assignments.)
    *
    * @param int $userid
    * @param int $courseid
    * @return string
    */
    public static function get_user_roles_in_course($userid, $courseid) {
       global $CFG, $DB;
       if ($courseid == SITEID) {
           $context = context_system::instance();
       } else {
           $context = context_course::instance($courseid);
       }
       // If the current user can assign roles, then they can see all roles on the profile and participants page,
       // provided the roles are assigned to at least 1 user in the context. If not, only the policy-defined roles.
       if (has_capability('moodle/role:assign', $context)) {
           $rolesinscope = array_keys(get_all_roles($context));
       } else {
           $rolesinscope = empty($CFG->profileroles) ? [] : array_map('trim', explode(',', $CFG->profileroles));
       }
       if (empty($rolesinscope)) {
           return '';
       }

       list($rallowed, $params) = $DB->get_in_or_equal($rolesinscope, SQL_PARAMS_NAMED, 'a');
       list($contextlist, $cparams) = $DB->get_in_or_equal($context->get_parent_context_ids(true), SQL_PARAMS_NAMED, 'p');
       $params = array_merge($params, $cparams);

       if ($coursecontext = $context->get_course_context(false)) {
           $params['coursecontext'] = $coursecontext->id;
       } else {
           $params['coursecontext'] = 0;
       }

       $sql = "SELECT DISTINCT r.id, r.name, r.shortname, r.sortorder, rn.name AS coursealias
                 FROM {role_assignments} ra, {role} r
            LEFT JOIN {role_names} rn ON (rn.contextid = :coursecontext AND rn.roleid = r.id)
                WHERE r.id = ra.roleid
                      AND ra.contextid $contextlist
                      AND r.id $rallowed
                      AND ra.userid = :userid
             ORDER BY r.sortorder ASC";
       $params['userid'] = $userid;

       $rolestring = '';

       if ($roles = $DB->get_records_sql($sql, $params)) {
           $viewableroles = get_viewable_roles($context, $userid);

           $rolenames = array();
           foreach ($roles as $roleid => $unused) {
               if (isset($viewableroles[$roleid])) {
                   $rolenames[] = $viewableroles[$roleid];
               }
           }
           $rolestring = implode(',', $rolenames);
       }

       return $rolestring;
    }
}