<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Admin</title>
    <link rel="stylesheet" href="./css/add_admin.css">
</head>
<body>
    <div class="container">
        <h2>Add Admin</h2>
        <form action="add_admin.php" method="POST" class="add-form">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required><br>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Add Admin">
        </form>
    </div>
</body>
</html>


<!-- css-------------------------- -->



<!-- php------------------------- -->
<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before saving it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO admins (UserName, Password) VALUES ('$username', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Admin added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
