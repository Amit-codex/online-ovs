<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="./css/admin_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Admin Dashboard</h1>
        <p>Welcome, Admin!</p>
        <nav class="dashboard-nav">
            <a href="add_voters.html" class="button">Add Voters</a>
            <a href="add_candidates.html" class="button">Add Candidates</a>
            <a href="update_candidate.php" class="button">Update Candidate</a>
            <a href="update_voter.php" class="button">Update Voter</a>
            <a href="vote_result.php" class="button">Vote Result</a>
            <a href="candidate_details.php" class="button">Candidates Details</a>
        </nav>
    </div>
</body>
</html>
