<?php
include '../../config.php';

if(isset($_POST['songId'])) {
    $songId = $_POST['songId'];

    $query = mysqli_query($conn, "SELECT * FROM songs WHERE id='$songId'");

    $resultArray = mysqli_fetch_array($query);
    //Convertimos el array en un objeto JSON para poder usarlo luego en JS.
    echo json_encode($resultArray);
}
?>
