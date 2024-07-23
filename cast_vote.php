<?php
session_start();

if (!isset($_SESSION['voter_id'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "amit1aadya";
$dbname = "online_voting";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$voter_id = $_SESSION['voter_id'];
$nominee_id = $_POST['nominee_id'];

$sql = "INSERT INTO votes (Voter_Id, Nominee_Id) VALUES ('$voter_id', '$nominee_id')";
$update_sql = "UPDATE voters SET Voted=1 WHERE Voter_Id='$voter_id'";

if ($conn->query($sql) === TRUE && $conn->query($update_sql) === TRUE) {
    echo "Vote cast successfully!";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
