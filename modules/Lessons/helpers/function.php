<?php

use Illuminate\Support\Facades\Storage;

function getVideoInfo($url) {
    $getID3 = new \getID3;
    $path = Storage::disk('public')->path(str_replace('storage', '', $url));
    $file = $getID3->analyze($path);

    return $file;
}

function getLessons($lessons, $old = '', $parentId = 0, $char = '') {
    $id = request()->route()->lesson;
    if ($lessons) {
        foreach ($lessons as $key => $lesson) {
            if ($lesson['parent_id'] == $parentId && $id != $lesson['id']) {
                
                echo '<option value="'.$lesson['id'].'" ';
                if ($old == $lesson['id']) {
                    echo ' selected';
                }
                echo '>'.$char.$lesson['name'].'</option>';
                unset($lessons[$key]);
                getLessons($lessons, $old, $lesson['id'], $char.' |- ');
            }
        }
    }
}