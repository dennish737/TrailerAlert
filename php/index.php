<?php

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
)
SELECT dl.v_id, v.name, v.status, dl.last_reading, ABS(TIMESTAMPDIFF(MINUTE, UTC_TIME(), dl.last_reading)) as t_diff, dl.loc, v.base,
                round(ST_DISTANCE_SPHERE(v.base_loc, dl.loc),3) as distance,
                dl.voltage, dl.temp
        FROM location_data dl
        INNER JOIN vehicles v
        ON dl.v_id = v.id
        ORDER BY v.name; ";
$result = $mysqli->query($sql);
$mysqli->close();
?>

<!-- HTML code to display data in tabular format -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>MITRU Information</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <!-- CSS FOR STYLING THE PAGE  -->
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <!-- <script type="text/javascript" src=js/clicked_vehicle.js></script> -->
  <script type="text/javascript">
	 function clicked_charts(elem) {
      var data= elem.value;
      location.href = "vehicle_charts.html?vehicle=" + data;
    }   
  </script>

</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <!-- class="logo_colour", allows you to change the colour of the text -->
        <h1><a href="index.php">SnoDEM<span class="logo_colour">_MITRU</span></a></h1>
        <h2>Summary</h2>
      </div>
    
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li class="selected"><a href="index.php">Home</a></li>
          <li><a href="vehicles.php">Vehicles</a></li>
          <li><a href="alertsandalarms.php">Alerts and Alarms</a></li>
          <li><a href="rules.php">Rules</a></li>
          <li><a href="contacts.php">Alert/Alarm Contacts</a></li>
          <li><a href="contact.html">Contact Us</a></li>
        </ul>
     </div>
   </div>
    <div id="site_content">
      <div id="content">

        <!-- TABLE CONSTRUCTION -->
        <table>
            <tr>
                <th>Name</th>
                <th>State</th>
                <th>Last Update</th>
                <th>dt from Last)</th>
                <th>Locator</th>
                <th>Base Location</th>
                <th>Distance From Base (m)</th>
                <th>Battery Voltage</th>
                <th>Temperature</th>
                <!-- <th>Status</th> -->
            </tr>
            <!-- PHP CODE TO FETCH DATA FROM ROWS -->
            <?php

                // LOOP TILL END OF DATA
                
                while($rows=$result->fetch_assoc())
                {
                $bname =  $rows['v_id'];
                $alarms = $row['alarm'];
                $alerts = $row['alerts'];
                $bcolor = getColor($alarms, $alerts);
                $vname = $row['name'];
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
                <!-- <td><?php echo $rows['name'];?></td> -->
                <td><input onclick="clicked_vehicle(this)" type="button" 
                    value =<php $bname] ?> name=<?php $vname ?>>
                    </td>

                <td><?php echo $rows['status'];?></td>
                <td><?php echo $rows['last_reading'];?></td>
                <td><?php echo $rows['t_diff'];?></td>
                <td><?php echo $rows['locator'];?></td>
                <td><?php echo $rows['base'];?></td>
                <td><?php echo $rows['distance'];?></td>
                <td><?php echo $rows['voltage'];?></td>
                <td><?php echo $rows['temp'];?></td>
                
            </tr>
            <?php
                }
            ?>
        </table>

     </div>
    </div>
    <div id="footer">    
      <p><a href="index.html">Home</a> | <a href="vehicles.php">Vehicles</a> | <a href="alertsandalarms.php">Alerts and Alarms</a>
      | <a href="rules.php">Rules</a>
      | <a href="contacts.php">Alert/Alarm Contacts</a> | <a href="contact.html">Contact Us</a></p>
      <p>Copyright &copy; Snohomish County Department of Emergency Management </p>
    </div>
  <div>
</body>
</html>    
