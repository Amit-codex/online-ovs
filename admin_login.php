<?php
session_start();
include 'connection.php';
$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT Admin_Id, Password FROM admins WHERE UserName='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $hashed_password = $row['Password'];

    // Verify the password
    if (password_verify($password, $hashed_password)) {
        $_SESSION['admin_id'] = $row['Admin_Id'];
        header("Location: admin_dashboard.php"); // Redirect to admin dashboard
    } else {
        echo "Invalid username or password!";
    }
} else {
    echo "Invalid username or password!";
}

$conn->close();
?>
