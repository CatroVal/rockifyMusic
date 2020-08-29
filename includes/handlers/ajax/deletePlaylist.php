<?php
include '../../config.php';

if(isset($_POST['playlistId'])) {
    $playlistId = $_POST['playlistId'];
    $playlistQuery = mysqli_query($conn, "DELETE FROM playlists WHERE id='$playlistId'");
    $playlistSongsQuery = mysqli_query($conn, "DELETE FROM playlistSongs WHERE playlist_id='$playlistId'");

} else {
    echo "Playlist id not passed into file!";
}

?>
