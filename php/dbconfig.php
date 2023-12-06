<?php
/* MySQL Database credentials.
//server with default setting (user 'root' with no password) */

define('DB_SERVER', 'localhost:3306');
define('DB_USERNAME', 'username');
define('DB_PASSWORD', 'password');
define('DB_NAME', 'mitru');

//print "DB Open : " . 'Success! '. "<br/>";

$conn = new mysqli(DB_SERVER, DB_USERNAME,
                DB_PASSWORD, DB_NAME);
                
// Checking for connections
if ($mysqli->connect_error) {
    die('Connect Error (' .
    $mysqli->connect_errno . ') '.
    $mysqli->connect_error);
}

?>
