





<div class="flex-container" id="Reg_Log_Buttons">
    <?php
    /**
     * Creates button and link to profile page if user is logged.
     */
    if(isset($_SESSION['username'])){
        echo '<a href="ProfilePage.php?page=1"> Můj profil </a>';
        echo '<form action="PostHandler.php" method="post">
            <input type="submit" value="Odhlasit" name="LogOut" id="Odhlaseni" class="FormButtons">';

        /**
         * Makes hidden form element to use it later in PostHandler
         */
        if(isset($location)){
            echo '<input type="hidden" name="location" value="'.$location.'">';
        }
        echo "</form>";

    }else{
        echo '<button  id="OtevreniReg" class="FormButtons">Registrace</button>
              <button id="OtevreniLog" class="FormButtons">Přihlásit</button>';
    }
    ?>
</div>




<div class="VyskakovaciForm" id="LogDiv">
    <form  name="LoginForm" class="VyskakovaciForm" method="post"  action="PostHandler.php">
        <label id="logUsernameLabel">Username:
            <input type="text" value="<?php if(isset($_SESSION["formValues"]['login_username'])){
                echo htmlspecialchars($_SESSION["formValues"]['login_username']);
            }?>" placeholder="Username" class="Username" name="login_username">
        </label><br>
        <label id="logPasswordLabel">Heslo:
            <input type="password" class="Heslo" value="<?php if(isset($_SESSION["formValues"]['login_password'])){
                echo htmlspecialchars($_SESSION["formValues"]['login_password']);
            }?>" placeholder="Heslo" name="login_password">
        </label><br>
        <input type="hidden" name="location" value="<?php if(isset($location)) {
        echo $location;} ?>">
        <input type="submit" value="Přihlásit" name="login_submit" class="Odeslani" id="OdeslaniLog">
        <input type="button" value="Zavřít" id="ZavreniLog">
    </form>
</div>





<div class="VyskakovaciForm" id="RegDiv" >
    <form  name="RegisterForm" class="VyskakovaciForm" action="PostHandler.php" method="post">
        <label id="regUsernameLabel">Username: (povinné)
            <input type="text" placeholder="Username" value="<?php if(isset($_SESSION["formValues"]['register_username'])){
                echo htmlspecialchars($_SESSION["formValues"]['register_username']);
            }?>" class="Username" name="register_username">
        </label><br>
        <label >Jméno:
            <input type="text" placeholder="Jméno" value="<?php if(isset($_SESSION["formValues"]['register_firstName'])){
                echo htmlspecialchars($_SESSION["formValues"]['register_firstName']);
            }?>" class="Jmeno" name="register_firstName">
        </label><br>
        <label>Příjmení:
            <input type="text" placeholder="Příjmení" value="<?php if(isset($_SESSION["formValues"]['register_lasttName'])){
                echo htmlspecialchars($_SESSION["formValues"]['register_lastName']);
            }?>" class="Prijmeni" name="register_lastName">
        </label><br>
        <label id="regPasswordLabel">Heslo: (povinné)
            <input type="password" class="Heslo" placeholder="Heslo" value="<?php if(isset($_SESSION["formValues"]['register_firstPassword'])){
                echo htmlspecialchars($_SESSION["formValues"]['register_firstPassword']);
            }?>" name="register_firstPassword">
        </label><br>
        <label id="regConfirmPasswordLabel">Potvrdit heslo: (povinné)
            <input type="password" class="Heslo" placeholder="Heslo" value="<?php if(isset($_SESSION["formValues"]['register_secondPassword'])){
                echo htmlspecialchars($_SESSION["formValues"]['register_secondPassword']);
            }?>" name="register_secondPassword">
        </label><br>
        <input type="hidden" name="location" value="<?php if(isset($location)) {
            echo $location;} ?>">
        <input type="submit" value="Registrovat" name="register_submit" class="Odeslani">
        <input type="button" value="Zavrit" id="ZavreniReg">
    </form>
</div>
<?php
/**
 * Unsets the session variable holding form values after using it to fill the forms.
 */
unset($_SESSION['formValues']);
?>

