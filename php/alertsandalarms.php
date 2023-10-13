<!-- PHP code read contact table and dispaly it a s table -->
<?php
$host_name = "localhost";
$database = "mitru";      // change to your DB name
$username = "wa7dem";     // database user id
$password = "SnoDEM720";  // user passwod


function getColVal($row, $column)
{
        $rtn = $row[$column];
        if ($column == 'vehicles')
        {
                $rtn = vehicle_list($rtn);
        }
        return $rtn;
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
// 	a.cleared, a.clear_time, a.cleared_sent,
$sql = "SELECT a.id, a.rule_id, c.rule_name, a.post_time, a.sent, a.sent_time,
	a.acknowledged, a.acknowledge_time,
	a.v_id, b.name as vehicle, a.count, a.severity, a.message
FROM status_queue a
INNER JOIN vehicles b
ON a.v_id = b.id
INNER JOIN rules c
ON a.rule_id = c.id
WHERE a.cleared = 0
ORDER BY a.v_id ASC, a.severity ASC, a.post_time DESC;";

$rs = $dbo->query($sql);
?>

<!-- HTML code to display data in tabular format -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Alerts Alarms</title>
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
        <h1>Alerts and Alarms</h1>
        <!-- TABLE CONSTRUCTION -->
        <table>
            <tr>
                        <!-- PHP CODE TO FETCH COLUMN NAMES -->
                        <?php
                            for ( $i = 0; $i < $rs->columnCount(); $i++)
                            {
                                $col = $rs->getColumnMeta($i);
                                $columns[] = $col['name'];
                                echo "<th>" . $col['name'] . "</th>";    
                            }
                            //echo "<th>Acknowledge?</th>";
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
                                echo "<td>" . getColVal($rows,$columns[$i]) . "</td>";
                            }
                            //echo "<td><input type='checkbox' name='checkbox[" . $rownumber . "]' value='". $rows['id'] . "'</td>";

                                ?>

            </tr>
            <?php
                    $rownumber = $rownumber + 1;
                }
            ?>
        </table>

    </section>
<?php   $dbo=null; ?>
</body>

</html>


