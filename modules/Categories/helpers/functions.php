<?php

function getCategories($categorise, $old = '', $parentId = 0, $char = '') {
    $id = request()->route()->category;
    if ($categorise) {
        foreach ($categorise as $key => $category) {
            if ($category['parent_id'] == $parentId && $id != $category['id']) {
                
                echo '<option value="'.$category['id'].'" ';
                if ($old == $category['id']) {
                    echo ' selected';
                }
                echo '>'.$char.$category['name'].'</option>';
                unset($categorise[$key]);
                getCategories($categorise, $old, $category['id'], $char.' |- ');
            }
        }
    }
}