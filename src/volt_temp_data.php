<?php

// Username is wa7dem
$user = 'wa7dem';
$password = 'SnoDEM720';

// Database name is mitru
$database = 'mitru';

// Server is localhost with
// port number 3306
$servername='localhost:3306';
$mysqli = new mysqli($servername, $user,
                $password, $database);

// Checking for connections
if ($mysqli->connect_error) {
    die('Connect Error (' .
    $mysqli->connect_errno . ') '.
    $mysqli->connect_error);
}

// SQL query to select data from database
if (isset($_GET['vehicle'])) {
        $param = $_GET['vehicle'];
        $sql = " SELECT r.v_id, r.last_reading, TIMESTAMPDIFF(MINUTE,NOW() - INTERVAL 72 HOUR, r.last_reading) as t_diff,
        r.chan_value as volts, r2.chan_value as temp
        FROM readings r
        INNER JOIN readings r2
        ON r2.v_id = r.v_id AND r2.last_reading = r.last_reading
        WHERE r.c_id = 1 AND r2.c_id = 2 AND r.last_reading > NOW() - INTERVAL 72 HOUR AND r.v_id='$param'
        ORDER BY r.v_id ASC, r.last_reading ASC; ";
} else {
        $sql = " SELECT r.v_id, r.last_reading, TIMESTAMPDIFF(MINUTE,NOW() - INTERVAL 72 HOUR, r.last_reading) as t_diff,
        r.chan_value as volts, r2.chan_value as temp
        FROM readings r
        INNER JOIN readings r2
        ON r2.v_id = r.v_id AND r2.last_reading = r.last_reading
        WHERE r.c_id = 1 AND r2.c_id = 2 AND r.last_reading > NOW() - INTERVAL 72 HOUR
        ORDER BY r.v_id ASC, r.last_reading ASC; ";
}
$result = $mysqli->query($sql);

$data = [];

foreach ($result as $row) {
   $data[] = $row;
}

$mysqli->close();

echo json_encode($data);
?>

