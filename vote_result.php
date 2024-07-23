<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.html");
    exit();
}

include 'connection.php'; // Ensure this file contains your database connection code

// Fetch vote results with aggregated vote count
$sql = "SELECT Candidate_Name, Candidate_Party, Candidate_Block, SUM(Vote_Count) as Total_Votes 
        FROM votes 
        GROUP BY Candidate_Name, Candidate_Party, Candidate_Block";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vote Results</title>
    <link rel="stylesheet" href="./css/vote_results.css">
</head>
<body>
    <div class="container">
        <h1>Vote Results</h1>
        <table class="results-table">
            <thead>
                <tr>
                    <th>Candidate Name</th>
                    <th>Party</th>
                    <th>Block</th>
                    <th>Vote Count</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['Candidate_Name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Candidate_Party']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Candidate_Block']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Total_Votes']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No votes recorded yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
