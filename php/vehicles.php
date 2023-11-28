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
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <!-- CSS FOR STYLING THE PAGE  -->
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <script type="text/javascript" src="js/vehicles.js"></script>
  

</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <!-- class="logo_colour", allows you to change the colour of the text -->
        <h1><a href="index.php">SnoDEM<span class="logo_colour">_MITRU</span></a></h1>
        <h2>Vehicles</h2>
      </div>

      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="index.php">Home</a></li>
          <li class="selected"><a href="vehicles.php">Vehicles</a></li>
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
                	 $bname =  $rows['id'];
                   $vname = $row['name'];
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
            <!-- <?php echo $rows['id'];?></td> -->
				<td>
				   <input type="button" value=<?php echo $rows['id'];?> name=<?php echo $rows['name'];?>
				   onclick=  "edit_vehicle(this);" >      
				</td>
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
				<td>           
				   <input type="button" name="add" value="add" onclick= "add_vehicle();" > 
				</td>
				  
        </table>

     </div>
    </div>
    <div id="footer">
      <p><a href="index.html">Home</a> | <a href="vehicles.php">Vehicles</a> | <a href="alertsandalarms.php">Alerts and Alarms</a>
      | <a href="rules.php">Rules</a>
      | <a href="contacts.php">Alert/Alarm Contacts</a> | <a href="contact.html">Contact Us</a></p>
      <p>Copyright &copy; Snohomish County Department of Emergency Management </p>
    </div>
  </div>
</body>
</html>

