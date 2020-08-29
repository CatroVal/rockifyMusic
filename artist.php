<?php
include 'includes/includedFiles.php';

if(isset($_GET['id'])) {
    $artistId = $_GET['id'];
} else {
    header('Location: index.php');
}

$artist = new Artist($conn, $artistId);

?>

<div class="entityInfo borderBottom">

    <div class="centerSection">

        <div class="artistInfo">
            <h1 class="artistName"><?php echo $artist->getName(); ?></h1>

            <div class="headerButtons">
                <button class="button naranja" onclick="playFirstSong()">PLAY</button>
            </div>
        </div>

    </div>

</div>

<div class="songListContainer borderBottom">
    <h2>SONGS</h2>
    <ul class="songList">
        <?php
            $songIdArray = $artist->getSongsId();

            $i = 1;

            foreach($songIdArray as $songId) {
                //Declaracion para parar el loop y no mostrar mas de 5 canciones
                if($i > 5) {
                    break;
                }

                $albumSong = new Song($conn, $songId);
                $albumArtist = $albumSong->getArtist();

                echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/icons-play.png' onclick='setTrack(\"". $albumSong->getId() ."\", tempPlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>

                        <div class='trackInfo'>
                            <span class='trackName'>" . $albumSong->getTitle() . "</span>
                            <span class='artistName'>" . $albumArtist->getName() . "</span>
                        </div>

                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionsButton' src='assets/images/icons/icons-more.png' onclick='showOptionsMenu(this)'>
                        </div>

                        <div class='trackDuration'>
                            <span class='duration'>" . $albumSong->getDuration() . "</span>
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

<div class="gridContainer">
    <h2>ALBUMS</h2>
    <?php
        $gridQuery = mysqli_query($conn, "SELECT * FROM albums WHERE artist_id='$artistId' LIMIT 4");

        while($row = mysqli_fetch_array($gridQuery)) {
            //Anchor tags reemplazados por <span> para ser usados como links!!!
            echo "<div class='gridViewItem'>
                    <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>
                        <img src='" . $row['artworkPath'] . "'>

                        <div class='gridViewInfo'>"
                            . $row['title'] .
                        "</div>
                    </span>
                </div>";
        }
    ?>
</div>

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?= Playlist::getPlaylistDropdown($conn, $userLoggedIn->getUserName()); ?>
</nav>
