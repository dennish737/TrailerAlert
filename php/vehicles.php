<!-- PHP code to establish connection with the localserver -->
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
$sql = "SELECT id, name, vid, status, base, 
	ST_Y(base_loc) as latitude, ST_X(base_loc) as longitude,
	CASE WHEN info_enable = TRUE THEN 'ON' ELSE 'Off' END AS info_enable,
	CASE WHEN alert_enable = TRUE THEN 'ON' ELSE 'Off' END AS alert_enable,
	CASE WHEN alarm_enable = TRUE THEN 'ON' ELSE 'Off' END AS alarm_enable	
FROM vehicles;";

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
        <h1>Mitru Vehicles</h1>
        <!-- TABLE CONSTRUCTION -->
        <table>
            <tr>
			    <th>id</th>
                <th>Name</th>
                <th>APRS Name</th>
				<th>Status</th>
                <th>Base</th>
                <th>Base Latitude</th>
				<th>Base Longitude</th>
				<th>Info Messages</th>
				<th>Alert Messages</th>
				<th>Alarm Messages</th>				
            </tr>
            <!-- PHP CODE TO FETCH DATA FROM ROWS -->
			 <?php

                // LOOP TILL END OF DATA
                while($rows=$result->fetch_assoc())
                {
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
				<td><?php echo $rows['id'];?></td>
                <td><?php echo $rows['name'];?></td>
				<td><?php echo $rows['vid'];?></td>
                <td><?php echo $rows['status'];?></td>
                <td><?php echo $rows['base'];?></td>
                <td><?php echo $rows['latitude'];?></td>
				<td><?php echo $rows['longitude'];?></td>
                <td><?php echo $rows['info_enable'];?></td>
                <td><?php echo $rows['alert_enable'];?></td>
                <td><?php echo $rows['alarm_enable'];?></td>
            </tr>
            <?php
                }
            ?>
        </table>
    </section>
</body>

</html>

