<?php
/*
 * Course Prerequisite List Screen form elements
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
 * Class courseprerequisitelist_form extends moodleform
 */
class courseprerequisitelist_form extends moodleform {
   /*
    * Function definition to define From elements
    */
   public function definition() {
      global $DB, $CFG, $USER, $OUTPUT;
      $mform  =& $this->_form;
      $mform->addElement('static', 'intro', '', get_string('intro', 'local_courseprerequisite'));
      $mform->addElement('html', local_courseprerequisite_list());
      $mform->addElement('html', "<div class=add align=left>");
      $mform->addElement('submit', 'add', get_string('add', 'local_courseprerequisite'));
      $mform->addElement('html', "</div>");
   }
}
