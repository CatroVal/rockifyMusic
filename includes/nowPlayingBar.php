<?php
$songQuery = mysqli_query($conn, "SELECT id FROM songs ORDER BY RAND() LIMIT 10");

$songsArray = array();

while($row = mysqli_fetch_array($songQuery)) {
    array_push($songsArray, $row['id']);
}
//Convertimos el array en un objeto json para poder usarlo en javascript
$jsonArray = json_encode($songsArray);
?>

<script>

$(document).ready(function() {
    var newPlaylist = <?php echo $jsonArray; ?>;
    audioElement = new Audio();
    setTrack(newPlaylist[0], newPlaylist, false);
    //Llamando esta funcion en el document.ready se setea el width al maximo al iniciar una canción.
    updateVolumeBar(audioElement.audio);

    //Previene el highlight de los botones de la barra de control cuando se produzcan estos eventos(mousedown, mousemove, etc)
    $("#nowPlayingBarContainer").on("mousedown mousemove touchmove touchstart", function(e) {
        e.preventDefault();
    })

    //COMIENZO DE CÓDIGO PARA DESPLAZAR LA BARRA DE PROGRESO DE CANCIÓN
    $(".playbackBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".playbackBar .progressBar").mousemove(function(e) {
        if(mouseDown == true) {
            //Muestra el tiempo de la canción dependiendo de la posición del mouse basado en el porcentaje que se ha recorrido en la barra de progreso
            timeFromOffset(e, this);
        }
    });

    $(".playbackBar .progressBar").mouseup(function(e) {
        timeFromOffset(e, this);
    });
    //FIN DE CODIGO DE DESPLAZAMIENTO

    //COMIENZO DE CÓDIGO PARA DESPLAZAR LA BARRA DE VOLUME
    $(".volumeBar .progressBar").mousedown(function() {
        mouseDown = true;
    });

    $(".volumeBar .progressBar").mousemove(function(e) {
        if(mouseDown == true) {
            var volumeLevel = e.offsetX / $(this).width();

            if(volumeLevel >= 0 && volumeLevel <= 1) {
                audioElement.audio.volume = volumeLevel;
            }
        }
    });

    $(".volumeBar .progressBar").mouseup(function(e) {
        var volumeLevel = e.offsetX / $(this).width();

        if(volumeLevel >= 0 && volumeLevel <= 1) {
            audioElement.audio.volume = volumeLevel;
        }
    });
    //FIN DE CÓDIGO DE DESPLAZAMIENTO DE VOLUMEN
    $(document).mouseup(function() {
        mouseDown = false;
    });

});


function timeFromOffset(mouse, progressBar) {
    var percentage = mouse.offsetX / $(progressBar).width() * 100;
    var seconds = audioElement.audio.duration * (percentage / 100);
    audioElement.setTime(seconds);
}

function prevSong() {
    if(audioElement.audio.currentTime >= 3 || currentIndex == 0) {
        audioElement.setTime(0);
    } else {
        currentIndex--;
        setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
    }
}

function nextSong() {
    if(repeat == true) {
        audioElement.setTime(0);
        playSong();
        return;
    }

    if(currentIndex == currentPlaylist.length - 1) {
        currentIndex = 0;
    } else {
        currentIndex++;
    }
    var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];
    setTrack(trackToPlay, currentPlaylist, true);
}

function setRepeat() {
    //Este if  se podria expresar así: repeat = !repeat;
    if(repeat == true) {
        repeat = false;
    } else {
        repeat = true;
    }
    var imageName = repeat ? "icons-repeat-orange.png" : "icons-repeat.png";
    $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
}

function setMuted() {
    audioElement.audio.muted = !audioElement.audio.muted;
    var imageName = audioElement.audio.muted ? "icons-mute-orange.png" : "icons-sound.png";
    $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
}

function setShuffle() {
    shuffle = !shuffle;
    var imageName = shuffle ? "icons-shuffle-orange.png" : "icons-shuffle.png";
    $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

    if(shuffle == true) {
        //Randomize playlist
        shuffleArray(shufflePlaylist);
        currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);
    } else {
        //shuffle desactivado. Vuelve a la playlist normal
        currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
    }
}

function shuffleArray(a) {
    var j, x, i;
    for (i = a.length - 1; i > 0; i--) {
        j = Math.floor(Math.random() * (i + 1));
        x = a[i];
        a[i] = a[j];
        a[j] = x;
    }
}

function setTrack(trackId, newPlaylist, play) {
    //INICIO: Randomize statement
    if(newPlaylist != currentPlaylist) {
        currentPlaylist = newPlaylist;
        shufflePlaylist = currentPlaylist.slice(); //'slice' hace una copia de 'currentPlaylist'
        shuffleArray(shufflePlaylist);
    }
    //FIN: Randomize statement

    if(shuffle == true) {
        currentIndex = shufflePlaylist.indexOf(trackId);
    } else {
    currentIndex = currentPlaylist.indexOf(trackId);
    }
    pauseSong();
    //audioElement.setTrack("assets/music/fake-gnr-01.mp3");
    /*
     * AJAX call: 1: Url a la pag donde se ecnuentra la llamada a ajax, 2: Datos que queramos pasar(nombre y valor). En este caso el id de la canción.
     * 3: Que queremos hacer con el resultado.
     * El primer parámetro es obligatorio. El 2 y 3 pueden ser opcionales.
     */
    $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data) {
        var track = JSON.parse(data);
        $(".trackName span").text(track.title);

        $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist_id }, function(data) {
            var artist = JSON.parse(data);
            $(".trackInfo .artistName span").text(artist.name);
            $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')");
        });

        $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album_id }, function(data) {
            var album = JSON.parse(data);
            $(".albumLink img").attr("src", album.artworkPath);
            $(".albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')");
            $(".trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')");
        });

        audioElement.setTrack(track);

        if(play == true) {
            playSong();
        }
    });
}

function playSong() {
    if(audioElement.audio.currentTime == 0) {
        //Ajax call para actualizar el campo plays de la tabla songs.
        $.post("includes/handlers/ajax/updatePlays.php", { songId: audioElement.currentlyPlaying.id });
    }
    $(".controlButton.play").hide();
    $(".controlButton.pause").show();
    audioElement.play();
}

function pauseSong() {
    $(".controlButton.play").show();
    $(".controlButton.pause").hide();
    audioElement.pause();
}

</script>


<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">

        <div id="nowPlayingLeft">
            <div class="content">
                <span class="albumLink">
                    <img role="link" tabindex="0" class="albumArtwork" src="">
                </span>

                <div class="trackInfo">
                    <span class="trackName">
                        <span role="link" tabindex="0"></span>
                    </span>
                    <span class="artistName">
                        <span role="link" tabindex="0"></span>
                    </span>
                </div>
            </div>
        </div>

        <div id="nowPlayingCenter">
            <div class="content PlayerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/icons-shuffle.png" alt="Shuffle">
                    </button>

                    <button class="controlButton previous" title="previous button" onclick="prevSong()">
                        <img src="assets/images/icons/icons-back-filled.png" alt="Previous">
                    </button>

                    <button class="controlButton play" title="play button" onclick="playSong()">
                        <img src="assets/images/icons/icons-circle-play.png" alt="Play">
                    </button>

                    <button class="controlButton pause" title="pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/icons-pausa.png" alt="Pause">
                    </button>

                    <button class="controlButton next" title="next button" onclick="nextSong()">
                        <img src="assets/images/icons/icons-next-filled.png" alt="Next">
                    </button>

                    <button class="controlButton repeat" title="repeat button" onclick="setRepeat()">
                        <img src="assets/images/icons/icons-repeat.png" alt="Repeat">
                    </button>
                </div>

                <div class="playbackBar">
                    <span class="progressTime current">0.00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progressTime remaining">0.00</span>
                </div>
            </div>
        </div>

        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="volume button" onclick="setMuted()">
                    <img src="assets/images/icons/icons-sound.png" alt="Volume">
                </button>
                <div class="progressBar">
                    <div class="progressBarBg">
                        <div class="progress"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
