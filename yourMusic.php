<?php
include 'includes/includedFiles.php';
?>


<div class="playlistsContainer">
    <div class="gridContainer">
        <h2>PLAYLISTS</h2>
        <div class="buttonItems">
            <button class="button naranja" onclick="createPlaylist()">NEW PLAYLIST</button>
        </div>

        <?php
        $username = $userLoggedIn->getUsername();

        $playlistQuery = mysqli_query($conn, "SELECT * FROM playlists WHERE owner='$username'");

        if(mysqli_num_rows($playlistQuery) == 0) {
            echo "<span class='noResult'>You don't have any playlists yet</span>";
        }

        while($row = mysqli_fetch_array($playlistQuery)) {
            $playlist = new Playlist($conn, $row);

            echo "<div class=gridViewItem role='link' tabindex='0' onclick='openPage(\"playlist.php?id=" . $playlist->getId() . "\")'>
                    <div class='playlistImage'>
                        <img src='assets/images/icons/icons8-playlist.png'>
                    </div>
                    <div class='gridViewInfo'>"
                        . $playlist->getName() .
                    "</div>
                </div>";
        }
        ?>

    </div>
</div>
