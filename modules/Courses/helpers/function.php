<?php

function getCategoriesCheckBox($categorise, $old = '', $parentId = 0, $char = '') {
    $id = request()->route()->category;
    if ($categorise) {
        foreach ($categorise as $key => $category) {
            if ($category['parent_id'] == $parentId && $id != $category['id']) {
                echo '<label class="d-block"><input type="checkbox" name="categories[]" value="'.$category->id.'">'.$char.$category->name.'</input></label>';
                unset($categorise[$key]);
                getCategoriesCheckBox($categorise, $old, $category['id'], $char.' |- ');
            }
        }
    }
}