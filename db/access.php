<?php
/*
 * Course Prerequisite
 * Version details
 *
 * @package    : local_courseprerequisite
 * @copyright  : 2017 Pukunui
 * @author     : Priya Ramakrishnan <priya@pukunui.com>
 * @license    : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$capabilities = array(
   'local/courseprerequisite:list' => array(
        'riskbitmask' => RISK_DATALOSS,
        'captype'     => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes'  => array(
            'manager'   => CAP_ALLOW,
        )
   )
);
