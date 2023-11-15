<!-- PHP code to establish connection with the localserver -->
<?php

header("refresh: 60");
function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'miles') {
  $theta = $longitude1 - $longitude2;
  $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) 
			* cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
  $distance = acos($distance);
  $distance = rad2deg($distance);
  $distance = $distance * 60 * 1.1515;
  switch($unit) {
    case 'miles':
      break;
    case 'kilometers' :
      $distance = $distance * 1.609344;
  }
  return (round($distance,2));
}

function getColor($alarms, $alerts) {
	$color_value = 0;
	if ( $alarms > 0 ){
			$color_value = 2;
	}
	elseif ($alerts > 0) {
			$color_value = 1;
	}

	return $color_value;
}

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
$sql = " WITH chan_readings (v_id, last_reading, voltage, temp) AS (
        SELECT a.v_id, a.last_reading, a.chan_value, b.chan_value
        FROM readings a
        INNER JOIN readings b
        ON b.v_id = a.v_id AND a.last_reading = b.last_reading
        WHERE a.c_id=1 AND b.c_id=2

), 
   location_data AS (
                SELECT l.v_id, l.last_reading, l.locator, l.loc, cr.voltage, cr.temp
                FROM locations l
                LEFT JOIN chan_readings cr
                ON cr.v_id = l.v_id AND cr.last_reading = l.last_reading
                WHERE l.last_reading in (SELECT max(last_reading) FROM locations GROUP by v_id)
), 				
	alarms_alerts AS (
		SELECT v_id, SUM(CASE WHEN severity = 'ALERT' THEN 1 ELSE 0 END) as 'alerts',
				SUM(CASE WHEN severity = 'ALARM' THEN 1 ELSE 0 END) as 'alarms'
		FROM status_queue 
		WHERE cleared = 0 AND acknowledged = 0 
		GROUP BY v_id
)
SELECT dl.v_id, v.name, v.status, dl.last_reading, ABS(TIMESTAMPDIFF(MINUTE, UTC_TIME(), dl.last_reading)) as t_diff, 
			dl.locator, dl.loc, v.base,
            round(ST_DISTANCE_SPHERE(v.base_loc, dl.loc),3) as distance, 
            dl.voltage, dl.temp, IFNULL(alm.alarms, 0) as alarms, IFNULL(alm.alerts, 0) as alerts
        FROM location_data dl
        INNER JOIN vehicles v
        ON dl.v_id = v.id
		LEFT JOIN alarms_alerts alm
		ON alm.v_id = v.id
        ORDER BY v.name;";
$result = $mysqli->query($sql);
$mysqli->close();
?>

<!-- HTML code to display data in tabular format -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>MITRU Information</title>
    <!-- CSS FOR STYLING THE PAGE -->
    <style>
		.checked-0{color:green;}
		.checked-1{color:yellow;}
		.checked-2{color:red;}
        table {
            margin: 0 auto;
            font-size: large;
            border: 1px solid black;
        }

        h1 {
            text-align: center;
            color: #006600;
            font-size: xx-large;
            font-family: 'Gill Sans', 'Gill Sans MT',
            ' Calibri', 'Trebuchet MS', 'sans-serif';
        }

        td {
            background-color: #E4F5D4;
            border: 1px solid black;
        }

        th,
        td {
            font-weight: bold;
            border: 1px solid black;
            padding: 10px;
            text-align: center;
        }

        td {
            font-weight: lighter;
        }
    </style>
</head>

<body>
    <section>
        <h1>Mitru Summary</h1>
        <!-- TABLE CONSTRUCTION -->
        <table>
            <tr>
                <th>Name</th>
                                <th>State</th>
                <th>Last Update ( UTC )</th>
                <th>dt from Last)</th>
                <th>Locator</th>
                <th>Base Location</th>
                <th>Distance From Base (m)</th>
                <th>Battery Voltage</th>
                <th>Temperature</th>
				<th>Alarms</th>
				<th>Alerts</th>
                <th>Charts</th>
            </tr>
            <!-- PHP CODE TO FETCH DATA FROM ROWS -->
            <?php

                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
                $bname = 'button'.$rows['v_id'];
				$alarms = $row['alarms'];
				$alerts = $row['alerts'];
				$bcolor = getColor($alarms, $alerts);
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
                <td><?php echo $rows['name'];?></td>
                                <td><?php echo $rows['status'];?></td>
                <td><?php echo $rows['last_reading'];?></td>
                                <td><?php echo $rows['t_diff'];?></td>
                <td><?php echo $rows['locator'];?></td>
                <td><?php echo $rows['base'];?></td>
                <td><?php echo $rows['distance'];?></td>
                <td><?php echo $rows['voltage'];?></td>
                <td><?php echo $rows['temp'];?></td>
				<td><?php echo $rows['alarms'];?></td>
				<td><?php echo $rows['alerts'];?></td>
                <script>
                    console.log(<?= json_encode($bname); ?>);
                </script>
                <td><button id=<?php echo $bname?> 
					class="float-left submit-button checked-0<?php echo $bcolor; ?>">Status</button><td>
            </tr>
            <?php
                }
            ?>
        </table>
                <script type="text/javascript">
                        document.getElementById("button1").onclick = function () {
                                location.href = "vehicle_charts.html?vehicle=1";
                        };
                        document.getElementById("button2").onclick = function () {
                                location.href = "vehicle_charts.html?vehicle=2";
                        };
                        document.getElementById("button3").onclick = function () {
                                location.href = "vehicle_charts.html?vehicle=3";
                        };
                </script>
    </section>
</body>

</html>

