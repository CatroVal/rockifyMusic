<?php

class Artist {
    private $conn;
    private $id;

    public function __construct($conn, $id) {
        $this->conn = $conn;
        $this->id = $id;

    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        $artistQuery = mysqli_query($this->conn, "SELECT * FROM artists WHERE id='$this->id'");
        $artist = mysqli_fetch_array($artistQuery);

        return $artist['name'];
    }

    public function getSongsId() {
        $query = mysqli_query($this->conn, "SELECT * FROM songs WHERE artist_id='$this->id' ORDER BY plays DESC");
        $artistSongsArray = array();

        while($row = mysqli_fetch_array($query)) {
            array_push($artistSongsArray, $row['id']);
        }

        return $artistSongsArray;
    }
}
