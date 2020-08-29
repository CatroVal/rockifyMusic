<?php
include 'includes/config.php';
include 'classes/User.php';
include 'classes/Playlist.php';
include 'classes/Artist.php';
include 'classes/Album.php';
include 'classes/Song.php';

//session_destroy(); LOGOUT!!!

/*Si existe la sesión la guardamos en una variable($userLoggedIn).
  De lo contrario redirigimos al usuario a la pagina de registro */
if(isset($_SESSION['userLoggedIn'])) {
    $userLoggedIn = new User($conn, $_SESSION['userLoggedIn']);
    $username = $userLoggedIn->getUsername();

    echo "<script> userLoggedIn = '$username' </script>";
} else {
    header('Location: register.php');
}
?>

<html>
<head>
    <meta charset="utf-8">
    <title>Welcome to Rockify</title>
    <link rel="stylesheet" type="text/css" href="assets/css/styles.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="assets/js/scripts.js" ></script>
</head>

<body>
    <div id="mainContainer">

        <div id="topContainer">
            <?php include 'includes/navBarContainer.php'; ?>

            <div id="mainViewContainer">
                <div id="mainContent">
