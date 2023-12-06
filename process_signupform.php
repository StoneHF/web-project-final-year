<?php
// Include the database connection file
session_start();
include 'config/conn.php';

// Retrieve the input data from the form
$varEmail = $_POST['email'];
$varPassword = md5($_POST['password']);
$varUsername = $_POST['username'];
$varFirstname = $_POST['fname'];
$varLastname = $_POST['lname'];
$varMobile = $_POST['mobile'];

// Use prepared statements and parameterized queries to prevent SQL injection attacks
$stmt = $mysqli->prepare("SELECT * FROM `tbl_members` WHERE `fldEmail` = ? AND `fldPassword` = ?");
$stmt->bind_param("ss", $varEmail, $varPassword);
$stmt->execute();
$result = $stmt->get_result();
$row_count = $result->num_rows;

// Check if the email is already registered
if ($row_count >= 1) {
    // If the email is already registered, show an error message
    echo "Email already registered";
} else {
    // Check if the username is already registered
    $stmt = $mysqli->prepare("SELECT * FROM `tbl_members` WHERE `fldUsername` = ?");
    $stmt->bind_param("s", $varUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_count = $result->num_rows;

    if ($row_count >= 1) {
        // If the username is already registered, show an error message
        echo "Username already registered";
    } else {
        // If the email and username are not registered, insert the new member into the database
        $stmt = $mysqli->prepare("INSERT INTO `tbl_members`(`fldEmail`, `fldPassword`, `fldUsername`, `fldFirstname`, `fldLastname`, `fldMobile`) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $varEmail, $varPassword, $varUsername, $varFirstname, $varLastname, $varMobile);
        $stmt->execute();

        // Show a success message
        echo "Sign up successful";
        // Redirect the user to the login form
        header('Location: login_form.php');
        exit();
    }
}
// Close the database connection
$mysqli->close();
?>