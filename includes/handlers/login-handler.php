<?php

if(isset($_POST['loginButton'])) {
    //Si se presiona el boton log in
    $username = $_POST['loginUsername'];
    $password = $_POST['loginPassword'];

    $result = $account->login($username, $password);

    if($result) {
        //Si el login es correcto, guardamos el username en una variable de sesion
        $_SESSION['userLoggedIn'] = $username;
        header("Location: index.php");
    }
}
