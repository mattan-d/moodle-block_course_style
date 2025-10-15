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
//print_r('dd');
//defined('MOODLE_INTERNAL') || die();
//print_r('ww');
require_once('../../config.php');
require_once('edit_style_form.php');

$course  = required_param('course', PARAM_INT);
global $DB, $COURSE;
require_login($course);

$url = new moodle_url('/blocks/course_style/update_style.php', array());
$PAGE->set_url($url);
$PAGE->set_pagelayout('admin');

$PAGE->set_title( get_string('setstyle', 'block_course_style'));
$PAGE->set_heading( get_string('setstyle', 'block_course_style'));
$PAGE->requires->css('/blocks/course_style/styles.css');

$style = new stdClass();
$style->id = -1;
$style->course = $course;
$style->bgcolor_general = "";
$style->bgcolor_course_body = "";
$style->bgcolor_blocks = "";
$style->color_titles = "";
$style->color_text = "";
$style->color_links = "";
$style->font = "";
$style->banner_url = "";

$record = $DB->get_record('block_course_style', ['course' => $course]);
if ($record) {
    $style->id = $record->id;
    $style->course = $record->course;
    $style->bgcolor_general = $record->bgcolor_general;
    $style->bgcolor_course_body = $record->bgcolor_course_body;
    $style->bgcolor_blocks = $record->bgcolor_blocks;
    $style->color_titles = $record->color_titles;
    $style->color_text = $record->color_text;
    $style->color_links = $record->color_links;
    $style->font = $record->font;
    $style->banner_url = $record->banner_url;
}

$mform = new edit_style_form($style);

if ($data = $mform->get_data()) {
    $datap = $_POST;
    if (isset($datap['cancel'])) {
        $url = new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $course);
        redirect($url);
    } else {
        if ($datap['bgcolor_general_hidden'] != '-1') {
            $style->bgcolor_general = $datap['bgcolor_general_hidden'];
        }


        if ($datap['bgcolor_course_body_hidden'] != '-1') {
            $style->bgcolor_course_body = $datap['bgcolor_course_body_hidden'];
        }
        if ($datap['bgcolor_blocks_hidden'] != '-1') {
            $style->bgcolor_blocks = $datap['bgcolor_blocks_hidden'];
        }
        if ($datap['color_titles_hidden'] != '-1') {
            $style->color_titles = $datap['color_titles_hidden'];
        }
        if ($datap['color_text_hidden'] != '-1') {
            $style->color_text = $datap['color_text_hidden'];
        }
        if ($datap['color_links_hidden'] != '-1') {
            $style->color_links = $datap['color_links_hidden'];
        }
        $style->font = $datap['font'];
        // Banner.
        if ($filename = $mform->get_new_filename('banner_url')) {
            global $USER;
            $contextcourse = context_course::instance($course);
            $block = $DB->get_record('block_instances', ['blockname' => 'course_style', 'parentcontextid' => $contextcourse->id]);
            $context = context_block::instance($block->id);
            $content = $mform->get_file_content('banner_url');
            $fs = get_file_storage();

            // Prepare file record object.
            $fileinfo = array(
                'contextid' => $context->id,
                'component' => 'block_course_style',
                'filearea' => 'block_course_style',
                'itemid' => $contextcourse->id,
                'filepath' => '/',
                'filename' => $filename);

            $sql = "SELECT * from mdl_files
                    where contextid = ? and component = 'block_course_style'
                    and filearea = 'block_course_style' and itemid = ? and filename = '$filename'";

            $ff = $DB->get_record_sql($sql, [$context->id, $contextcourse->id]);

            $flag = false;
            if ($ff) {
                $f = $fs->get_file($ff->contextid, 'block_course_style', 'block_course_style', $contextcourse->id, '/', $filename);
                if ($f) {
                    $file = $f;
                    $flag = true;
                }
            }
            if (! $flag) {
                $fs->create_file_from_string($fileinfo, $content);
                $file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                                        $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
            }
            $urlimg = moodle_url::make_pluginfile_url($file->get_contextid(), $file->get_component(), $file->get_filearea(),
                         $file->get_itemid(), $file->get_filepath(), $file->get_filename(), false);
            $style->banner_url = $urlimg->out();
        }
        if ($record) {
            $DB->update_record('block_course_style', $style);
        } else {
            $DB->insert_record('block_course_style', $style);
        }
        $url = new moodle_url($CFG->wwwroot . '/course/view.php?id=' . $course);
        redirect($url);
    }
}
echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->box_start('generalbox', 'notice');
echo $OUTPUT->box_end();
echo $OUTPUT->footer();
