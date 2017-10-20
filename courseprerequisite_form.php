<?php
/*
 * Course Prerequisite Screen form elements
 * Version details
 *
 * @package    : local_courseprerequisite
 * @copyright  : 2017 Pukunui
 * @author     : Priya Ramakrishnan <priya@pukunui.com>
 * @license    : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir.'/formslib.php');
/*
 * Class courseprerequisite_form extends moodleform
 */
class courseprerequisite_form extends moodleform {
   /*
    * Function definition to define From elements
    */
   public function definition() {
      global $DB, $CFG, $USER, $OUTPUT;
      $mform  =& $this->_form;
      $courseid = $this->_customdata['courseid'];
      $prereqid = $this->_customdata['prereqid'];
      //$courseid = 0;
      $allcourses = $DB->get_records_sql("SELECT id, fullname FROM {course} WHERE id <> 1 ORDER BY fullname");
      foreach ($allcourses as $allc) {
         $courses[$allc->id] = $allc->fullname;
      }
      $mform->addElement('hidden', 'prereqid', $prereqid);
      $mform->setType('prereqid', PARAM_RAW);
      $mform->addElement('select', 'selectcourse', get_string('selectcourse', 'local_courseprerequisite'), $courses);
      if (!empty($prereqid)) {
          $defcourse = $DB->get_field('local_courseprerequisite', 'courseid', array('id' => $prereqid));
          $mform->setDefault('selectcourse', $defcourse);
      }
      $prerequisites = local_courseprerequisite_prerequisite($prereqid); 
      $courses = local_courseprerequisite_courses($prereqid);
      $rarrow = $OUTPUT->rarrow();
      $larrow = $OUTPUT->larrow();
      $htmltbl = "<table class='coursetable'>
                  <tr>
                     <td> Prerequisites </td>
                     <td> </td>
                     <td> Courses </td>
                  </tr>
                  <tr> 
                     <td> $prerequisites </td>
                     <td>
                        <div id='addcontrols'>
                        <input name='add' id='add' type='submit' value='$larrow ADD' title=print_string('add'); <br />
                        </div>
                        <div id='removecontrols'>
                        <input name='remove' id='remove' type='submit' value='$rarrow REMOVE' title=print_string('remove');
                        </div>
                     </td>
                     <td> $courses </td>
                  </tr>
                  </table>";
      $mform->addElement('html', $htmltbl);
      $count = $DB->count_records('local_prerequisitecourse', array('prerequisiteid' => $prereqid));
      $a = new stdClass();
      $a->count = $count;
      $mform->addElement('text', 'selectedcourse', get_string('selectedcourse', 'local_courseprerequisite', $a));
      $mform->setType('selectedcourse', PARAM_INT);
      $completed = $DB->get_field('local_courseprerequisite', 'completedcount', array('id' => $prereqid));
      $mform->setDefault('selectedcourse', $completed);
      $buttonarray = array();
      $buttonarray[] =& $mform->createElement('submit', 'save', get_string('save', 'local_courseprerequisite'));
      $buttonarray[] =& $mform->createElement('cancel', 'cancel', get_string('cancel', 'local_courseprerequisite'));
      $mform->addGroup($buttonarray, 'buttonarr', '', array(''), false);
   }
   /**
     * Function validation to validate form elements
     * @param $data holds the data Submitted form the Form
     * @param $files, files Submitted as part of the Form
     * @return $errors displays the Error message when encountered
     */
    public function validation($data, $files) {
        global $DB;
        $errors = parent::validation($data, $files);
        if (!empty($data['save'])) {
            $count = $DB->count_records('local_prerequisitecourse', array('prerequisiteid' => $data['prereqid']));
            if (empty($count)) {
                $errors['selectedcourse'] = get_string('emptyprerequisite', 'local_courseprerequisite');
            }
            if ($count < $data['selectedcourse']) {
                $errors['selectedcourse'] = get_string('invalidcount', 'local_courseprerequisite');
            }
        }
        return $errors;
    }
}
