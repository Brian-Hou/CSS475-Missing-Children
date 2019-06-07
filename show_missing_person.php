<?php
/**
 * Created by PhpStorm.
 * User: Melroy, Kevin, Sepehr, Brian
 * Date: 06/07/19
 * Time: 10:40am
 */
require_once 'config.inc.php';
// Get Missing Person ID
$id = $_GET['id'];
if ($id === "") {
    header('location: list_missing_children.php');
    exit();
}
if ($id === false) {
    header('location: list_missing_children.php');
    exit();
}
if ($id === null) {
    header('location: list_missing_children.php');
    exit();
}
?>
<html>
<head>
    <title>Sample PHP Database Program</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Show Missing Person</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL using Parameterized Form (Safe from SQL Injections)
    $sql = "SELECT missingPersonID,firstName, lastName, dateMissing, found, birthDate, sexCode, raceCode, hairDescription, eyeColorDescription FROM MissingPerson M
       INNER JOIN EyeColor E ON M.eyeColorCode = E.eyeColorCode WHERE missingPersonID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {

        // Bind Parameters from User Input
        $stmt->bind_param('s',$id);

        // Execute the Statement
        $stmt->execute();

        // Process Results Using Cursor
        $stmt->bind_result($missingPersonID,$firstName,$lastName,$dateMissing, $found, $birthDate,$sexCode, $raceCode, $hairDescription, $eyeColorDescription);
        echo "<div>";
        while ($stmt->fetch()) {
            echo '<a href="show_missing_person.php?id='  . $missingPersonID . '">' . $firstName . ' ' . $lastName . '</a><br>' . 'Found: ' . $found .
                '<br>Date Missing: ' . $dateMissing . '<br>Birth Date: ' . $birthDate . '<br>Gender: '  . $sexCode . '<br>Race: ' . $raceCode . '<br>Hair Description: '
            . $hairDescription . '<br>Eye Color Description: ' . $eyeColorDescription . '<br><br>';
        }
        echo "</div>";
        ?>
        <div>
            <a href="update_missing_children.php?id=<?= $missingPersonID ?>">Update Found Status </a>
        </div>
        <?php
    }

    $conn->close();

    ?>
</>
</body>
</html>
