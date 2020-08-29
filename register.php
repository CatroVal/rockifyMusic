<?php
    require 'includes/config.php';
    require 'classes/Account.php';
    require 'classes/Constants.php';

    $account = new Account($conn);

    require 'includes/handlers/register-handler.php';
    require 'includes/handlers/login-handler.php';

    function getInputValue($name) {
        if(isset($_POST[$name])) {
            echo $_POST[$name];
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rockify</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">
    <!--Referencia a libreria jQuery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="assets/js/register.js"></script>
</head>

<body>
    <!--Este codigo php activará el formulario que corresponda (Login o Register) con los errores de validacion-->
    <?php
     if(isset($_POST['registerButton'])) {
         echo '<script>
                $(document).ready(function() {
                    $("#loginForm").hide();
                    $("#registerForm").show();
                });
               </script>';
     } else {
         echo '<script>
                $(document).ready(function() {
                    $("#loginForm").show();
                    $("#registerForm").hide();
                });
               </script>';
     }
    ?>

    <div id="background">
        <div id="loginContainer">

            <div id="inputContainer">
                <form id="loginForm" action="" method="POST">
                    <h2>Login to your account</h2>
                    <p>
                        <?= $account->getError(Constants::$loginInvalid); ?>
                        <label for="loginUsername">Username</label>
                        <input id="loginUsername" name="loginUsername" type="text" value="<?= isset($_POST['loginUsername']) ? $_POST['loginUsername'] : ""; ?>" required>
                    </p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input id="loginPassword" name="loginPassword" type="password" required>
                    </p>
                    <button type="submit" name="loginButton">Log In</button>

                    <div class="hasAccountText">
                        <span id="hideLogin">Don't have an account yet? Register here!</span>
                    </div>
                </form>

                <form id="registerForm" action="register.php" method="POST">
                    <h2>Create your account</h2>
                    <p>
                        <?= $account->getError(Constants::$usernameCharacters); ?>
                        <?= $account->getError(Constants::$usernameTaken); ?>
                        <label for="username">Username</label>
                        <input id="username" name="username" type="text" placeholder="e.g. juanPerez" value="<?= isset($_POST['username']) ? $_POST['username'] : ""; ?>" required>
                    </p>
                    <p>
                        <?= $account->getError(Constants::$firstnameCharacters); ?>
                        <label for="firstname">First name</label>
                        <input id="firstname" name="firstname" type="text" placeholder="e.g. Juan" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : ""; ?>" required>
                    </p>
                    <p>
                        <?= $account->getError(Constants::$lastnameCharacters); ?>
                        <label for="lastname">Last name</label>
                        <input id="lastname" name="lastname" type="text" placeholder="e.g. Pérez" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : ""; ?>" required>
                    </p>
                    <p>
                        <?= $account->getError(Constants::$emailsNotMatch); ?>
                        <?= $account->getError(Constants::$emailInvalid); ?>
                        <?= $account->getError(Constants::$emailTaken); ?>
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email" placeholder="e.g. youremail@gmail.com" value="<?= isset($_POST['email']) ? $_POST['email'] : ""; ?>" required>
                    </p>
                    <p>
                        <label for="email2">Confirm Email</label>
                        <input id="email2" name="email2" type="email" placeholder="e.g. youremail@gmail.com" value="<?= isset($_POST['email2']) ? $_POST['email2'] : ""; ?>" required>
                    </p>
                    <p>
                        <?= $account->getError(Constants::$passwordsNotMatch); ?>
                        <?= $account->getError(Constants::$passwordCharacters); ?>
                        <?= $account->getError(Constants::$passwordAlphanumeric); ?>
                        <label for="password">Password</label>
                        <input id="password" name="password" type="password" required>
                    </p>
                    <p>
                        <label for="password2">Confirm password</label>
                        <input id="password2" name="password2" type="password" required>
                    </p>
                    <button type="submit" name="registerButton">Sign In</button>

                    <div class="hasAccountText">
                        <span id="hideRegister">Already have an account? Login here!</span>
                    </div>
                </form>

            </div>

            <div id="loginText">
                <h1>Who said Rock 'N' Roll is dead?</h1>
                <h2>Tons of songs that blow your mind!</h2>
                <ul>
                    <li>Discover songs from all rock genre</li>
                    <li>Create your own playlists</li>
                    <li>Follow artist to keep up to date</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
