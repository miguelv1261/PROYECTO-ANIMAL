<?php
ini_set('session.cookie_lifetime', 7 * 24 * 60 * 60);//7dias
ini_set('session.gc_maxlifetime', 7 * 24 * 60 * 60);
ini_set("max_execution_time", 0);
set_time_limit(0);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$base_path = str_replace('\\', '/', realpath(dirname(__FILE__))).'/';
$virtual_path = str_replace('\\', '/', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'])).'/';
define('BASE_PATH', $base_path);
define('VIRTUAL_PATH', $virtual_path);

?>