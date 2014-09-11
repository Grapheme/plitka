<?php
require __DIR__.'/../../bootstrap/autoload.php';
$app = require_once __DIR__.'/../../bootstrap/start.php';

/***
 *
 * Вынести весь функционал в отдельный роут модуля system: /admin/scripts.js
 *
 ***/

/*** NEED TO ADD PROTECTION CODE ***/


$mods_file = storage_path('modules_js_modifications.dat');
$js_cache_file = storage_path('modules_js_cache.dat');

$files = glob(app_path('modules/*/js/*.js'));
#Helper::d($files);
$mod = @json_decode(file_get_contents($mods_file), 1);
#Helper::d($mod);

$need_reload = false;
foreach ($files as $file) {
    $mtime = @filemtime($file);
    #Helper::d($file . " - " . $mtime . " - " . $mod[$file]);
    if ($mtime != @$mod[$file]) {
        $need_reload = true;
        break;
    }
}

#Helper::d((int)$need_reload);

if ($need_reload) {
    $mods = array();
    $js = "";
    foreach ($files as $file) {
        $mtime = @filemtime($file);
        $mods[$file] = $mtime;
        $data = @file_get_contents($file);
        $js .= "/*** " . basename($file) . " ***/\n\n" . $data . "\n\n";
    }
    @file_put_contents($mods_file, json_encode($mods));
    $js = trim($js);
    file_put_contents($js_cache_file, $js);
    $prefix = "/*** FROM FILES ***/\n\n";
} else {
    $prefix = "/*** FROM CACHE ***/\n\n";
    $js = @file_get_contents($js_cache_file);
}

header('Content-Type: application/javascript');
echo @$prefix . @$js;