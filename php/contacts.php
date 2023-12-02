<!-- PHP code read contact table and dispaly it a s table -->
<?php
$host_name = "localhost";
$database = "mitru";      // change to your DB name
$username = "wa7dem";     // database user id
$password = "SnoDEM720";  // user passwod

function vehicle_list($vehicles)
{
    $white = "no";
    $gray = "no";
    $black = "no";

    switch($vehicles) {
        case 1:
            $white = "yes";
            break;
        case 2:
            $gray = "yes";
            break;
        case 3:
            $white = "yes";
            $gray = "yes";
                break;
        case 4:
                $black = "yes";
                break;
        case 5:
            $white = "yes";
            $black = "yes";
                break;
        case 6:
            $gray = "yes";
            $black = "yes";
                break;
        case 7:
            $white = "yes";
            $gray = "yes";
            $black = "yes";
                break;
        default:
            $white = "no";
            $grey = "no";
            $black = "no";
        }
        return [$white, $gray, $black];
}

function getColVal($row, $column)
{
        $rtn = $row[$column];
        if ($column == 'vehicles')
        {
                $rtn = vehicle_list($rtn);
        }
        return $rtn;
}

if(isset($_REQUEST['deleteFile']))
{   var_dump($_REQUEST);

        foreach( $_POST as $input_name => $input_value)
        {
                if (strpos($input_name, 'checkbox') != false)
                {
                        $ids[] = $input_value;
                }

    }
        print_r($ids);
}
//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host='.$host_name.';dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}

// SQL query to select data from database
//$sql = "SELECT * FROM contacts ORDER BY id";
$sql = "SELECT a.id, a.name, a.phone, b.carrier_name, a.level, a.vehicles
        FROM contacts a
        INNER JOIN carrier_sms b
        ON a.carrier_id = b.id
        ORDER BY a.id";

$rs = $dbo->query($sql);
?>

<!-- HTML code to display data in tabular format -->
<!DOCTYPE html>
<html lang="en">

<head>SnoDEM - Alert/Alarm Contacts</title>
  <meta name="description" content="website description" />
  <meta name="keywords" content="website keywords, website keywords" />
  <meta http-equiv="content-type" content="text/html; charset=windows-1252" />
  <link rel="stylesheet" type="text/css" href="css/style.css" />
  <!-- <script type="text/javascript" src="js/contacts.js"></script> -->
  <script type="text/javascript">
  function edit_contact(elem){
    var data= elem.value;
    //console.log(data);
    //alert('This alert box was called with the id click event =' + data);
    location.href = "edit_contact.php?contact=" + data;   
  }

  function add_contact(){
    //console.log(data);
    //alert('This alert box was called with the add click event');
    location.href = "add_contact.php";
  }

  function delete_contact (elem) {

  }  
  </script>
  
</head>

<body>
  <div id="main">
    <div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href="index.html">SnoDEM<span class="logo_colour">_MITRU</span></a></h1>
          <h2>Alert/Alarm Contacts.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li><a href="index.php">Home</a></li>
          <li><a href="vehicles.php">Vehicles</a></li>
          <li><a href="alertsandalarms.php">Alerts and Alarms</a></li>
          <li><a href="rules.php">Rules</a></li>
	  <li class="selected"><a href="contacts.php">Alert and Alarm Contacts</a></li>
          <li> <a href="contact.html">Contact Us</a></li>
        </ul>
      </div>
    </div>
    <div id="site_content">
      <div id="content">
        <h1>Contacts for Alerts and Alarms</h1>
        <!-- TABLE CONSTRUCTION -->
        <table>
            <!-- Construct TABLE header -->
            <tr>
                        <!-- PHP CODE TO FETCH COLUMN NAMES -->
                        <?php
                            for ( $i = 0; $i < $rs->columnCount(); $i++)
                                {
                                        $col = $rs->getColumnMeta($i);
                                        
                                        if( $col['name'] == 'id')
                                        {
                                        //{ 
                                            $columns[] = $col['name'];
                                            $loc_name = $col['name'];
                                            //echo "<th>" . $loc_name . "</th>";  
                                            echo "<th>" . "    " . "</th>"; 
                                        //    $columns[] = $col['name'];
                                        //    echo "<th>" . $col['name'] . "</th>";                                                                             	
                                        }                                        
                                        elseif( $col['name'] == 'vehicles')
                                        {
                                                $columns[] = $col['name'];
                                                // Add columns for vehicles
                                                echo "<th>Black</th>";
                                                echo "<th>Gray</th>";
                                                echo "<th>White</th>";
                                        } 
                                        else {
                                           $columns[] = $col['name'];
                                           echo "<th>" . $col['name'] . "</th>";
                                        }
                            }
                            echo "<th>Delete?</th>";
                        ?>
            </tr>
            <!-- PHP CODE TO FETCH DATA FROM ROWS -->
            <?php
                $rownumber = 0;
                // LOOP TILL END OF DATA
                while($rows=$rs->fetch())
                {
                //$bname = 'button'.$rows['v_id']
            ?>
            <tr>
                <!-- FETCHING DATA FROM EACH
                    ROW OF EVERY COLUMN -->
                        <?php
                           for ( $i = 0; $i < count($columns); $i++)
                           {
										  if( $columns[$i] == 'id')
										  {
										  	  //$vid = getColVal($rows,$columns[$i]);
										  	  $vid = $rows['id'];
										  	  $in_value = "Edit";
										  	  //echo "<input type='button' name='". getColVal($rows,$columns[$i]) . "' value='". getColVal($rows,$columns[$i]) . "' onclick= 'edit_contact(this)' >" ;
										  	  //echo "<td>" . getColVal($rows,$columns[$i]) . "</td>";
										  	  echo "<td> " ;
										  	  echo "<input type=button";
										  	  echo "  onclick='edit_contact(this)'";
										  	  echo "  name='" . $vid . "'";
										  	  echo "  value='" .$in_value . "'>";
										  	  
										  	  //echo $vid ;
										  	  echo "</td>";

										  }
                                elseif( $columns[$i] == 'vehicles')
                                {
                                    $vlist = vehicle_list($rows[$columns[$i]]);
                                    foreach($vlist as $v)
                                    {
                                        echo "<td>" . $v . "</td>";
                                    }
                                } else {
                                    echo "<td>" . getColVal($rows,$columns[$i]) . "</td>";
                                }
                            }
                            echo "<td><input type='checkbox' name='checkbox[" . $rownumber . "]' value='". $rows['id'] . "'</td>";

                                ?>

            </tr>
            <?php
                    $rownumber = $rownumber + 1;
                }
            ?>
            <tr>
              <td>
                 <input type="button" name="add" value="add" onclick= "add_contact();" >  
              </td>
            </tr>
        </table>
		</div>
		</div>    
     <div id="footer">
      <p><a href="index.php">Home</a> | <a href="page1.html">Page1</a> | <a href="page2.html">Page2</a> | <a href="page3.html">Page3</a> | <a href="contacts.php">Alarm/Alert Contacts</a> | <a href="contact.html">Contact Us</a></p>
      <p>Copyright &copy; Snohomish County Department of Emergency Management </p>
    </div>	</div>
<input type="submit" value="Delete" name="deleteFile"/>
<?php   $dbo=null; ?>
</body>

</html>


