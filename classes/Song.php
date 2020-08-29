<?php

class Song {
    private $conn;
    private $id;
    private $title;
    private $artist_id;
    private $album_id;
    private $genre_id;
    private $duration;
    private $path;
    private $mysqliData;

    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;

        $query = mysqli_query($this->conn, "SELECT * FROM songs WHERE id='$this->id'");
        $this->mysqliData = mysqli_fetch_array($query);
        $this->title = $this->mysqliData['title'];
        $this->artist_id = $this->mysqliData['artist_id'];
        $this->album_id = $this->mysqliData['album_id'];
        $this->genre_id = $this->mysqliData['genre_id'];
        $this->duration = $this->mysqliData['duration'];
        $this->path = $this->mysqliData['path'];

    }

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getArtist() {
        return new Artist($this->conn, $this->artist_id);
    }

    public function getAlbum() {
        return new Album($this->conn, $this->album_id);
    }

    public function getGenreId() {
        return $this->genre_id;
    }

    public function getDuration() {
        return $this->duration;
    }

    public function getPath() {
        return $this->path;
    }

    public function getMysqliData() {
        return $this->mysqliData;
    }

}

?>
