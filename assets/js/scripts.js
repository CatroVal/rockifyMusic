var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;


//Evento desaparecer el menu cuando se clickea fuera de la caja del menu
$(document).click(function(click) {
    var target = $(click.target);

    if(!target.hasClass("item") && !target.hasClass("optionsButton")) {
        hideOptionsMenu();
    }
});

//Evento desaparecer el menu en cuanto se hace scroll
$(window).scroll(function() {
    hideOptionsMenu();
});


$(document).on("change", "select.playlist", function() {
    var select = $(this);
    var playlistId = select.val();
    //La variable "songId" va a almacenar el valor ("id") del campo hidden, el cual tiene la clase ".songId"
    var songId = select.prev(".songId").val();

    $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function(error) {

        if(error != "") {
            alert(error);
            return;
        }

        hideOptionsMenu();
        select.val("");
    });
});


//Actualizar EMAIL
function updateEmail(newEmailClass) {
    var emailValue = $("." + newEmailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php", { email: emailValue, username: userLoggedIn })
    .done(function(response) {
        $("." + newEmailClass).nextAll(".message").text(response);
    })
}

//Actualizar PASSWORD
function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2) {
    var oldPassword = $("." + oldPasswordClass).val();
    var newPassword1 = $("." + newPasswordClass1).val();
    var newPassword2 = $("." + newPasswordClass2).val();

    $.post("includes/handlers/ajax/updatePassword.php",
        { oldPassword: oldPassword,
            newPassword1: newPassword1,
            newPassword2: newPassword2,
            username: userLoggedIn })
    .done(function(response) {
        $("." + oldPasswordClass).nextAll(".message").text(response);
    });
}

//LOGOUT
function logout() {
    $.post("includes/handlers/ajax/logout.php", function() {
        location.reload();
    });
}

//Funcion para eliminar canción de una playlist
function removeFromPlaylist(button, playlistId) {
    var songId = $(button).prevAll(".songId").val();

    $.post("includes/handlers/ajax/removeFromPlaylist.php", { playlistId: playlistId, songId: songId})
    .done(function(error) {

        if(error != "") {
            alert(error);
            return;
        }

        openPage("playlist.php?id=" + playlistId);
    });
}


//Funcion para esconder el menu de opciones de las canciones
function hideOptionsMenu() {
    var menu = $(".optionsMenu");

     if(menu.css("display") != "none") {
         menu.css("display", "none");
     }
}

//Función para desplegar el munú de opciones de las canciones
function showOptionsMenu(button) {
    var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();

    menu.find(".songId").val(songId);

    var scrollTop = $(window).scrollTop(); //Distancia desde el principio de la ventana hasta el principio del documento
    var elementOffset = $(button).offset().top; //Distancia desde el comienzo del documento hasta el elemento

    var top = elementOffset - scrollTop;
    var left = $(button).position().left; //Creo un obj jQuery del elemento "button" pasado como parámetro en la función porque es un elemento HTML
                                            //y no se puede usar como tal dentro de la función.
    menu.css({ "top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
}

//Funcion de creación de nueva playlist con llamada Ajax
function createPlaylist() {
    var popup = prompt("Enter a name for your playlist");

    if(popup != null) {
        //Llamada Ajax para poder ejecutar consulta SQL
        //Uso de .done para hacer la llamada Ajax. En otras llamadas he usado el codigo .success (ver llamada en nowPlayingBar.php)
        $.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn }).done(function(error) {
            //Ejecutar al retorno de la llamada AJAX
            if(error != "") {
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}

//Funcion de borrado de playlist con llamada Ajax
function deletePlaylist(playlistId) {
    var prompt = confirm("Are you sure you want to delete this playlist?");

    if(prompt) {
        //console.log("delete playlist");
        $.post("includes/handlers/ajax/deletePlaylist.php", { playlistId: playlistId }).done(function(error) {
            if(error != "") {
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}

//Funcion para cambio dinámico de páginas.
function openPage(url) {
    //Esta declaracion resetea los 2000ms asignados en la función de la pag de busqueda!!!
    if(timer != null) {
        clearTimeout(timer);
    }

    if(url.indexOf("?") == -1) {
        url = url + "?";
    }
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $('#mainContent').load(encodedUrl);
    //'scrollTop' al cargarse una nueva pagina
    $('body').scrollTop(0);
    history.pushState(null, null, url);
}


function formatTime(seconds) {
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60); //Math.floor para redondear a la baja
    var seconds = time - minutes * 60;

    var extraZero;
    if(seconds < 10) {
        extraZero = "0";
    } else {
        extraZero = "";
    }

    //Otra forma de hacer el if seria: var extraZero = (seconds < 10) ? "0" : "";
    return minutes + ":" + extraZero + seconds;
}

function updateTimeProgressBar(audio) {
    $(".progressTime.current").text(formatTime(audio.currentTime));
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

    var progress = audio.currentTime / audio.duration * 100;
    $(".playbackBar .progress").css("width", progress + "%");
}

function updateVolumeBar(audio) {
    var volume = audio.volume * 100;
    $(".volumeBar .progress").css("width", volume + "%");
}

function playFirstSong() {
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

//Creamos una clase/objeto Audio.
function Audio() {
    //"this.currentlyPlaying" y "this.audio" son propiedades del objeto.
    //"createElement(audio)" lo que hace es crear el objeto Audio.
    this.currentlyPlaying;
    this.audio = document.createElement('audio');

    //Lanza la siguiente canción al llegar al final
    this.audio.addEventListener("ended", function() {
        nextSong();
    })

    this.audio.addEventListener("canplay", function() {
        //'this' dentro de la función, hace referencia al objeto ('audio') sobre el que ha sido llamado el evento
        var duration = formatTime(this.duration);
        $(".progressTime.remaining").text(duration);
    });

    //Evento para mostrar el progreso de la canción tanto en tiempo como en la barra de progreso
    this.audio.addEventListener("timeupdate", function() {
        if(this.duration) {
            updateTimeProgressBar(this);
        }
    });

    this.audio.addEventListener("volumechange", function() {
        updateVolumeBar(this);
    })

    //Creamos la funcion "setTrack" con un parámetro(src), que seria la ubicación del archivo de audio. El parametro src luego será
    //cambiado por track, que es el objeto json que usaremos hacer el update del campo plays ya que este contiene todos los datos de la canción.
    this.setTrack = function(track) {
        //La variable currentlyPlaying se actualizará con cada canción que se esté reproducioendo.
        this.currentlyPlaying = track;
        //"this.audio.src": src es una propiedad de la clase audio. A esto le estamos asignando el valor "src" que es la ubicacion del archivo de audio.
        //El valor asignado ya no es "src". Lo cambiamos por "track.path"
        this.audio.src = track.path;
    }

    this.play = function() {
        this.audio.play();
    }

    this.pause = function() {
        this.audio.pause();
    }

    this.setTime = function(seconds) {
        this.audio.currentTime = seconds;
    }
}
