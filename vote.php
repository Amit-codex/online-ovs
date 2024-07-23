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

$sql = "SELECT N_Id, N_name, Party FROM nominees";
$result = $conn->query($sql);

$options = "";

while ($row = $result->fetch_assoc()) {
    $options .= "<option value='" . $row['N_Id'] . "'>" . $row['N_name'] . " (" . $row['Party'] . ")</option>";
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vote</title>
</head>
<body>
    <h2>Cast Your Vote</h2>
    <form action="cast_vote.php" method="POST">
        <select name="nominee_id" required>
            <?php echo $options; ?>
        </select>
        <input type="submit" value="Vote">
    </form>
</body>
</html>


