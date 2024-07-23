<?php
// Include the database connection file
include 'connection.php';

try {
    // Retrieve and sanitize input
    $candidate_id = $conn->real_escape_string($_POST['candidate_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $party = $conn->real_escape_string($_POST['party']);
    $block = $conn->real_escape_string($_POST['block']);

    // Handle file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['picture']['tmp_name'];
        $fileName = $_FILES['picture']['name'];
        $fileSize = $_FILES['picture']['size'];
        $fileType = $_FILES['picture']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = $candidate_id . '.' . $fileExtension;

        $uploadFileDir = 'uploads/';
        $dest_path = $uploadFileDir . $newFileName;

        // Check if the uploads directory exists and create it if it doesn't
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $picture_path = $conn->real_escape_string($dest_path);

            // Prepare and execute SQL statement
            $sql = "INSERT INTO candidates (Candidate_Id, C_name, Party, Block_num, Picture_Path) VALUES ('$candidate_id', '$name', '$party', '$block', '$picture_path')";
            if ($conn->query($sql) === TRUE) {
                echo "Candidate added successfully!";
                // Redirect to a success page or admin dashboard after successful addition
                header("Location: admin_dashboard.php");
                exit();
            } else {
                echo "Error: " . $conn->error;
            }
        } else {
            echo 'There was an error moving the uploaded file.';
        }
    } else {
        echo 'Error uploading file.';
    }
} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) {
        // Unique constraint violation
        echo "Error: Candidate ID or Name already exists.";
    } else {
        // Other SQL errors
        echo "Error: " . $e->getMessage();
    }
}

// Close connection
$conn->close();
?>
