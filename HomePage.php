<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>TrailSearch</title>
    <link rel="stylesheet" href="HomePageStyly.css">
    <script src="HomePageScript.js" defer> </script>
</head>
<body>
<header>TrailSearch</header>
<?php
include_once "Navigation.php";
include_once "PostForms.php";
?>
<main>
    <div class="MainBox" id="DomovskaStranka">
        <?php
        if(isset($_REQUEST['validationError'])){
            echo "<p class='errorMessage'>";
            echo $_REQUEST['validationError'];
            echo "</p>";
        }
        ?>
        <article class="TrialText" id="TocnaText">
            <h1>Vítejte!</h1>
            <p id="Main_text">Vítejte na stránce TrailSearch. Tato stránka vznikla za účelem usnadnění vyhledávání trailů
                a bikeparků pro všechny skupiny bikerů v ČR. Každý zaregistrovaný a přihlašený uživatel
                může přidávat komentáře s hodnocením pod daný trail. Bez příhlášení je možné pouze traily
                prohlížet.
            </p>
        </article>
    </div>
</main>
</body>

</html>

