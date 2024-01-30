<?php
session_start(); // start session for user authentication
include 'config/conn.php'; // include database connection file

$target_dir = "GamesReleases/"; // specify the directory where images will be stored
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]); // set the target file path
$uploadOK = 1; // variable to check if the file can be uploaded
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION)); // get the file extension
$imageTitle = $_POST['title']; // get the image title from form data
$xboxone = isset($_POST['xboxone']) ? $_POST['xboxone'] : 0; // check if Xbox One checkbox is checked
$xboxsx = isset($_POST['xboxsx']) ? $_POST['xboxsx'] : 0; // check if Xbox Series X checkbox is checked
$playstation4 = isset($_POST['playstation4']) ? $_POST['playstation4'] : 0; // check if PlayStation 4 checkbox is checked
$playstation5 = isset($_POST['playstation5']) ? $_POST['playstation5'] : 0; // check if PlayStation 5 checkbox is checked
$pc = isset($_POST['pc']) ? $_POST['pc'] : 0; // check if PC checkbox is checked
$switch = isset($_POST['switch']) ? $_POST['switch'] : 0; // check if Switch checkbox is checked
$bio = $_POST['bio']; // get the game bio from form data
$releaseDate = $_POST['releaseDate']; // get the release date from form data


if ($_FILES["fileToUpload"]["size"] >= 5000000) // check if the file size is greater than 5MB
{
    echo "Sorry, the file is too large.";
    $uploadOK = 0;
}

if ($uploadOK == 0) // if the file cannot be uploaded, show an error message
{
    echo "Sorry, the file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) // if the file can be uploaded, move it to the target directory
    {
        $sql = "INSERT INTO `tbl_game_release_img` (`fldImageID`, `fldImageTitle`, `fldPath`, `fldXboxOne`, `fldXboxSX`, `fldPlaystation4`, `fldPlaystation5`, `fldPC`, `fldSwitch`, `fldBio`, `fldReleaseDate`)  
        VALUES (NULL, '$imageTitle', '$target_file', '$xboxone', '$xboxsx', '$playstation4', '$playstation5', '$pc', '$switch', '$bio', '$releaseDate')"; // create an SQL query to insert the image details into the database

        if ($mysqli->query($sql) === TRUE) { // execute the query and check if it was successful
            header('Location: admin_game_image_upload.php'); // redirect to the image upload form page
            echo "Image Uploaded successfully.";
        } 
    } else {
        echo "Sorry, there was an error uploading your file."; // show an error message if the file cannot be uploaded
    }
}
?>
