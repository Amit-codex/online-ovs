<?php
// Include the database connection file
include 'connection.php';

// Retrieve and sanitize input
$aadhar_id = $conn->real_escape_string($_POST['aadhar_id']);
$password = $_POST['password'];

// Prepare and execute SQL statement
$sql = "SELECT Password FROM users WHERE Aadhar_Id='$aadhar_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the hashed password from the database
    $row = $result->fetch_assoc();
    $hashed_password = $row['Password'];

    // Verify the provided password with the hashed password
    if (password_verify($password, $hashed_password)) {
        // Start session and set session variables
        session_start();
        $_SESSION['aadhar_id'] = $aadhar_id; // Store the Aadhar ID in the session

        // Redirect to a new page
        header("Location: voters_dashboard.html");
        exit(); // Make sure to call exit() after header() to stop further script execution
    } else {
        echo "Invalid Aadhar ID or password.";
    }
} else {
    echo "No account found with this Aadhar ID.";
}

// Close connection
$conn->close();
?>
