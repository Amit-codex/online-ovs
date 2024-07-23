<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin.html");
    exit();
}

include 'connection.php';

$candidate = null;
$error = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['search_candidate_id'])) {
        $search_candidate_id = $conn->real_escape_string($_POST['search_candidate_id']);
        $sql = "SELECT * FROM candidates WHERE Candidate_Id='$search_candidate_id'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $candidate = $result->fetch_assoc();
        } else {
            $error = "Candidate not found.";
        }
    } elseif (isset($_POST['candidate_id'])) {
        if (isset($_POST['delete'])) {
            $candidate_id = $conn->real_escape_string($_POST['candidate_id']);
            $sql = "DELETE FROM candidates WHERE Candidate_Id='$candidate_id'";
            if ($conn->query($sql) === TRUE) {
                $message = "Candidate deleted successfully!";
                $candidate = null;
            } else {
                $error = "Error deleting candidate: " . $conn->error;
            }
        } else {
            $candidate_id = $conn->real_escape_string($_POST['candidate_id']);
            $name = $conn->real_escape_string($_POST['name']);
            $party = $conn->real_escape_string($_POST['party']);
            $block = $conn->real_escape_string($_POST['block']);

            $sql = "UPDATE candidates SET C_name='$name', Party='$party', Block_num='$block' WHERE Candidate_Id='$candidate_id'";

            if ($conn->query($sql) === TRUE) {
                $message = "Candidate updated successfully!";
            } else {
                $error = "Error updating candidate: " . $conn->error;
            }

            if (isset($_FILES['picture']) && $_FILES['picture']['error'] == UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename($_FILES["picture"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                $valid_extensions = array("jpg", "jpeg", "png", "gif");
                if (!in_array($imageFileType, $valid_extensions)) {
                    $error = "Only JPG, JPEG, PNG & GIF files are allowed.";
                } else {
                    if (move_uploaded_file($_FILES["picture"]["tmp_name"], $target_file)) {
                        $picture_path = $conn->real_escape_string($target_file);
                        $sql = "UPDATE candidates SET Picture_Path='$picture_path' WHERE Candidate_Id='$candidate_id'";
                        if ($conn->query($sql) !== TRUE) {
                            $error = "Error updating picture: " . $conn->error;
                        } else {
                            $message = "Candidate and picture updated successfully!";
                        }
                    } else {
                        $error = "There was an error moving the uploaded file.";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Candidate</title>
    <link rel="stylesheet" href="./css/update_candidate.css">
</head>
<body>
    <div class="container">
        <h2>Update Candidate</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if ($message) echo "<p class='message'>$message</p>"; ?>

        <form action="update_candidate.php" method="POST" class="search-form">
            <label for="search_candidate_id">Candidate ID:</label>
            <input type="text" id="search_candidate_id" name="search_candidate_id" required>
            <input type="submit" value="Search">
        </form>

        <?php if ($candidate): ?>
            <h3>Update Candidate Details</h3>
            <form action="update_candidate.php" method="POST" enctype="multipart/form-data" class="update-form">
                <input type="hidden" name="candidate_id" value="<?php echo htmlspecialchars($candidate['Candidate_Id']); ?>">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($candidate['C_name']); ?>" required><br>
                <label for="party">Party:</label>
                <input type="text" id="party" name="party" value="<?php echo htmlspecialchars($candidate['Party']); ?>" required><br>
                <label for="block">Block:</label>
                <input type="text" id="block" name="block" value="<?php echo htmlspecialchars($candidate['Block_num']); ?>" required><br>
                <label for="picture">Picture:</label>
                <input type="file" id="picture" name="picture"><br>
                <input type="submit" value="Update Candidate">
            </form>

            <h3>Delete Candidate</h3>
            <form action="update_candidate.php" method="POST" class="delete-form" onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                <input type="hidden" name="candidate_id" value="<?php echo htmlspecialchars($candidate['Candidate_Id']); ?>">
                <input type="submit" name="delete" value="Delete Candidate">
            </form>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
$conn->close();
?>
