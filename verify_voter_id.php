<?php
session_start();
include 'connection.php';

$error = '';
$redirect = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $voter_id = $conn->real_escape_string($_POST['voter_id']);

    // Query to check if the voter ID exists
    $sql = "SELECT * FROM voters WHERE Voter_Id='$voter_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Voter ID exists, redirect to the voting page
        $_SESSION['voter_id'] = $voter_id; // Store voter ID in session
        header("Location: vote_page.php"); // Redirect to voting page
        exit();
    } else {
        // Voter ID does not exist
        $error = "Voter ID not found. Please check and try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Voter ID</title>
    <link rel="stylesheet" href="./css/verify_voter_id.css">
</head>
<body>
<div class="container">
        <h2>Verify Voter ID</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form action="verify_voter_id.php" method="POST">
            <label for="voter_id">Voter ID Number:</label>
            <input type="text" id="voter_id" name="voter_id" required>
            <input type="submit" value="Verify">
        </form>
    </div>
</body>
</html>
