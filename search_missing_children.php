<?php
/**
 * Created by PhpStorm.
 * User: Melroy, Kevin, Sepehr, Brian
 * Date: 06/07/19
 * Time: 10:40am
 */
require_once 'config.inc.php';
// Get Missing Person Name
$fname = $_GET['fname'];

if ($fname === "") {
    header('location: list_missing_children.php');
    exit();
}
if ($fname === false) {
    header('location: list_missing_children.php');
    exit();
}
if ($fname === null) {
    header('location: list_missing_children.php');
    exit();
}
?>

<html lang = "en">
<head>
    <title>Sample PHP Database Program</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Missing Person's Report</h2>
    <?php
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL Statement, supports partial matching of Missing Perso's first name
    $sql = "SELECT missingPersonID,firstName, lastName, dateMissing, found, birthDate, sexCode, raceCode, hairDescription, eyeColorDescription
        FROM MissingPerson M  INNER JOIN EyeColor E ON M.eyeColorCode = E.eyeColorCode WHERE firstName LIKE ? OR firstName LIKE '{$fname}%' OR firstName LIKE '%{$fname}' OR firstName LIKE '%{$fname}%' ";


    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {

    $stmt->bind_param('s', $fname);
    $stmt->execute();
    $stmt->bind_result($missingPersonID,$firstName,$lastName,$dateMissing, $found, $birthDate,$sexCode, $raceCode, $hairDescription, $eyeColorDescription);
        while ($stmt->fetch()) {
            echo '<a href="show_missing_person.php?id='  . $missingPersonID . '">' . $firstName . ' ' . $lastName . '</a><br>' . 'Found: ' . $found .
                '<br>Date Missing: ' . $dateMissing . '<br>Birth Date: ' . $birthDate . '<br>Gender: '  . $sexCode . '<br>Race: ' . $raceCode . '<br>Hair Description: '
                . $hairDescription . '<br>Eye Color Description: ' . $eyeColorDescription . '<br><br>';
        }
    }

    // Close Connection
    $conn->close();

    ?>
</div>
</body>
</html>




