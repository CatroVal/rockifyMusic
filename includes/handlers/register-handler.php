<?php
function sanitizeFormPassword($inputText) {
    $inputText = strip_tags($inputText);

    return $inputText;
}

function sanitizeFormUsername($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);

    return $inputText;
}

function sanitizeFormString($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);
    $inputText = ucfirst($inputText);

    return $inputText;
}

function sanitizeEmailString($inputText) {
    $inputText = strip_tags($inputText);
    $inputText = str_replace(" ", "", $inputText);

    return $inputText;
}

if(isset($_POST['registerButton'])) {
    //Si se presiona el botón Sign In
    $username = sanitizeFormUsername($_POST['username']);
    $firstName = sanitizeFormString($_POST['firstname']);
    $lastName = sanitizeFormString($_POST['lastname']);
    $email = sanitizeEmailString($_POST['email']);
    $email2 = sanitizeEmailString($_POST['email2']);
    $password = sanitizeFormPassword($_POST['password']);
    $password2 = sanitizeFormPassword($_POST['password2']);

    $registerSuccess = $account->register($username, $firstName, $lastName, $email, $email2, $password, $password2);

    if($registerSuccess) {
        //Si el registro de usuario es correcto, guardamos el username en la sesión
        $_SESSION['userLoggedIn'] = $username;
        header("Location: index.php");
    }
}
