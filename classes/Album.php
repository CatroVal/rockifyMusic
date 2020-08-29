<?php

class Album {
    private $conn;
    private $id;
    private $title;
    private $artist_id;
    private $genre_id;
    private $artworkPath;

    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;

        $albumQuery = mysqli_query($this->conn, "SELECT * FROM albums WHERE id='$this->id'");
        $album = mysqli_fetch_array($albumQuery);

        $this->title = $album['title'];
        $this->artist_id = $album['artist_id'];
        $this->genre_id = $album['genre_id'];
        $this->artworkPath = $album['artworkPath'];

    }


    public function getTitle() {
        return $this->title;
    }

    public function getArtist() {
        return new Artist($this->conn, $this->artist_id);
    }

    public function getGenreId() {
        return $this->genre_id;
    }

    public function getArtworkPath() {
        return $this->artworkPath;
    }

    public function getNumberOfSongs() {
        $query = mysqli_query($this->conn, "SELECT id FROM songs WHERE album_id='$this->id'");
        $songs = mysqli_num_rows($query);

        return $songs;
    }

    public function getSongsId() {
        $query = mysqli_query($this->conn, "SELECT id FROM songs WHERE album_id='$this->id' ORDER BY albumOrder ASC");
        $array = array();

        while($row = mysqli_fetch_array($query)) {
            array_push($array, $row['id']);
        }

        return $array;

    }
}

?>
