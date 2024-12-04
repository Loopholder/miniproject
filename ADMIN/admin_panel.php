<?php

include('../DATABASE/database.php');
session_start();
 //Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../USER/user_login.php"); // Redirect to login page if not logged in
    exit();
}

// Fetch admin details
$admin_id = $_SESSION['admin_id'];
$query = "SELECT admin_username FROM TBL_ADMIN WHERE admin_id = '$admin_id'";
$result = mysqli_query($conn, $query);
$admin = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
        }
        .dashboard-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px;
        }
        .header {
            width: 100%;
            background-color: #333;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
        }
        .dashboard-menu {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .dashboard-menu a {
            text-decoration: none;
            padding: 15px 30px;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 18px;
            transition: background-color 0.3s ease;
        }
        .dashboard-menu a:hover {
            background-color: #0056b3;
        }
        .admin-info {
            margin-top: 20px;
            font-size: 16px;
        }
        footer {
            margin-top: 40px;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Welcome, <?php echo $admin['admin_username']; ?> BOSS</h1>
        </div>

        <div class="dashboard-menu">
            <a href="upload_image.php">Upload Image</a>
            <a href="view_reviews.php">View User Reviews</a>
            <a href="change_password.php">Change Password</a>
            <a href="logout.php">Logout</a>
        </div>

        <div class="admin-info">
            <p>Admin Dashboard for managing content and reviewing user feedback.</p>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Ferro Faab. All rights reserved.</p>
    </footer>
</body>
</html>
