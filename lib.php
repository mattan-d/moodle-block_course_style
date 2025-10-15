<?php

function block_course_style_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    
    if ($context->contextlevel != CONTEXT_BLOCK) {
        return false; 
    }
    
    if ($filearea !== 'block_course_style' && $filearea !== 'course_style') {
        return false;
    }
    $itemid = array_shift($args); 
 
    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); 
    if (!$args) {
        $filepath = '/'; 
    } else {
        $filepath = '/'.implode('/', $args).'/'; 
    }
 
    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'block_course_style', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }
 
    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering. 
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}