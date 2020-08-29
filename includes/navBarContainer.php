<div id="navBarContainer">
    <nav class="navBar">
        <!--Los anchor tags '<a>' han sido reemplazados por <span> para ser usados como links!!!-->
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
            <img src="assets/images/icons/icon-logo.png" alt="Logo">
        </span>

        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('search.php')" class="navItemLink">Search</span>
                <img src="assets/images/icons/icons-search.png" class="iconSearch" alt="Search">
            </div>
        </div>

        <div class="group">
            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('browse.php')" class="navItemLink">Browser</span>
            </div>

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItemLink">Your Music</span>
            </div>

            <div class="navItem">
                <span role="link" tabindex="0" onclick="openPage('userSettings.php')" class="navItemLink"><?= $userLoggedIn->getFirstAndLastName(); ?></span>
            </div>
        </div>

    </nav>
</div>
