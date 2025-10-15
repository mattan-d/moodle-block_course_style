<?php

require_once('../../config.php');
require_once("$CFG->libdir/formslib.php");
class edit_style_form extends moodleform {

    protected $style;

    public function __construct($obj) {
        $this->style = $obj;
        parent::__construct(null);
    }

    function definition() {
        global $PAGE, $CFG, $DB;
        $style = $this->style;
        $mform = $this->_form;
        
        $mform->addElement('header', 'header', get_string('blocksettings', 'block'));

        $mform->addElement('hidden', 'id', null);
        $mform->setType('id', PARAM_INT);
        $mform->setConstant('id', $style->id);
        
        $mform->addElement('hidden', 'course', null);
        $mform->setType('course', PARAM_INT);
        $mform->setConstant('course', $style->course);
        
        $script = "<script>
                function savecolor(label) {
                    require(['jquery'], function($) {
                        var s = '#'+label;
                        var s_hidden ='[name='+ label + '_hidden]';
                        $(s_hidden).val($(s).val());
                    });
                }
            </script>";

        $mform->addElement('html',$script);

        $html_bgcolor_general = '<div class="form-group row fitem"> 
                    <label class="col-md-3 d-inline " for="bgcolor_general">' . get_string('bgcolor_general', 'block_course_style') . '</label>
                    <input class="col-md-1 form-inline felement" type="color" onChange="savecolor(\'bgcolor_general\')" id="bgcolor_general" name="bgcolor_general" value="' . $style->bgcolor_general . '" >
                </div>';
        $mform->addElement('html',$html_bgcolor_general);

        $mform->addElement('hidden', 'bgcolor_general_hidden', null);
        $mform->setType('bgcolor_general_hidden', PARAM_INT);
        $mform->setConstant('bgcolor_general_hidden', '-1');

        $html_bgcolor_course_body = '<div class="form-group row  fitem  "> 
                    <label class="col-md-3 d-inline " for="bgcolor_course_body">' . get_string('bgcolor_course_body', 'block_course_style') . '</label>
                    <input class="col-md-1 form-inline felement" type="color" onChange="savecolor(\'bgcolor_course_body\')" id="bgcolor_course_body" name="bgcolor_course_body" value="' . $style->bgcolor_course_body . '">
                </div>';        
        $mform->addElement('html',$html_bgcolor_course_body);

        $mform->addElement('hidden', 'bgcolor_course_body_hidden', null);
        $mform->setType('bgcolor_course_body_hidden', PARAM_INT);
        $mform->setConstant('bgcolor_course_body_hidden', '-1');

        $html_bgcolor_blocks = '<div class="form-group row  fitem  "> 
                    <label class="col-md-3 d-inline " for="bgcolor_blocks">' . get_string('bgcolor_blocks', 'block_course_style') . '</label>
                    <input class="col-md-1 form-inline felement" type="color" onChange="savecolor(\'bgcolor_blocks\')" id="bgcolor_blocks" name="bgcolor_blocks" value="' . $style->bgcolor_blocks . '" opacity>
                </div>';
        $mform->addElement('html',$html_bgcolor_blocks);

        $mform->addElement('hidden', 'bgcolor_blocks_hidden', null);
        $mform->setType('bgcolor_blocks_hidden', PARAM_INT);
        $mform->setConstant('bgcolor_blocks_hidden', '-1');

        $html_color_titles = '<div class="form-group row  fitem  "> 
                    <label class="col-md-3 d-inline " for="color_titles">' . get_string('color_titles', 'block_course_style') . '</label>
                    <input class="col-md-1 form-inline felement" type="color" onChange="savecolor(\'color_titles\')" id="color_titles" name="color_titles" value="' . $style->color_titles . '">
                </div>';
        $mform->addElement('html',$html_color_titles);

        $mform->addElement('hidden', 'color_titles_hidden', null);
        $mform->setType('color_titles_hidden', PARAM_INT);
        $mform->setConstant('color_titles_hidden', '-1');

        $html_color_text = '<div class="form-group row  fitem  "> 
                <label class="col-md-3 d-inline " for="color_text">' . get_string('color_text', 'block_course_style') . '</label>
                <input class="col-md-1 form-inline felement" type="color" onChange="savecolor(\'color_text\')" id="color_text" name="color_text" value="' . $style->color_text . '">
            </div>';
        $mform->addElement('html',$html_color_text);

        $mform->addElement('hidden', 'color_text_hidden', null);
        $mform->setType('color_text_hidden', PARAM_INT);
        $mform->setConstant('color_text_hidden', '-1');

        $html_color_links = '<div class="form-group row  fitem  "> 
                <label class="col-md-3 d-inline " for="color_links">' . get_string('color_links', 'block_course_style') . '</label>
                <input class="col-md-1 form-inline felement" type="color" onChange="savecolor(\'color_links\')" id="color_links" name="color_links" value="' . $style->color_links . '">
            </div>';
        $mform->addElement('html',$html_color_links);

        $mform->addElement('hidden', 'color_links_hidden', null);
        $mform->setType('color_links_hidden', PARAM_INT);
        $mform->setConstant('color_links_hidden', '-1');

        $mform->addElement('text', 'font', get_string('font', 'block_course_style'));
        $mform->setType('font', PARAM_TEXT);
        $mform->setConstant('font', $style->font);

        $mform->addElement('filepicker', 'banner_url', get_string('uploadimg', 'block_course_style'), null,
                array('maxbytes' => $CFG->maxbytes, 'accepted_types' => array('.png', '.jpg')));

        
        $buttonarray[] = &$mform->createElement('submit', 'savechanges', get_string('savechanges','block_course_style'), array('value' => 'savechanges', 'onclick' => 'savecolors()'));
                    
        $buttonarray[] = &$mform->createElement('submit', 'cancel', get_string('cancel','block_course_style'), array('value' => 'makecopy'));
        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');

    }


   

}