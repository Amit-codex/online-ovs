<?php
// Include the database connection file
include 'connection.php';

// Retrieve and sanitize input
$aadhar_id = $conn->real_escape_string($_POST['aadhar_id']);
$name = $conn->real_escape_string($_POST['name']);
$email = $conn->real_escape_string($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate passwords
if ($password !== $confirm_password) {
    die("Passwords do not match.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Prepare and execute SQL statement
$sql = "INSERT INTO users (Aadhar_Id, V_name, Email_Id, Password) VALUES ('$aadhar_id', '$name', '$email', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    // Registration successful, redirect to login page
    header("Location: login.html");
    exit(); // Make sure to exit after redirection
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
