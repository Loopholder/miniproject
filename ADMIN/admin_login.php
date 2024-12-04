<?php
include ("../DATABASE/database.php");

session_start();

if(isset($_POST['login'])) {
    $admin_username = $_POST['admin_username'];
    $admin_password = $_POST['admin_password'];

    $query = "SELECT * FROM TBL_ADMIN WHERE admin_username='$admin_username' AND admin_password='$admin_password'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 1) {
        $_SESSION['admin_username'] = $admin_username;
        echo "Login successful!";
        // Redirect to admin dashboard or homepage
        header('Location: admin_dashboard.php');
        exit;
    } else {
        echo "<script>alert('Invalid username or password.');</script>";
    }
}
?>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: white;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: black;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <form method="POST">
        <h2>Admin Login</h2>
        <input type="text" name="admin_username" placeholder="Admin Username" required>
        <input type="password" name="admin_password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>

</body>
</html>
