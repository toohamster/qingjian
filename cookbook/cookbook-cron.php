#!/usr/bin/env php
<?php
date_default_timezone_set('PRC');
$code_dir = '/data/softs/CookBook/bin';

$commands = array(
        "cd {$code_dir}",
        "java cookbook.Cookbook_week",
);

$commands = array_map('escapeshellcmd', $commands);

// 可以使用 ; 或者 && 来联立指令
$cmd_line = implode('&&', $commands);

ob_start();
system($cmd_line, $re);
$content = ob_get_contents();
ob_end_clean();

$file = __DIR__ . '/db.dat';

if (file_exists($file)) {
    $olddata = file_get_contents($file);
    file_put_contents(__DIR__.'/olddata/b-'. date('Ymd',@filemtime($file)).'.dat', $olddata);
}    

file_put_contents($file, $content);
print 'build success!' . PHP_EOL;
?>
