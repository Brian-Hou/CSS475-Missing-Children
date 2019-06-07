<?php
/**
 * Created by PhpStorm.
 * User: Melroy, Kevin, Sepehr, Brian
 * Date: 06/07/19
 * Time: 10:40am
 */
require_once 'config.inc.php';

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
    <h2>Missing Children List</h2>
    <?php
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL Statement
    $sql = "SELECT missingPersonID, firstName,lastName FROM MissingPerson ORDER BY firstName, lastName";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {
        // Execute the Statement
        $stmt->execute();

        // Loop Through Result
        $stmt->bind_result($missingPersonID,$firstName,$lastName);
        echo "<ul>";
        while ($stmt->fetch()) {
            echo '<li><a href="show_missing_person.php?id='  . $missingPersonID . '">' . $firstName . ' ' . $lastName . '</a></li>';
        }
        echo "</ul>";
    }
    ?>
    <form action="search_missing_children.php" method = "get">
    Filter By First Name: <input type = "text" name="fname"><br>
    <input type="submit">
    </form>
    <?php
    // Close Connection
    $conn->close();

    ?>
</div>
</body>
</html>
