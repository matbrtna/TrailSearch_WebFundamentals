<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>TrailSearch</title>
    <link rel="stylesheet" href="HomePageStyly.css">
    <script src="ProfilePageScript.js" defer> </script>
</head>
<body>
<header>TrailSearch</header>
<?php
include_once "Navigation.php";
include_once "DatabaseConnect.php"
?>
<main>
    <div class="MainBox" id="ProfileInfo">
        <h1>Profil</h1>
        <?php
        if(isset($_GET['errors'])){
            echo "<p class='errorMessage'> Hesla jsou moc krátké nebo se neshodují</p>";
        }

        /**
         * Writes all user info
         */
        if(isset($connection)){
            $userInfo=GetUserInfo($connection);
            echo "<h3> Uživatelské jméno: </h3> 
                <p>".htmlspecialchars($userInfo[0]). "</p>";
            echo "<h3> Jméno: </h3> 
                <p>".htmlspecialchars($userInfo[1]). "</p>";
            echo "<h3> Příjmení: </h3> 
                <p>".htmlspecialchars($userInfo[2]). "</p>";
        }else{
            echo "<p> Nelze se přihlásit k databázi</p>";
        }

        echo"<h3> Profilová fotka: </h3>";
        echo "<img src=" . getImage()." alt='profilová obrázek'>";
        ?>

        <form method="post" action="PostHandler.php" enctype="multipart/form-data">
            <label for="profileImage"></label>
            <input type="file" name="profileImage" id="profileImage">
            <br>
            <button type="submit" name="insertPic">Změnit profilový obrázek</button>
            <button type="submit" name="removePic">Odstranit profilový obrázek</button>
            <?php
            echo "<input type='hidden' name='page' value='" . $_GET['page'] . "'></form>";
            ?>

        <?php
        if(isset($connection)) {
            echo "<h3> Upravit jméno nebo příjmení: </h3>";
            echo " <form method='post' action='PostHandler.php' id='changeNames'>
                <label for='changeName'>Zadejte nové jméno:</label>
                <input type='text' name='changeName' id='changeName'>
                <label for='changeLastName'>Zadejte nové příjmení:</label>
                <input type='text' name='changeLastName' id='changeLastName'>
                <input type='submit' name='changeNames' >
                <input type='hidden' name='page' value='" . $_GET['page'] . "'>
                </form>
                <br>";

            echo "<h3> Upravit heslo: (hesla se musí  shodovat) </h3>";
            echo"<form method='post' action='PostHandler.php'  id='changePasswords'>
                <label for='changePasswordFirst'>Zadejte heslo:</label>
                <input type='password' name='changePasswordFirst' id='changePasswordFirst'>
                <label for='changePasswordSecond'>Potvrďte heslo:</label>
                <input type='password' name='changePasswordSecond' id='changePasswordSecond'>
                <input type='submit' name='changePasswords'>
                <input type='hidden' name='page' value='" . $_GET['page'] . "'>
                </form>";
        }

        if (isset($connection)) {
            $page = isset($_GET['page']) ? $_GET['page'] : 1; // Získání aktuální stránky, výchozí hodnota je 1
            $itemsPerPage = 8; // Počet položek na stránce
            $offset = ($page - 1) * $itemsPerPage;
            $sql = "SELECT * FROM comments WHERE username = ? LIMIT ?, ?";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "sii", $_SESSION["username"], $offset, $itemsPerPage);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);

            echo "<br>";
            echo "<h2> Vámi přidané komentáře: </h2>";

            foreach ($comments as $comment) {
                echo "<h3>";
                echo "Trail: " . htmlspecialchars($comment["trailname"]);
                echo "</h3>";
                echo "<br>";

                echo "<p class='comment' >";
                echo htmlspecialchars($comment["text"]);
                echo "</p>";
                echo "<form method='post' action='PostHandler.php'>
                 <input type='hidden' name='id' value='" . $comment["id"] . "'>
                 <input type='hidden' name='page' value='" . $_GET['page'] . "'>
                 <button class='RemoveComment' name='DeleteComment'> Odstranit komentář </button>
                </form>";
                echo "<br>";
            }

            /**
             * Rounds total pages number
             */
            $totalPages = ceil(countComments($connection, $_SESSION["username"]) / $itemsPerPage);

            echo "<div class='pagination'>";
            for ($i = 1; $i <= $totalPages; $i++) {
                if($_GET['page']>=($i-3) && $_GET['page']<=($i+1)  && $_GET['page']!=$i){
                    echo "<a href='?page=$i'>$i</a> ";
                }elseif( $_GET['page']==$i){
                    echo $i. " ";
                }
            }
            echo "</div>";
        }

        /**
         * Counts the total number of comments for a specific user.
         *
         * @param mysqli $connection The database connection object.
         * @param string $username The username for which to count comments.
         * @return int The total number of comments for the user.
         */
        function countComments($connection, $username) {
            $sql = "SELECT COUNT(*) as count FROM comments WHERE username = ?";
            $stmt = mysqli_prepare($connection, $sql);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = mysqli_fetch_assoc($result);

            return $row['count'];
        }


        /**
         * Retrieves the path to the user's profile picture.
         *
         * @return string The path to the profile picture.
         */
        function getImage(){
            /**
             * Gets path to picture
             */
            $path="ProfilePics/".$_SESSION['username'].".jpg";

            if(file_exists($path)){
                return $path;
            }else{
                return "BasePic.jpg";
            }
        }

        ?>
    </div>
</main>
</body>
</html>
