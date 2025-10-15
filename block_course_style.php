<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Newblock block caps.
 *
 * @package    block_course_style
 * @copyright  Daniel Neis <danielneis@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/accesslib.php');

class block_course_style extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_course_style');
    }

    public function get_content() {
        global $CFG, $OUTPUT, $COURSE, $DB, $PAGE, $USER;

        $record = $DB->get_record('block_course_style', ['course' => $COURSE->id]);
        if ($record) {
            echo '<style>
        #page, #page-content, #region-main, #topofscroll, .course-content .cards { background-color: ' . $record->bgcolor_general . ' !important; }
        #page-header .page-context-header { color: ' . $record->color_text . '; }
        .form-group.row.fitem, 
        .region_main_settings_menu_proxy, 
        #region-main>.card { background-color: ' . $record->bgcolor_course_body . '; }
        .card { background-color: ' . $record->bgcolor_blocks . '; }
        .course-content { color: ' . $record->color_text . '; }
        span.instancename { color: ' . $record->color_links . '; }
        body { font-family: ' . $record->font . '; }
        h3.sectionname a { color: ' . $record->color_titles . '; }';

            // Add banner image if it exists
            if (!empty($record->banner_url)) {
                echo '#page-header div.card { background-image: url(' . $record->banner_url . '); }';
            }

            echo '</style>';
        }
        $this->content = new stdClass();
        $this->content->items = array();
        $this->content->icons = array();
        $this->content->footer = '';
        $this->content->text = "";

        $admins = get_admins();
        $isadmin = false;
        foreach ($admins as $admin) {
            if ($USER->id == $admin->id) {
                 $isadmin = true;
                 break;
            }
        }

        $contextblock = context_block::instance($this->instance->id);
        $hascapability = has_capability('block/course_style:editstyle', $contextblock);

        if ($isadmin || $hascapability) {
            $this->content->text .= '<a href="' . $CFG->wwwroot . '/blocks/course_style/update_style.php?course='.$COURSE->id.'">'
             . get_string('tosetstyle', 'block_course_style') . '</a>';
        }
        return $this->content;
    }

    // My moodle can only have SITEID and it's redundant here, so take it away.
    public function applicable_formats() {
        return array('all' => false,
                     'site' => true,
                     'site-index' => true,
                     'course-view' => true,
                     'course-view-social' => false,
                     'mod' => true,
                     'mod-quiz' => false);
    }

    public function instance_allow_multiple() {
          return true;
    }

    public function has_config() {
        return false;
    }

    public function cron() {
        mtrace( "Hey, my cron script is running" );
        return true;
    }
}
