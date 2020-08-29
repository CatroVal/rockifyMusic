<?php

//Este archivo debe comprobar si el request es enviado por AJAX o si lo hace el usuario manualmente en la url
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
    include 'includes/config.php';
    include 'classes/User.php';
    include 'classes/Playlist.php';
    include 'classes/Artist.php';
    include 'classes/Album.php';
    include 'classes/Song.php';

    //Recoger variable "userLoggedIn" de la url para poder usarla en la llamada Ajax.
    if(isset($_GET['userLoggedIn'])) {
        $userLoggedIn = new User($conn, $_GET['userLoggedIn']);

    } else {
        echo "Username variable not passed into the file. Check openPage JS function";
        exit();
    }

} else {
    include 'includes/header.php';
    include 'includes/footer.php';

    $url = $_SERVER['REQUEST_URI'];
    echo "<script>openPage('$url')</script>";
    exit();
}

?>
