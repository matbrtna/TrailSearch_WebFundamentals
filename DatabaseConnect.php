<?php

/**
 * @file
 * Database connection configuration.
 */

/**
 * Database connection username.
 * @var string
 */
$username = '';

/**
 * Database connection password.
 * @var string
 */
$passwordToDb = '';

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

/**
 * Retrieves information about the currently logged-in user.
 *
 * @param mysqli $connection The database connection object.
 * @return array An array containing user information (username, firstname, secondname, password).
 */
function GetUserInfo($connection){
    $users = getAllUsers($connection);
    foreach ($users as $user) {
        if ($user['username'] === $_SESSION['username']) {
            $allInfo = array($user['username'], $user['firstname'], $user['secondname'], $user['password']);
            return $allInfo;
        }
    }
}

/**
 * Retrieves information about all users from the database.
 *
 * @param mysqli $connection The database connection object.
 * @return array An array containing information about all users.
 */
function getAllUsers($connection){
    $sql = "SELECT * FROM users";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $users;
}
?>

