<?php
/*
 * Course Prerequisite - Local library function
 *
 * @package    : local_courseprerequisite
 * @copyright  : 2017 Pukunui
 * @author     : Priya Ramakrishnan <priya@pukunui.com>
 * @license    : http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
/*
 * To build the add/remove table.
 *
 * @uses $DB
 * @param $groupid holds the id of the group
 * @return $table html group type list
 */
function local_courseprerequisite_swap($courseid) {
    $html = '<table class="coursetable"> 
        <tr>
        <td id="prerequisite">
        <p><label >'.get_string('prerequisite', 'local_courseprerequisite').'</label></p>'.
        local_courseprerequisite_prerequisite($courseid).'</td>
        <td id="buttoncell">
        <div id="addcontrols">
        <input name="add" id="add" type="submit" value="';
    $html .= '&nbsp;'.get_string('add');
    $html .= ' "title="add" ';
    $html .= ' /><br/>
        </div>
        <div id="removecontrols">
        <input name="remove" id="remove" type="submit" value="'.'&nbsp;'.get_string('remove').'"
        title="remove"/><br/>
        </div>
        </td>
        <td id="allcourses">
        <p><label >'.get_string('courses', 'local_courseprerequisite').'</label></p>'.
        local_courseprerequisite_courses($courseid).'</td>
        </td>
        </tr>
        </table>';
    return $html;
}
/*
 * Get the list of prerequisites 
 *
 * @uses $DB
 * @param $groupid holds the id of the group
 * @return $table html group type list
 */
function local_courseprerequisite_prerequisite($prereqid=0) {
    global $DB;
    $rows = 15;
    $output = '<div>'. "\n".
              '<select name="prerequisite[]" id="prerequisite" multiple="multiple" size="'.$rows.'">';
    $sql = "SELECT c.id, c.fullname
            FROM {course} c
            JOIN {local_prerequisitecourse} pq
            ON pq.courseid = c.id
            JOIN {local_courseprerequisite} cp
            ON cp.id = pq.prerequisiteid
            WHERE cp.id = $prereqid
            ORDER BY c.fullname";
    $prereqcrs = $DB->get_records_sql($sql);
    foreach ($prereqcrs as $pr) {
        $output .= "<option value=$pr->id>$pr->fullname</option>"."\n";        
    }
    $output .= "</select>";
    return $output;
}
/*
 * Get the list of all courses 
 *
 * @uses $DB
 * @param $groupid holds the id of the group
 * @return $table html group type list
 */
function local_courseprerequisite_courses($prereqid=0) {
    global $DB;
    $rows = 15;
    $output = '<div>'. "\n".
        '<select name="courses[]" id="courses" multiple="multiple" size="'.$rows.'">';
    if ($prereqid) {
        $sql = "SELECT c.id, c.fullname
                FROM {course} c
                WHERE c.id NOT IN (SELECT pq.courseid
                FROM {local_prerequisitecourse} pq
                WHERE pq.prerequisiteid = $prereqid)
                ORDER BY c.fullname";
        $courses = $DB->get_records_sql($sql);               
    } else {
        $courses = $DB->get_records_sql("SELECT id, fullname FROM {course} ORDER BY fullname");
    }
    foreach ($courses as $c) {
       $output .= "<option value=$c->id>$c->fullname</option>"."\n";
    }
    $output .= "</select>";
    return $output;
}

/*
 * Get the list of all course prerequisites 
 *
 * @uses $DB
 * @return $table html group type list
 */
function local_courseprerequisite_list() {
    global $DB, $CFG;
   
    // Create the table headings.
    $table = new html_table();
    $table->width = '100%';
    // Set the row heading object.
    $row = new html_table_row();
    // Create the cell.
    $cell = new html_table_cell();
    $cell->header = true;
    $cell->text = get_string('course', 'local_courseprerequisite');
    $cell->style = 'text-align:left';
    $row->cells[] = $cell;
    // Create the cell.
    $cell = new html_table_cell();
    $cell->header = true;
    $cell->text = get_string('prerequisitecourses', 'local_courseprerequisite');
    $cell->style = 'text-align:left';
    $row->cells[] = $cell;
    // Create the cell.
    $cell = new html_table_cell();
    $cell->header = true;
    $cell->text = get_string('tobecompleted', 'local_courseprerequisite');
    $cell->style = 'text-align:left';
    $row->cells[] = $cell;
    // Create the cell.
    $cell = new html_table_cell();
    $cell->header = true;
    $cell->text = get_string('action', 'local_courseprerequisite');
    $cell->style = 'text-align:left';
    $row->cells[] = $cell;
    $table->data[] = $row;     
    $sql = "SELECT cp.id as prereqid, c.fullname, cp.completedcount
            FROM {course} c
            JOIN {local_courseprerequisite} cp
            ON cp.courseid = c.id
            ORDER BY c.fullname";
    $courseprerequisites = $DB->get_records_sql($sql);
    foreach ($courseprerequisites as $cp) {
        $pcourse = array();
        $sql = "SELECT c.id, c.fullname
                FROM {course} c
                JOIN {local_prerequisitecourse} p
                ON p.courseid = c.id
                WHERE p.prerequisiteid = $cp->prereqid
                ORDER BY c.fullname";
        $prerequisites = $DB->get_records_sql($sql);
        foreach ($prerequisites as $pq) {
            $pcourse[] = $pq->fullname;
        }
        $pcourses = implode(",", $pcourse);
        $editlink = "<a href='$CFG->wwwroot/local/courseprerequisite/courseprerequisite.php?prereqid=$cp->prereqid&action=edit'>".
                    get_string('edit', 'local_courseprerequisite')."</a>";
        $deletelink = "<a href='$CFG->wwwroot/local/courseprerequisite/courseprerequisitelist.php?prereqid=$cp->prereqid&action=delete'>".
                    get_string('delete', 'local_courseprerequisite')."</a>";
        // Set the row heading object.
        $row = new html_table_row();
        // Create the cell.
        $cell = new html_table_cell();
        $cell->header = true;
        $cell->text = $cp->fullname;
        $row->cells[] = $cell;
        // Create the cell.
        $cell = new html_table_cell();
        $cell->header = true;
        $cell->text = $pcourses;
        $row->cells[] = $cell;
        // Create the cell.
        $cell = new html_table_cell();
        $cell->header = true;
        $cell->text = $cp->completedcount;
        $row->cells[] = $cell;
        // Create the cell.
        $cell = new html_table_cell();
        $cell->header = true;
        $cell->text = $editlink.' '.$deletelink;
        $row->cells[] = $cell;
        // Add header to the table.
        $table->data[] = $row;
    }
    // Add to the table.
    return html_writer::table($table);
}
