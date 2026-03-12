<?php
$username = 'root';
$passwordToDb = '';
$adress = 'localhost';
$db = 'trailsearch';
$connection = new mysqli($adress, $username, $passwordToDb, $db) or die("Není připojení k databázi");


function GetUserInfo($connection){
    $users=getAllUsers($connection);
    foreach($users as $user){
        if($user['username']===$_SESSION['username']){
            $allInfo=array($user['username'],$user['firstname'],$user['secondname'],$user['password']);
            return $allInfo;
        }
    }
}

function getAllUsers($connection){
    $sql = "SELECT * FROM users";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
    return $users;
}


$location=$_POST['location'];
echo $location;
$id=$_POST['id'];
echo $id;
if(isset($connection)){
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    header("Location: TrailPage.php?location=" . $location);
}

?>
