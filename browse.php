<?php include 'includes/includedFiles.php'; ?>

<h1 class="bigTitleHeading">You Might Like This</h1>

<div class="gridContainer">
    <?php
        $gridQuery = mysqli_query($conn, "SELECT * FROM albums ORDER BY RAND() LIMIT 10");

        while($row = mysqli_fetch_array($gridQuery)) {
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
