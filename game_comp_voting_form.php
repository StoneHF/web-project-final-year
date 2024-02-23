<?php
session_start();
include 'config/conn.php';
include 'adminCheck/navbarCheck.php';

// Redirect user to homepage if not logged in
if (!isset($_SESSION['fldMemberID'])) {
    header("Location: HomePage.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
<style>
    /* Add a container element for the gallery items */
    .gallery-container {
        display: flex; /* Use flexbox to arrange the items in a row */
        flex-wrap: wrap; /* Wrap items to the next row if necessary */
        justify-content: flex-start; /* Align items to the start of the container */
    }

    /* Style the individual gallery items */
    .gallery-item {
        margin: 10px; /* Add some space around the items */
        flex: 1 0 calc(20% - 20px); /* Maximum 5 items per row */
        background-color: #f1f1f1; /* Add a common background color */
        border: 1px solid #ccc; /* Add a border around each item */
        text-align: center; /* Center the text within the item */
    }

    /* Add a hover effect to the items */
    .gallery-item:hover {
        border: 1px solid #777; /* Change the border color on hover */
        cursor: pointer; /* Add a pointer cursor on hover */
    }

    /* Style the image within the item */
    .gallery-item img {
        max-width: 100%; /* Set maximum width to fit the container */
        height: auto; /* Keep the aspect ratio of the image */
    }

    /* Style the item description */
    .item-desc {
        padding: 15px; /* Add some padding to the description */
    }
</style>


</head>
<body>

<form method="post" action="game_comp_voting_form.php" id="competitionForm">
    <!-- dropdown menu for selecting a year -->
    <label for="year">Select a year:</label><br>
    <select name="year" id="year" onchange="this.form.submit()">
        <option value="all">All Years</option>
        <?php
        // select distinct years from the release dates of the images in the database
        $sql = "SELECT DISTINCT YEAR(fldReleaseDate) as year FROM tbl_comp_game_images ORDER BY year DESC";
        $result = $mysqli->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['year'] . "'>" . $row['year'] . "</option>";
        }
        ?>
    </select>
    <br>
    <!-- radio buttons for sorting images by release date -->
    <label>Sort by:</label><br>
    <input type="radio" id="asc" name="sort" value="asc" onchange="this.form.submit()">
    <label for="asc">Ascending</label>
    <input type="radio" id="desc" name="sort" value="desc" onchange="this.form.submit()">
    <label for="desc">Descending</label>
</form>

<form method="post" action="process_game_comp_voting.php">
    <div class="gallery-container">
        <?php
        // select image data from the database if the member hasn't voted on the game
        $yearFilter = isset($_POST['year']) && $_POST['year'] != "all" ? "AND YEAR(fldReleaseDate) = {$_POST['year']}" : "";
        $sortOrder = isset($_POST['sort']) && $_POST['sort'] == "asc" ? "ASC" : "DESC";
        $sql = "SELECT * FROM tbl_comp_game_images WHERE fldCompImageID NOT IN (
        SELECT fldCompImageID FROM tbl_game_comp_results WHERE fldMemberID = '{$_SESSION['fldMemberID']}'
      )
      {$yearFilter}
      ORDER BY fldReleaseDate {$sortOrder}";

        $result = $mysqli->query($sql);

        // check if there are any images to display
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // display the image data here
                ?>
                <div class="gallery-item">
                    <a target="_blank" href="<?php echo $row['fldPath']; ?>">
                        <img src="<?php echo $row['fldPath']; ?>" alt="<?php echo $row['fldImageTitle']; ?>">
                    </a>
                    <div class="item-desc"><?php echo $row['fldImageTitle']; ?></div>
                    <div class="item-desc"><?php echo $row['fldReleaseDate']; ?></div>

                    <!-- hidden field to store the image ID -->
                    <input type="hidden" name="CompImageID[]" value="<?php echo $row['fldCompImageID']; ?>">

                    <label for="points">Points:</label>
                    <select name="points[]">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="40">40</option>
                        <option value="50">50</option>
                        <option value="60">60</option>
                        <option value="70">70</option>
                        <option value="80">80</option>
                        <option value="90">90</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <?php
            }
        } else {
            // display message when no images are found
            echo "No images found.";
        }

        // close the database connection
        $mysqli->close();
        ?>
    </div>

    <!-- submit button for all images -->
    <input type="submit" name="submit" value="Submit">
</form>

</body>
</html>
