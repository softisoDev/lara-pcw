<?php

set_time_limit(30);
error_reporting(E_ALL);


/**
 * Check Token
 */
if (!isset($_GET['token']) || $_GET['token'] != 'f1909b9735bf5ccc50e0e5f3f59ea527' ) {
    header('HTTP/1.1 404 Not Found');
    echo '404 Not Found.';
    exit;
}


/**
 * Pull
 */
exec("cd /var/www/besres/data/www/larapcw.com/ && git pull origin master", $pull);


/**
 * Info
 */
echo "Host: " . $_SERVER['HTTP_HOST'] . "<br>Status: ok<br>Project: larapcw<br>";
echo 'Git: ' . implode('', $pull);
