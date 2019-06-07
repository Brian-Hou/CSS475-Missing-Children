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
    <h2>Update Missing Children</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check the Request is an Update from User -- Submitted via Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $foundStatus = $_POST['foundStatus'];
        if ($foundStatus === null)
            echo "<div><i>Specify a found status</i></div>";
        else if ($foundStatus === false)
            echo "<div><i>Specify a found status</i></div>";
        else if (trim($foundStatus) === "")
            echo "<div><i>Specify a found status</i></div>";
        else {

            /* perform update using safe parameterized sql */
            $sql = "UPDATE MissingPerson SET found = ? WHERE missingPersonID = ?";
            $stmt = $conn->stmt_init();
            if (!$stmt->prepare($sql)) {
                echo "failed to prepare";
            } else {

                // Bind user input to statement
                $stmt->bind_param('ss', $foundStatus,$id);

                // Execute statement and commit transaction
                $stmt->execute();
                $conn->commit();
            }
        }
    }

    /* Refresh the Data */
    $sql = "SELECT missingPersonID,firstName, lastName, dateMissing, found, birthDate, sexCode, raceCode, hairDescription, eyeColorDescription FROM MissingPerson M
       INNER JOIN EyeColor E ON M.eyeColorCode = E.eyeColorCode WHERE missingPersonID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $stmt->bind_result($missingPersonID,$firstName,$lastName,$dateMissing, $found, $birthDate,$sexCode, $raceCode, $hairDescription, $eyeColorDescription);
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <?php
            while ($stmt->fetch()) {
                echo '<a href="show_missing_person.php?id='  . $missingPersonID . '">' . $firstName . ' ' . $lastName . '</a><br>' . 'Found: ' . $found .
                    '<br>Date Missing: ' . $dateMissing . '<br>Birth Date: ' . $birthDate . '<br>Gender: '  . $sexCode . '<br>Race: ' . $raceCode . '<br>Hair Description: '
                    . $hairDescription . '<br>Eye Color Description: ' . $eyeColorDescription . '<br><br>';
            }
            ?><br><br>
            Found Status: <input type = "text" name = "foundStatus">
            <button type = "submit">Update</button>
        </form>
        <?php
    }

    $conn->close();

    ?>
</>
</body>
</html>
