<?php
/**
 * @file
 * Trail page displaying information about a specific trail.
 */

session_start();

/**
 * Location variable containing the name of the trail.
 * @var string
 */
if(isset($_GET['location'])) {
    $location = htmlspecialchars($_GET['location']);
}

?>

<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>TrailSearch</title>
    <link rel="stylesheet" href="HomePageStyly.css">
    <script src="TrailPageScript.js" defer> </script>
</head>
<body>
<header>TrailSearch</header>

<?php
/**
 * Includes the navigation and post forms on the trail page.
 */
include_once "Navigation.php";
include_once "PostForms.php";
?>

<main>
    <div class="MainBox" id="Tocna">
        <hr>
        <?php
        /**
         * Displays validation error message if it exists.
         */
        if(isset($_REQUEST['validationError'])){
            echo "<p class='errorMessage'>";
            echo htmlspecialchars($_REQUEST['validationError']);
            echo "</p>";
        }
        ?>
        <article class="TrialText">
            <h1><?php echo $location?></h1>
            <p id="Trail_text">
                <?php
                /**
                 * Displays trail text content or a message if the trail does not exist.
                 */
                if(isset($location)){
                    $filename="TrailTexts/".$location.".txt";
                    $text=file($filename);
                    foreach ($text as $line){
                        echo $line;
                    }
                }else{
                    echo "Tento trail neexistuje";
                }
                ?>
            </p>
        </article>
        <div class="TrailImage flex-container" id="TocnaImage">
            <img src="TrailPictures/<?php echo $location?>/<?php echo $location."1.png"?>" alt="TrailPic1">
            <img src="TrailPictures/<?php echo $location?>/<?php echo $location."2.png"?>" alt="TrailPic2">
            <img src="TrailPictures/<?php echo $location?>/<?php echo $location."3.png"?>" alt="TrailPic3">
        </div>
        <div id="SpecialFeatures">
            <button id="ZobrazitLokaci" class="<?php echo $location?>"> Jak se tam dostat? </button>
            <button id="ZobrazitObtiznost" class="<?php echo $location?>"> Obtížnost </button>
        </div>
        <p id="TrailLokace" class="Closed"> </p>
        <a id="GoogleLokace" href="" class="Closed">Odkaz na Google mapy</a>
        <p id="TrailObtiznost" class="Closed"></p>
        <hr>
    </div>
</main>

<?php
/**
 * Includes the comment section if the location is set.
 */
if(isset($location)){
    include_once "CommentSection.php";
}
?>

</body>
</html>
