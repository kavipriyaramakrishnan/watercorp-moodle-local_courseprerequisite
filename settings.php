<?php
/*
 * Course Prerequisite
 * Settings page
 *
 * @package    : local_courseprerequisite
 * @copyright  : 2017 Pukunui
 * @author     : Priya Ramakrishnan <priya@pukunui.com>
 * @license    : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die;

$ADMIN->add('root', new admin_category('local_courseprerequisite', get_string('pluginname', 'local_courseprerequisite')));

$ADMIN->add('local_courseprerequisite', new admin_externalpage('courseprerequisitelist', get_string('courseprerequisitelist', 'local_courseprerequisite'),
            $CFG->wwwroot."/local/courseprerequisite/courseprerequisitelist.php",
                                 'local/courseprerequisite:list'));
