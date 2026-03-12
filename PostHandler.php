<?php
/**
 * @file
 * Handles various actions such as posting comments, logging in, registering, and managing user profiles.
 */

session_start();


/**
 * Sets the global variable $location if the POST parameter is present and not empty.
 */
if(isset($_POST["location"]) && $_POST['location']!==""){
    $location=$_POST["location"];
}



/**
 * Database connection values same as database connect
 * DatanaseConnect not included because headers wont work otherwise
 */
$username = 'brtnamat';
$passwordToDb = 'webove aplikace';
$adress = 'localhost';
$db = 'brtnamat';


/**
 * Database connection object.
 * @var mysqli
 */
$connection = new mysqli($adress, $username, $passwordToDb, $db) or die("Není připojení k databázi");

/**
 * Checks if the request method is POST and the comment_submit button is set.
 * Processes the posted comment and redirects the user to the appropriate page.
 */
if(($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["comment_submit"])) {
    if(isset($_SESSION["username"]) && isset($connection) && isset($location)){
        $username = $_SESSION["username"];
        $message = $_POST["text"];
        $sql = "INSERT INTO comments (username, text, trailname) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $username, $message, $location);
        $result = mysqli_stmt_execute($stmt); // Execute SQL query
        mysqli_stmt_close($stmt);
    } else {
        if(isset($location)){
            header("Location: TrailPage.php?location={$location}&page={$_POST['page']}&errors=true"); // Moves user to loaction site if thats where it was called
            exit();
        }
    }
    header("Location: TrailPage.php?location={$location}&page={$_POST['page']}"); // Otherwise it moves user to home page
    exit();
}

/**
 * Checks if the LogOut button is set in the POST data.
 * Unsets all known sessions and redirects the user to the appropriate page.
 */
if(isset($_POST['LogOut'])){
    unset($_SESSION['username']);
    unset($_SESSION['admin']);
    if(isset($location)) {
        header("Location: TrailPage.php?location=" . $location."&page=1"); // Moves user to loaction site if thats where it was called
    }else{
        header("Location: HomePage.php"); // Otherwise it moves user to home page
    }
    exit();
}

$validationError="";


if($_SERVER["REQUEST_METHOD"] == "POST" && isset($connection)) {


    /**
     * Checks if wanted action was for user to log out
     */
    if (isset($_POST["login_submit"])) {
        $login_username = $_POST["login_username"];
        $login_password = $_POST["login_password"];
        $validationError = validateLogin($login_username, $login_password, $connection); // Stores errors if generated
        validationHeader($validationError, $_POST['location']); // Generates header
    }

      /**
     * Checks if wanted action was for user register
     */
    if (isset($_POST["register_submit"])) {
        $reg_username = $_POST["register_username"];
        $reg_password = $_POST["register_firstPassword"];
        $reg_confirm_password = $_POST["register_secondPassword"];
        $reg_firstName = $_POST["register_firstName"];
        $reg_lastName = $_POST["register_lastName"];
        $validationError = validateRegistration($reg_username, $reg_password, $reg_confirm_password,$reg_firstName,$reg_lastName,$connection); // Stores errors if generated
        validationHeader($validationError); //Generates header
    }
}

/**
 * Checks if the DeleteComment button is set in the POST data.
 * Deletes the specified comment and redirects the user to the appropriate page.
 */
if(isset($connection) && isset($_POST['DeleteComment'])){
    $id=$_POST['id'];
    $sql = "DELETE FROM comments WHERE id = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt); // Execute SQL query
    if($location){
        header("Location: TrailPage.php?location=" . $location."&page=".$_POST['page']);  // Moves user to loaction site if thats where it was called
        exit();
    }else{
        header("Location: ProfilePage.php?page=".$_POST['page']); // Otherwise it moves user to home page
        exit();
    }

}

/**
 * Checks if the insertPic button is set in the POST data.
 * Calls the SetPic function and redirects the user to the appropriate page.
 */
if(isset($_POST['insertPic'])){
    SetPic();
}

/**
 * Checks if the removePic button is set in the POST data.
 * Calls the RemovePic function and redirects the user to the appropriate page.
 */
if(isset($_POST['removePic'])){
    RemovePic();
    header("Location: ProfilePage.php?page=".$_POST['page']);
    exit();
}

/**
 * Checks if the changePasswords button is set in the POST data.
 * Updates the user's password based on the provided values.
 * Redirects the user to the appropriate page.
 */
if(isset($_POST['changeNames'])){
    if(isset($_POST['changeName']) && strlen($_POST['changeName'])>0 && strlen($_POST['changeName'])<17){ // Checks if lenght of new name is ok
        $sql = "UPDATE users SET firstname = ? WHERE username = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $_POST['changeName'], $_SESSION['username']);
        mysqli_stmt_execute($stmt); // Updates name based on username
    }
    if (isset($_POST['changeLastName']) && strlen($_POST['changeLastName'])>0 && strlen($_POST['changeLastName'])<17){ // Checks if lenght of new last name is ok
        $sql = "UPDATE users SET secondname = ? WHERE username = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $_POST['changeLastName'], $_SESSION['username']);
        mysqli_stmt_execute($stmt); // Updates last name based on username
    }
    header("Location: ProfilePage.php?page=".$_POST['page']);
    exit();
}

// Check if wanted action was changing password
if(isset($_POST['changePasswords'])){
    if(isset($_POST['changePasswordFirst']) && strlen($_POST['changePasswordFirst'])>=5 && $_POST['changePasswordFirst']===$_POST['changePasswordSecond']){ // Checks if passwords are long enough and they are the same
        $passHash=password_hash($_POST['changePasswordFirst'],PASSWORD_DEFAULT); // Using hash on the new password
        $sql = "UPDATE users SET password = ? WHERE username = ?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $passHash, $_SESSION['username']);
        mysqli_stmt_execute($stmt); // Updates the password based on username
        header("Location: ProfilePage.php?page=".$_POST['page']);
        exit();
    }else{
        header("Location: ProfilePage.php?page=".$_POST['page']."&errors=true"); // Returns to profile page with error statement if the form password was bad
        exit();
    }
}




/**
 * Validates user registration details and inserts a new user into the database upon successful validation.
 *
 * @param $validationError  -Errors that will be displayed
 * @return void - Validation error message, if any.
 */
function validationHeader($validationError): void
{
    // Adds evry filled field into session it can be writen to form 
    $_SESSION['formValues']=array();
    if (!empty($_POST["register_username"])){
        $_SESSION['formValues']['register_username']=$_POST["register_username"];
    }
    if (!empty($_POST["register_firstName"])){
        $_SESSION['formValues']['register_firstName']=$_POST["register_firstName"];
    }
    if (!empty($_POST["register_lastName"])){
        $_SESSION['formValues']['register_lastName']=$_POST["register_lastName"];
    }
    if (!empty($_POST["register_firstPassword"])){
        $_SESSION['formValues']['register_firstPassword']=$_POST["register_firstPassword"];
    }
    if (!empty($_POST["register_secondPassword"])){
        $_SESSION['formValues']['register_secondPassword']=$_POST["register_secondPassword"];
    }
    if (!empty($_POST["login_username"])){
        $_SESSION['formValues']['login_username']=$_POST["login_username"];
    }
    if (!empty($_POST["login_password"])){
        $_SESSION['formValues']['login_password']=$_POST["login_password"];
    }

    if (empty($validationError)) { // Checks if any errors should be displayed
        if (isset($_POST['location']) && strlen($_POST['location'])>2 ) { // Checks if location param is ok
            header("Location: TrailPage.php?location=" . $_POST['location']."&page=1"); // Returns on first page of given location
        } else {
            header("Location: HomePage.php"); // Returns on home page
        }
    } else {
        if (isset($_POST['location']) && strlen($_POST['location'])>2) {
            header("Location: TrailPage.php?location={$_POST['location']}&validationError={$validationError}&page=1");
        } else {
            header("Location: HomePage.php?validationError={$validationError}");
        }
    }
    exit();
}

/**
 * Removes the user's profile picture.
 */
function RemovePic(){
    $target_dir = "ProfilePics/"; // Gets the directory of pictures folder
    $target_file = $target_dir . $_SESSION['username'] .".jpg"; // Gets the name based on username
    if (file_exists($target_file)) { // Checks if this picture is in the folder
        unlink($target_file); // Deletes the picture
    }
}


/**
 * Handles user profile picture upload.
 */
function SetPic(){
    $target_dir = "ProfilePics/"; // Gets the directory of pictures folder
    $target_file = $target_dir . $_SESSION['username'] .".jpg"; // Gets the name based on username
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION); // Gets the info about type of file

    if(basename($_FILES["profileImage"]["name"]) != "") {

        if (file_exists($target_file)) { // Deletes picture if its there
            unlink($target_file);
        }

        if ($_FILES["profileImage"]["size"] > 5000000) { // Sets the maximum size of picture to be uploaded
            echo "Moc velký obrázek.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif")// Checks if its the right type of file
            {
            echo "Pouze soubory s koncovkou JPG, JPEG, PNG a GIF jsou povoleny.";
            $uploadOk = 0;
        }
    }else {
        echo "Nebyl vybrán žádný obrázek.";
    }

    if ($uploadOk == 0) {
        echo " Obrázek nebyl nahrán.";

    } else {
        if (move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file)) { // Uploads wanted picture
            header("Location: ProfilePage.php?page=".$_POST['page']); // Redirects to profile page
            exit();
        } else {
            echo " Obrázek nebyl nahrán.";
        }
    }
}

/**
 * Validates user login credentials and sets session variables upon successful login.
 *
 * @param string $username    - The entered username.
 * @param string $password    - The entered password.
 * @param mysqli $connection  - The MySQL database connection.
 *
 * @return string            - Validation error message, if any.
 */

function validateLogin($username, $password,$connection): string
{
    // Check if username or password is empty
    if (empty($username) || empty($password)) {
        return "Nezadané heslo nebo uživatelské jméno";
    }

    // Get all users from the database
    $users=getAllUsers($connection);

    // Loop through each user to find a match
    foreach ($users as $user){
        // Check if the username and password match
        if ($user["username"]==$username && password_verify($password,$user["password"])){
            // Set session variables for the logged-in user
            $_SESSION["username"]=$username;
            $_SESSION["admin"]=$user["admin"];
            return "";
        }
    }
    // Return error message if no matching user is found
    return "Špatné heslo nebo uživatelské jméno";
}



/**
 * Validates user registration details and inserts a new user into the database upon successful validation.
 *
 * @param string $username          - The entered username.
 * @param string $password          - The entered password.
 * @param string $confirmPassword   - The entered password confirmation.
 * @param string $firstName         - The entered first name.
 * @param string $lastName          - The entered last name.
 * @param mysqli $connection        - The MySQL database connection.
 *
 * @return string                   - Validation error message, if any.
 */

function validateRegistration($username, $password, $confirmPassword,$firstName,$lastName,$connection): string
{
    // Check if entered values are ok
    if (strlen($username) < 5 || strlen($password) < 5 || strlen($firstName)>16 || strlen($lastName)>16 || strlen($username)>16 ) {
        return "Nesprávně zadané hodnoty formuláře";
    }
    // Check if the entered passwords match
    if ($password !== $confirmPassword) {
        return "Neshodují se hesla";
    }
    $users=getAllUsers($connection);
    // Check if the username is already taken
    foreach ($users as $user) {
        if ($user["username"] == $username) {
            return "Toto uživatelské jméno již existuje";
        }
    }
    // Hashes the password
    $hashedPassword=password_hash($password, PASSWORD_DEFAULT);
    $adminValue=0;

    $sql = "INSERT INTO users (username, firstname, secondname, password, admin) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $username, $firstName, $lastName, $hashedPassword, $adminValue);
    $result = mysqli_stmt_execute($stmt); // Executes SQL query

    // Check if the insertion was successful
    if ($result === false) {
        return mysqli_error($connection);
    }

    mysqli_stmt_close($stmt);

    // Set session variable for the registered user
    $_SESSION['username']=$username;
    return "";
}
/**
 * Retrieves information about the currently logged-in user.
 *
 * @param mysqli $connection  - The MySQL database connection.
 *
 * @return array              - Array containing user information (username, first name, last name, password).
 */
function GetUserInfo($connection){
    $users=getAllUsers($connection);

    // Find and return information about the currently logged-in user
    foreach($users as $user){
        if($user['username']===$_SESSION['username']){
            $allInfo=array($user['username'],$user['firstname'],$user['secondname'],$user['password']);
            return $allInfo;
        }
    }
}


/**
 * Retrieves information about all users from the database.
 *
 * @param mysqli $connection  - The MySQL database connection.
 *
 * @return array              - Array containing information about all users.
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
