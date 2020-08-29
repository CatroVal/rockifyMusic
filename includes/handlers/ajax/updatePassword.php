<?php
include '../../config.php';

if(!isset($_POST['username'])) {
    echo "ERROR: Could not set username";
    exit();
}

if(!isset($_POST['oldPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])) {
    echo "ERROR: Not all passwords have been set";
    exit();
}

if($_POST['oldPassword'] == "" || $_POST['newPassword1'] == "" || $_POST['newPassword2'] == "") {
    echo "All fields must be filled";
    exit();
}

$username = $_POST['username'];
$oldPassword = $_POST['oldPassword'];
$newPassword1 = $_POST['newPassword1'];
$newPassword2 = $_POST['newPassword2'];

$oldMd5 = md5($oldPassword);

$passwordCheck = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND password='$oldMd5'");
if(mysqli_num_rows($passwordCheck) != 1) {
    echo "Incorrect password";
    exit();
}

if($newPassword1 != $newPassword2) {
    echo "Your new passwords do not match";
    exit();
}

if(preg_match("/[^A-Za-z0-9]/", $newPassword1)) {
    echo "Your password can only contain letters and/or numbers";
    exit();
}

if(strlen($newPassword1) < 6 || strlen($newPassword1) > 20) {
    echo "Your password must contain between 6 and 20 characters";
    exit();
}

$newMd5 = md5($newPassword1);
$query = mysqli_query($conn, "UPDATE users SET password='$newMd5' WHERE username='$username'");
echo "Password updated successfully";

?>
