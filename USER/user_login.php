<?php
include('../DATABASE/database.php'); // Include your database connection file

session_start(); // Start the session to store user login state

$message = '';

if (isset($_POST['login'])) {
    $username = $_POST['username']; // Adjust the form name to match
    $password = $_POST['password'];

    // First, check in the admin table
    $admin_query = "SELECT * FROM tbl_admin WHERE admin_username = ?";
    $stmt = $conn->prepare($admin_query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $admin_result = $stmt->get_result();

    if ($admin_result->num_rows > 0) {
        // Admin found, check password
        $admin = $admin_result->fetch_assoc();
        if ($password == $admin['admin_password']) {  // Plain text password comparison for admin
            $_SESSION['admin_username'] = $admin['admin_username'];
            // Redirect to admin panel
            header('Location: ../ADMIN/admin_panel.php');
            exit;
        } else {
            $message = "Invalid username or password for admin.";
        }
    } else {
        // If no admin is found, check the user table
        $user_query = "SELECT * FROM tbl_user WHERE user_username = ?";
        $stmt = $conn->prepare($user_query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $user_result = $stmt->get_result();

        if ($user_result->num_rows > 0) {
            // User found, check password (assuming password is hashed for users)
            $user = $user_result->fetch_assoc();
            if (password_verify($password, $user['user_password'])) {
                $_SESSION['user_username'] = $user['user_username'];
                // Redirect to user index page
                header('Location: ../MAIN_PAGES/index.php');
                exit;
            } else {
                $message = "Invalid username or password for user.";
            }
        } else {
            $message = "Invalid username or password.";
        }
    }

    $stmt->close();
}

$conn->close();
?>

<html>
<head>
    <title>User/Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #0f2027, #203a43, #2c5364);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: black;
        }
        form {
            background-color: #a8f0e6;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            width: 300px;
            backdrop-filter: blur(10px);
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 30px;
            background-color: rgba(255, 255, 255, 0.2);
            color: black;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: black;
        }
        ::placeholder {
            color:#335b3d;
        }
        .popup-message {
            display: none;
            position: fixed;
            bottom: -100px;
            left: 50%;
            transform: translateX(-50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 15px 30px;
            border-radius: 30px;
            font-size: 16px;
            z-index: 1000;
            transition: all 0.5s ease;
        }
        .show-popup {
            display: block;
            bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="popup-message" id="popupMessage"><?php echo $message; ?></div>
    <form method="POST" action="">
        <h2>Login</h2>
        <input type="text" name="username" autocomplete="off" placeholder="Username" required>
        <input type="password" name="password" autocomplete="off" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>

    <script>
        window.onload = function() {
            var message = "<?php echo $message; ?>";
            if (message) {
                var popup = document.getElementById('popupMessage');
                popup.classList.add('show-popup');
                setTimeout(function() {
                    popup.classList.remove('show-popup');
                }, 3000); // Hide the message after 3 seconds
            }
        }
    </script>
</body>
</html>
