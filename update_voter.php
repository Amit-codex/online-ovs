<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.html");
    exit();
}

include 'connection.php';

$voter = null;
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search_voter_id'])) {
        $search_voter_id = $conn->real_escape_string($_POST['search_voter_id']);
        $sql = "SELECT * FROM voters WHERE Voter_Id='$search_voter_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $voter = $result->fetch_assoc();
        } else {
            $error = "Voter not found.";
        }
    } elseif (isset($_POST['voter_id'])) {
        $voter_id = $conn->real_escape_string($_POST['voter_id']);
        $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : '';
        $age = isset($_POST['age']) ? $conn->real_escape_string($_POST['age']) : '';
        $sex = isset($_POST['sex']) ? $conn->real_escape_string($_POST['sex']) : '';
        $email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
        $phone_number = isset($_POST['phone_number']) ? $conn->real_escape_string($_POST['phone_number']) : '';

        if ($name === '' || $age === '' || $sex === '' || $email === '' || $phone_number === '') {
            $error = "Please fill out all fields.";
        } else {
            $sql = "UPDATE voters SET V_name='$name', Age='$age', Sex='$sex', Email_Id='$email', Phone_number='$phone_number' WHERE Voter_Id='$voter_id'";

            if ($conn->query($sql) === TRUE) {
                $message = "Voter updated successfully!";
                $voter = null;
            } else {
                $error = "Error updating voter: " . $conn->error;
            }
        }
    } elseif (isset($_POST['delete_voter_id'])) {
        $delete_voter_id = $conn->real_escape_string($_POST['delete_voter_id']);
        $sql = "DELETE FROM voters WHERE Voter_Id='$delete_voter_id'";

        if ($conn->query($sql) === TRUE) {
            $message = "Voter deleted successfully!";
            $voter = null;
        } else {
            $error = "Error deleting voter: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Voter</title>
    <link rel="stylesheet" href="./css/update_voter.css">
</head>
<body>
    <div class="container">
        <h2>Update Voter</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form action="update_voter.php" method="POST" class="search-form">
            <label for="search_voter_id">Voter ID:</label>
            <input type="text" id="search_voter_id" name="search_voter_id" required>
            <input type="submit" value="Search">
        </form>

        <?php if ($voter): ?>
            <h3>Update Voter Details</h3>
            <form action="update_voter.php" method="POST" class="update-form">
                <input type="hidden" name="voter_id" value="<?php echo htmlspecialchars($voter['Voter_Id']); ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($voter['V_name']); ?>" required><br>
                <label for="age">Age:</label>
                <input type="text" id="age" name="age" value="<?php echo htmlspecialchars($voter['Age']); ?>" required><br>
                <label for="sex">Sex:</label>
                <input type="text" id="sex" name="sex" value="<?php echo htmlspecialchars($voter['Sex']); ?>" required><br>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($voter['Email_Id']); ?>" required><br>
                <label for="phone_number">Phone Number:</label>
                <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($voter['Phone_Number']); ?>" required><br>
                <input type="submit" value="Update Voter">
            </form>

            <h3>Delete Voter</h3>
            <form action="update_voter.php" method="POST" class="delete-form">
                <input type="hidden" name="delete_voter_id" value="<?php echo htmlspecialchars($voter['Voter_Id']); ?>">
                <input type="submit" value="REMOVE THE VOTER">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
