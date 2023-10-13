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

<head>
    <meta charset="UTF-8">
    <title>Contacts</title>
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
        <h1>Contacts</h1>
        <!-- TABLE CONSTRUCTION -->
        <table>
            <tr>
                        <!-- PHP CODE TO FETCH COLUMN NAMES -->
                        <?php
                            for ( $i = 0; $i < $rs->columnCount(); $i++)
                                {
                                        $col = $rs->getColumnMeta($i);
                                        if( $col['name'] == 'vehicles')
                                        {
                                                $columns[] = $col['name'];
                                                // Add columns for vehicles
                                                echo "<th>Black</th>";
                                                echo "<th>Gray</th>";
                                                echo "<th>White</th>";
                                        } else {
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
                                if( $columns[$i] == 'vehicles')
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
        </table>

    </section>
<input type="submit" value="Delete" name="deleteFile"/>
<?php   $dbo=null; ?>
</body>

</html>


