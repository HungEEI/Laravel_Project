<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

function getFileInfo($url) {
    $path = Storage::disk('public')->path(str_replace('storage', '', $url));

    return [
        'name' => basename($url),
        'size' => File::size($path)
    ];
}