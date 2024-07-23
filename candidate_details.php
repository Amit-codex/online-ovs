<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: linear-gradient(to right, #000000, #064169);
            color:white;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid #ddd;
        
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #65b2e5;
            color:black;
        }
        .search-bar {
            margin-bottom: 20px;
        }
        .search-bar input[type="text"] {
            padding: 10px;
            width: 200px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .search-bar input[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-bar input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Candidate Details</h1>
    <div class="search-bar">
        <form action="candidate_details.php" method="GET">
            <label for="name">Search by Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter name">
            <label for="party">Search by Party:</label>
            <input type="text" id="party" name="party" placeholder="Enter party">
            <input type="submit" value="Search">
        </form>
    </div>

    <?php
    include 'connection.php';

    // Retrieve search parameters
    $name = isset($_GET['name']) ? $conn->real_escape_string($_GET['name']) : '';
    $party = isset($_GET['party']) ? $conn->real_escape_string($_GET['party']) : '';

    // Build the SQL query
    $sql = "SELECT * FROM candidates WHERE 1=1";
    if ($name !== '') {
        $sql .= " AND C_name LIKE '%$name%'";
    }
    if ($party !== '') {
        $sql .= " AND Party LIKE '%$party%'";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Candidate ID</th>
                    <th>Name</th>
                    <th>Party</th>
                    <th>Block</th>
                    <th>Picture</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Candidate_Id']); ?></td>
                        <td><?php echo htmlspecialchars($row['C_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['Party']); ?></td>
                        <td><?php echo htmlspecialchars($row['Block_num']); ?></td>
                        <td>
                            <?php if (!empty($row['Picture_Path'])): ?>
                                <img src="<?php echo htmlspecialchars($row['Picture_Path']); ?>" alt="Candidate Picture" width="100">
                            <?php else: ?>
                                No picture available
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No candidates found.</p>
    <?php endif; ?>

    <?php
    $conn->close();
    ?>
</body>
</html>
