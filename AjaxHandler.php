<?php

/**
 * @file
 * Database connection configuration.
 */

/**
 * Database connection username.
 * @var string
 */
$username = 'brtnamat';

/**
 * Database connection password.
 * @var string
 */
$passwordToDb = 'webove aplikace';

/**
 * Database server address.
 * @var string
 */
$address = 'localhost';

/**
 * Database name.
 * @var string
 */
$db = 'brtnamat';

/**
 * Database connection object.
 * @var mysqli
 */
$connection = new mysqli($address, $username, $passwordToDb, $db) or die("Není připojení k databázi");


if ($connection->connect_error) {
    die("Připojení k databázi selhalo: " . $connection->connect_error);
}

if(isset($_GET['table']) && $_GET["table"]=="trailLocations"){
    $sql = "SELECT * FROM trailLocations WHERE trailName = ?";
}elseif (isset($_GET['table']) && $_GET["table"]=="trailComplexity"){
    $sql = "SELECT * FROM trailComplexity WHERE trailName = ?";
}else{
    echo "Nepovolené volání scriptu - tento script není povolen volat uživatelem.";
    die();
}
$stmt = mysqli_prepare($connection, $sql);
mysqli_stmt_bind_param($stmt, "s", $_GET['location']);
mysqli_stmt_execute($stmt);
$result = $stmt->get_result();


$response = $result->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($response);
?>





