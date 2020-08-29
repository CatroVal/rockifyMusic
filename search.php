<?php
include 'includes/includedFiles.php';

if(isset($_GET['term'])) {
    /*Usar "urldecode" para que eliminar, por ejemplo, espacios introducidos en la variable "$term".
    Si el valor de $term fuera The Beatles, sin urldecode el valor seria: 'The%20Beatles' */
    $term = urldecode($_GET['term']);

} else {
    $term = "";
}
?>

<div class="searchContainer">
    <h4>Search for an album, song or artist</h4>
    <input type="text" class="searchInput" value="<?= $term; ?>" placeholder="Search...">
</div>

<script>
//$(".searchInput").focus(); / Modificado ya que en Chrome no funciona!!!

$(function() {
    $(".searchInput").keyup(function() {
        clearTimeout(timer);

        timer = setTimeout(function() {
            var val = $(".searchInput").val();
            openPage("search.php?term=" + val);
        }, 2000);
    });
});

//Reemplaza el $(".searchInput").focus();!!!!
$(document).ready(function() {
    $(".searchInput").focus();
    var search = $(".searchInput").val();
    $(".searchInput").val('');
    $(".searchInput").val(search);
})
</script>

<?php
if($term == "") {
    echo "<span class='noResult'>Nothing to search...</span>";
    exit();
}
?>

<!--INICIO DE BUSQUEDA DE CANCIONES -->
<div class="songListContainer borderBottom">
    <h2>SONGS</h2>

    <ul class="songList">
        <?php

            $songsQuery = mysqli_query($conn, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10 ");

            if(mysqli_num_rows($songsQuery) == 0) {
                echo "<span class='noResult'>No songs matching your search</span>";
            }

            $songIdArray = array();

            $i = 1;

            while($row = mysqli_fetch_array($songsQuery)) {
                if($i > 15) {
                    break;
                }
                array_push($songIdArray, $row['id']);

                $albumSong = new Song($conn, $row['id']);
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
<!--FIN -->

<!--INICIO DE BUSQUEDA DE ARTISTAS -->
<div class="artistListContainer borderBottom">
    <h2>ARTISTS</h2>

    <?php
        $artistsQuery = mysqli_query($conn, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

        if(mysqli_num_rows($artistsQuery) == 0) {
            echo "<span class='noResult'>No artists matching your search</span>";
        }

        $artistsArray = array();

        while($row = mysqli_fetch_array($artistsQuery)) {
            $artistFound = new Artist($conn, $row['id']);

            echo "<div class='searchResultRow'>
                    <div class='artistName'>
                        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=". $artistFound->getId() ."\")'>
                            " . $artistFound->getName() . "
                        </span>
                    </div>
                </div>";
        }
    ?>
</div>
<!--FIN -->

<!--INICIO DE BUSQUEDA DE ALBUMS -->
<div class="gridContainer">
    <h2>ALBUMS</h2>

    <?php
        $albumsQuery = mysqli_query($conn, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

        if(mysqli_num_rows($albumsQuery) == 0) {
            echo "<span class='noResult'>No albums matching your search</span>";
        }

        while($row = mysqli_fetch_array($albumsQuery)) {
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
<!--FIN -->

<nav class="optionsMenu">
    <input type="hidden" class="songId">
    <?= Playlist::getPlaylistDropdown($conn, $userLoggedIn->getUserName()); ?>
</nav>
