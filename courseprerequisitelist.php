<?php
/*
 * Course Prerequisite List Screen
 * Version details
 *
 * @package    : local_courseprerequisite
 * @copyright  : 2017 Pukunui
 * @author     : Priya Ramakrishnan <priya@pukunui.com>
 * @license    : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once('../../config.php');
require($CFG->dirroot.'/local/courseprerequisite/courseprerequisitelist_form.php');
require($CFG->dirroot.'/local/courseprerequisite/locallib.php');

$action = optional_param('action', '', PARAM_RAW);
$prereqid = optional_param('prereqid', 0, PARAM_INT);
$strtitle = get_string('pluginname', 'local_courseprerequisite');
$systemcontext = context_system::instance();
$url = new moodle_url('/local/courseprerequisite/courseprerequisitelist.php');

require_login();

// Set up PAGE object.
$PAGE->set_url($url);
$PAGE->set_context($systemcontext);
$PAGE->set_title($strtitle);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($strtitle);

if (!strcmp($action, 'delete')) {
    $linkyes = "$CFG->wwwroot/local/courseprerequisite/courseprerequisitelist.php?prereqid=$prereqid&action=confirm";
    $linkno  = "$CFG->wwwroot/local/courseprerequisite/courseprerequisitelist.php";
    echo $OUTPUT->header();
    echo $OUTPUT->confirm(get_string('typeconfirmdeletion', 'local_courseprerequisite'), $linkyes, $linkno);
    echo $OUTPUT->footer();
    exit();
} else if (!strcmp($action, 'confirm')) {
    $DB->delete_records('local_prerequisitecourse', array('prerequisiteid' => $prereqid));
    $DB->delete_records('local_courseprerequisite', array('id' => $prereqid));
}

$mform = new courseprerequisitelist_form();

if ($data = $mform->get_data()) {
    if (!empty($data->add)) {
        redirect($CFG->wwwroot."/local/courseprerequisite/courseprerequisite.php");
    }
}

echo $OUTPUT->header();
echo $mform->display();
echo $OUTPUT->footer();
