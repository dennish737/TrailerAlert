<?php
$host_name = "localhost";
$database = "mitru";      // change to your DB name
$username = "wa7dem";     // database user id
$password = "SnoDEM720";  // user passwod

function valid_phone($phone){
        $valid_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
        return $valid_number;
}

$status = "";
//////// Do not Edit below /////////
try {
$dbo = new PDO('mysql:host='.$host_name.';dbname='.$database, $username, $password);
} catch (PDOException $e) {
print "Error!: " . $e->getMessage() . "<br/>";
die();
}

//if($_SERVER['REQUEST_METHOD'] == 'POST') {
if($_SERVER['REQUEST_METHOD'] == 'POST') {  //Fetch Variables
    $name =  $_POST['name'];
    $phone =  $_POST['phone'];
    $carrier = $_POST['carrier'];
    $level = $_POST['level'];
    $black = $_POST['Black'];
    $gray = $_POST['Gray'];
    $white = $_POST['White'];
        // make sure we have the rewuired variables
        if(empty($name) || empty($phone) || empty($carrier)) {
                $status = "name, phone and carrier id are required";
        } else {
                if(strlen($name) >=64 || !preg_match("/^[a-zA-Z-'\s]+$/", $name)) {
                        $status = "Please enter a valid name";
                } else if(!filter_var($phone, FILTER_SANITIZE_NUMBER_INT)){
                        $status = "Please enter a valid phone number";
                } else if($carrier == 0) {
                        $status = "Please enter a valid charrier";
                } else {
                        $status = "Your contact has been added";
                        $vphone = valid_phone($phone);
                        $vehicles = $black + $gray + $white;
                        $data = [ 'name' => $name,
                                          'phone' => $vphone,
                                          'carrier_id' => $carrier,
                                          'level' => $level,
                                          'vehicles' => $vehicles
                                        ];
                        $sql = "INSERT INTO contacts(name, phone, carrier_id, level, vehicles)
                                        VALUES(:name, :phone, :carrier_id, :level, :vehicles)";
                        //echo nl2br("\n$name\n $vphone\n $carrier\n $level\n $black\n $gray\n >
                        $stmt = $dbo->prepare($sql);
                        $stmt->execute($data);
                        $name = "";
                        $phone = "";
                        $_POST = array();  // clear data
                }
        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="css/style.css">
        <title>Add Contact</title>
</head>
<body>
        <div class="container">
          <h1>Add Contact Information</h1>

    <form action="" method="POST" class="main-form">
          <div class="form-group">
        <label for="name">Contact Name:</label>
        <input type="text" name="name" id="name" class="gt-input"
            value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST') echo $name ?>" required>
          </div>
          <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="text" name="phone" id="phone" class="gt-input"
                 value="<?php if($_SERVER['REQUEST_METHOD'] == 'POST') echo $phone  ?>" require>
          </div>
          <div class="form-group">
        <label for="carrier">Select a Carrier</label>
        <select name="carrier" id=carrier class="gt-input">
                <option disabled selected value> -- select an option -- </option>
        `       <?php
                /// get a list of carriers ////
                $sql="select carrier_name, id from carrier_sms";
                foreach ($dbo->query($sql) as $row) {
                    echo "<option value=$row[id]>$row[carrier_name]</option>";
                }
        ?>
                </select> <br>
        </div>
        <div class="form-group">
                <label for="level">Alert  Levels: </label>
                <select name="level" id="level" class="gt-input">
                <option value=0>None</option>
                <option value=1>Alarms Only</option>
                <option value=2>Alarms and Alerts</option>
                <option value=3>All</options>
                </select><br>
          </div>
          <div class="form-group">
                <label for="vehicles">Vehicles</label><br>
                <input type="hidden" name="Black" value="0" />
                <input type="checkbox" id="Black" name="Black" value=4>Black</input><br>
                <input type="hidden" name="Gray" value="0" />
                <input type="checkbox" id="Gray" name="Gray" value=2>Gray</input><br>
                <input type="hidden" name="White" value="0" />
                <input type="checkbox" id="White" name="White" value=1>White</input><br>
          </div>
        <input type="submit" class="gt_button value="submit" name="submit">
        <div class="form-status">
        <?php echo $status ?>
    </div>
        </form>
        <br>
</body>
</html>

