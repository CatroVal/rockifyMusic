<?php
include 'includes/includedFiles.php';

if(isset($_GET['id'])) {
    $playlistId = $_GET['id'];

} else {
    header('Location: index.php');
}

$playlist = new Playlist($conn, $playlistId);
$owner = $playlist->getOwner();

?>

<div class="entityInfo">
    <div class="leftSection">
        <div class="playlistImage">
            <img src="assets/images/icons/icons8-playlist.png">
        </div>
    </div>

    <div class="rightSection">
        <h2><?= $playlist->getName(); ?></h2>
        <p>By <?= $playlist->getOwner(); ?></p>
        <p><?= $playlist->getPlaylistSongs(); ?> songs</p>
        <button class="button" onclick="deletePlaylist('<?= $playlistId; ?>')">DELETE PLAYLIST</button>
    </div>
</div>

<div class="songListContainer">
    <ul class="songList">
        <?php
            $songIdArray = $playlist->getSongIdFromPLaylist();

            $i = 1;

            foreach($songIdArray as $songId) {
                $playlistSong = new Song($conn, $songId);
                $songArtist = $playlistSong->getArtist();

                echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/icons-play.png' onclick='setTrack(\"". $playlistSong->getId() ."\", tempPlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <span class='trackName'>" . $playlistSong->getTitle() . "</span>
                            <span class='artistName'>" . $songArtist->getName() . "</span>
                        </div>

                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
                            <img class='optionsButton' src='assets/images/icons/icons-more.png' onclick='showOptionsMenu(this)'>
                        </div>

                        <div class='trackDuration'>
                            <span class='duration'>" . $playlistSong->getDuration() . "</span>
                        </div>
                    </li>";

                $i++;
            }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);
        </script>
    </ul>
</div>

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?= Playlist::getPlaylistDropdown($conn, $userLoggedIn->getUserName()); ?>
    <div class="item" onclick="removeFromPlaylist(this, '<?= $playlistId; ?>')">Remove from playlist</div>
</nav>
