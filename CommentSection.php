<?php

include_once "DatabaseConnect.php"

?>

<div id="commentsList">
    <hr>
    <h2 id="commentsHead">Komentáře</h2>
    <div id="insideCommentsList">

<?php
    
$itemsPerPage = 10; // Sets max number of commnents on site
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Calculates which comment should be first

if (isset($location) && isset($connection)) {
    /**
     * Calculates the index of comments to display on the page.
     * @var int
     */
    $offset = ($page - 1) * $itemsPerPage;

    /**
     * SQL query to select comments based on trailname with pagination.
     * @var string
     */

    $sql = "SELECT * FROM comments WHERE trailname = ? LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $location, $itemsPerPage, $offset);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $comments = mysqli_fetch_all($result, MYSQLI_ASSOC);// Gets all comments in assoc field
    mysqli_stmt_close($stmt); // SQL pro získání komentářů


    foreach ($comments as $comment) {
        echo "<h3>";
        echo "Uživatel: " . htmlspecialchars($comment["username"]);
        echo "</h3>";
        echo "<br>";
        echo "<p class='comment' >";

        echo  htmlspecialchars($comment["text"]);
        echo "</p>";

        /**
         * Adds delete button to comment if its alloved
         */
        if( (isset($_SESSION['username']) && $comment["username"]===$_SESSION['username']) || (isset($_SESSION["admin"]) && $_SESSION["admin"]==1)){
            echo "<form method='post' action='PostHandler.php'>
                 <input type='hidden' name='id' value='" . $comment["id"] . "'>
                 <input type='hidden' name='location' value='" . $location ."'>
                  <input type='hidden' name='page' value='" . $_GET['page'] ."'>
                 <button class='RemoveComment' name='DeleteComment'> Odstranit komentář </button>
            </form>";
        }
        echo "<br>";
    }


    /**
     * Algorithm to get links to previous and next pages.
     */
    $sql = "SELECT COUNT(id) FROM comments WHERE trailname = ?";
    $stmt = mysqli_prepare($connection, $sql);
    mysqli_stmt_bind_param($stmt, "s", $location);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_row($result);
    $totalComments = $row[0];
    mysqli_stmt_close($stmt);

    $totalPages = ceil($totalComments / $itemsPerPage);



echo "<hr>
    </div>
    <br>";
    /**
     * Displays links to previous and next pages if they are close enough.
     */
    for ($i = 1; $i <= $totalPages; $i++) {
        if($_GET['page']>=($i-3) && $_GET['page']<=($i+1) && $_GET['page']!=$i){
            echo "<a href='?page=$i&location=$location'>$i</a> ";
        }elseif( $_GET['page']==$i){
            echo $i. " ";
        }
        echo " ";
    }
}
 echo "</div>"

?>





<form id="commentForm" method="post" action="PostHandler.php">
    <label for="commentText">Vložte komentář: </label>
    <?php if(isset($_REQUEST['errors'])){
        echo '<p class="errorMessage">Uživatel musí být přihlášen </p>';
    } ?>
    <textarea id="commentText" placeholder="Add your comment..." maxlength="600" name="text"></textarea>
    <input type="hidden" name="location" value="<?php if(isset($location)) {
        echo $location;} ?>">
    <input type="hidden" name="page" value="<?php if(isset($_GET['page'])){
        echo $_GET['page'];}?>">
    <input type="submit" value="Vložit" name="comment_submit">
</form>