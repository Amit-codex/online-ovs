<?php
// Include the database connection file
include 'connection.php';

// Retrieve and sanitize input
$aadhar_id = $conn->real_escape_string($_POST['aadhar_id']);
$voter_id = $conn->real_escape_string($_POST['voter_id']);
$name = $conn->real_escape_string($_POST['name']);
$age = $conn->real_escape_string($_POST['age']);
$sex = $conn->real_escape_string($_POST['sex']);
$email = $conn->real_escape_string($_POST['email']);
$phone_number = $conn->real_escape_string($_POST['phone_number']);

// Prepare and execute SQL statement
$sql = "INSERT INTO voters (Aadhar_Id, Voter_Id, V_name, Age, Sex, Email_Id, Phone_Number) VALUES ('$aadhar_id', '$voter_id', '$name', '$age', '$sex', '$email', '$phone_number')";

if ($conn->query($sql) === TRUE) {
    echo "Voter added successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close connection
$conn->close();
?>
