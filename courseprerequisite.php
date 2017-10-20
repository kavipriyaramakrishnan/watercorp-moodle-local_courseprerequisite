<?php
/*
 * Course Prerequisite Screen
 * Version details
 *
 * @package    : local_courseprerequisite
 * @copyright  : 2017 Pukunui
 * @author     : Priya Ramakrishnan <priya@pukunui.com>
 * @license    : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require($CFG->dirroot.'/local/courseprerequisite/courseprerequisite_form.php');
require($CFG->dirroot.'/local/courseprerequisite/locallib.php');

$add           = optional_param('add', '', PARAM_RAW);
$courseid      = optional_param('selectcourse', 0, PARAM_INT);
$courses       = optional_param('courses', array(), PARAM_ALPHANUMEXT);
$prereqid      = optional_param('prereqid', 0, PARAM_INT);
$prerequisites = optional_param('prerequisite', array(), PARAM_ALPHANUMEXT);
$remove        = optional_param('remove', '', PARAM_RAW);
$strtitle = get_string('pluginname', 'local_courseprerequisite');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/courseprerequisite/courseprerequisite.php');

require_login();

// Set up PAGE object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($strtitle);

if (!empty($add)) {
    if (!($prereqid)) {
       $records = new stdClass();
       $records->courseid = $courseid;
       $records->completedcount = 0;
       $prereqid = $DB->insert_record('local_courseprerequisite', $records);
    }
    foreach ($courses as $key => $data) {
       $records1 = new stdClass();
       $records1->prerequisiteid = $prereqid;
       $records1->courseid = $data;
       $DB->insert_record('local_prerequisitecourse', $records1);
    }
    redirect($CFG->wwwroot."/local/courseprerequisite/courseprerequisite.php?prereqid=$prereqid");   
} else if (!empty($remove)) {
    foreach ($prerequisites as $key => $data) {
       $record = new stdClass();
       $record->prerequisiteid = $prereqid;
       $record->courseid = $data;
       $DB->delete_records('local_prerequisitecourse', array('prerequisiteid' => $prereqid, 'courseid' => $data));
    }
}
// Form
$mform = new courseprerequisite_form('', array('courseid' => $courseid, 'prereqid' => $prereqid));

if ($data = $mform->get_data()) {
    if (!empty($data->save)) {
    $record = new stdClass();
    $record->id = $data->prereqid;
    $record->completedcount = $data->selectedcourse;
    $DB->update_record('local_courseprerequisite', $record);
    redirect($CFG->wwwroot."/local/courseprerequisite/courseprerequisitelist.php");
    }
} else if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot."/local/courseprerequisite/courseprerequisitelist.php");
}
// Output renderers.
echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
