<?php
include 'includes/includedFiles.php';
?>


<div class="entityInfo">
    <div class="centerSection">

        <div class="userInfo">
            <h1><?= $userLoggedIn->getFirstAndLastName(); ?></h1>
        </div>

        <div class="buttonItems">
            <button class="button" onclick="openPage('userUpdate.php')">USER DETAILS</button>
            <button class="button" onclick="logout()">LOG OUT</button>
        </div>

    </div>
</div>
