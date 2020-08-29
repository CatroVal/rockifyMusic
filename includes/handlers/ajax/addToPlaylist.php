<?php
include '../../config.php';

if(isset($_POST['playlistId']) && isset($_POST['songId'])) {
    $playlistId = $_POST['playlistId'];
    $songId = $_POST['songId'];

    $orderIdQuery = mysqli_query($conn, "SELECT MAX(playlistOrder) + 1 AS playlistOrder FROM playlistSongs WHERE playlist_id='$playlistId'");
    $row = mysqli_fetch_array($orderIdQuery);
    $order = $row['playlistOrder'];

    if($order == null) {
        $insertSongQuery = mysqli_query($conn, "INSERT INTO playlistSongs VALUES(null, '$songId', '$playlistId', 1)");
    } else {

        $insertSongQuery = mysqli_query($conn, "INSERT INTO playlistSongs VALUES(null, '$songId', '$playlistId', '$order')");

    }

} else {
    echo "playlistId or songId not passed into addToPlaylist.php file!!";
}

?>
