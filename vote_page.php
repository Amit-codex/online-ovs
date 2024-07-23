<?php
session_start();

// Check if the voter ID is set in the session
if (!isset($_SESSION['voter_id'])) {
    header("Location: verify_voter_id.php");
    exit();
}

include 'connection.php';

$error = '';
$message = '';

// Handle the vote submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate_id = $conn->real_escape_string($_POST['candidate']);
    $voter_id = $_SESSION['voter_id'];

    // Check if the voter has already voted
    $sql = "SELECT * FROM votes WHERE Voter_Id='$voter_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $error = "You have already voted.";
    } else {
        // Fetch candidate details
        $sql = "SELECT C_name, Party, Block_num FROM candidates WHERE Candidate_Id='$candidate_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $candidate = $result->fetch_assoc();
            $candidate_name = $conn->real_escape_string($candidate['C_name']);
            $candidate_party = $conn->real_escape_string($candidate['Party']);
            $candidate_block = $conn->real_escape_string($candidate['Block_num']);

            // Record the vote
            $sql = "INSERT INTO votes (Candidate_Id, Candidate_Name, Candidate_Party, Candidate_Block, Vote_Count, Voter_Id) VALUES ('$candidate_id', '$candidate_name', '$candidate_party', '$candidate_block', 1, '$voter_id')
                    ON DUPLICATE KEY UPDATE Vote_Count = Vote_Count + 1";
            if ($conn->query($sql) === TRUE) {
                $message = "Your vote has been cast successfully!";
                // Optionally, clear the session data to prevent re-voting
                unset($_SESSION['voter_id']);
            } else {
                $error = "Error casting your vote: " . $conn->error;
            }
        } else {
            $error = "Candidate not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote</title>
    <link rel="stylesheet" href="./css/vote_page.css">
    
</head>
<body>
<div class="container">
        <h2>Cast Your Vote</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form action="vote_page.php" method="POST">
            <h3>Select Candidate you want to vote:</h3>
            <?php
            // Fetch candidates from the database
            $sql = "SELECT * FROM candidates";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div class='candidate'>";
                    echo "<input type='radio' id='candidate" . htmlspecialchars($row['Candidate_Id']) . "' name='candidate' value='" . htmlspecialchars($row['Candidate_Id']) . "' required>";
                    echo "<label for='candidate" . htmlspecialchars($row['Candidate_Id']) . "'>";
                    echo "<strong>" . htmlspecialchars($row['C_name']) . "</strong> (" . htmlspecialchars($row['Party']) . ")<br>";
                    echo "Block: " . htmlspecialchars($row['Block_num']) . "<br>";
                    echo "</label>";
                    echo "</div>";
                }
            } else {
                echo "<p>No candidates available.</p>";
            }
            ?>
            <input type="submit" value="Submit Vote">
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>
