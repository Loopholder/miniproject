<?php
// Start session
session_start();

include ('../DATABASE/database.php');

if(isset($_POST['signup'])) {
    // Capture form data
    $name = $_POST['full_name'];
    $us_name = $_POST['user_username'];
    $email = $_POST['user_email'];
    $pass = password_hash($_POST['user_password'], PASSWORD_DEFAULT); // Hashing the password

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO TBL_USER (full_name, user_username, user_email, user_password) 
    VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $us_name, $email, $pass); // Binding parameters

    // Execute the query and check for errors
    if ($stmt->execute()) {
        // Store the user ID and username in the session
        $_SESSION['user_id'] = $stmt->insert_id;  // Capture the last inserted ID (user_id)
        $_SESSION['username'] = $us_name;

        // Redirect to the index page after successful signup
        header("Location: ../MAIN_PAGES/index.php");
        exit(); // Ensure script stops after redirect
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Sign Up</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: black;
            overflow: hidden;
        }
        .container {
            color: black;
            position: relative;
            width: 100%;
            max-width: 500px;
            padding: 0 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Ensure container takes the full viewport height */
        }
        .background .circle {
            position: absolute;
            border-radius: 50%;
            opacity: 0.2;
            filter: blur(100px);
        }
        .background .circle.purple.large {
            width: 600px;
            height: 600px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: linear-gradient(to bottom right, #9b59b6, #8e44ad);
        }
        .background .circle.blue.medium {
            width: 400px;
            height: 400px;
            top: 25%;
            left: 25%;
            transform: translate(-25%, -25%);
            background: linear-gradient(to bottom right, #2980b9, #2c3e50);
        }
        .background .circle.yellow.small {
            width: 300px;
            height: 300px;
            bottom: 25%;
            right: 25%;
            transform: translate(25%, 25%);
            background: linear-gradient(to bottom right, #f1c40f, #e67e22);
        }
        .form-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            height: 100%;
        }
        .form-container {
            background: #5034c2;
            backdrop-filter: blur(10px);
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
        }
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
        }
        .spinner {
            position: relative;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(to bottom right, #9b59b6, #8e44ad);
            animation: spin 6s linear infinite;
        }
        .spinner-inner {
            position: absolute;
            top: 2px;
            left: 2px;
            width: calc(100% - 4px);
            height: calc(100% - 4px);
            border-radius: 50%;
            background: white;
        }
        .spinner-core {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 32px;
            height: 32px;
            background: linear-gradient(to bottom right, #2980b9, #2c3e50);
            border-radius: 50%;
            transform: translate(-50%, -50%);
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        h1 {
            margin-top: 20px;
            font-size: 24px;
            font-weight: bold;
            font-family: Furore;
        }
        .signup-form {
            display: flex;
            flex-direction: column;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
        }
        input {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
            background-color: #f5f5f5;
        }
        .submit-btn {
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .submit-btn:hover {
            background-color: #0056b3;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 14px;
            color: black;
        }
        .login-link {
            font-size: 20px;
            color: #3aff00;
            text-decoration: underline;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="background">
            <div class="circle purple large"></div>
            <div class="circle blue medium"></div>
            <div class="circle yellow small"></div>
        </div>
        <div class="form-wrapper">
            <div class="form-container">
                <div class="header">
                    <div class="spinner">
                        <div class="spinner-inner"></div>
                        <div class="spinner-core"></div>
                    </div>
                    <h1>Sign Up</h1>
                </div>
                <form class="signup-form" method="post" action="" onsubmit="return validateForm()">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input id="full_name" name="full_name" type="text" autocomplete="off" placeholder="Your Name" required>
                        <div id="nameError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="user_username">Username</label>
                        <input id="user_username" name="user_username" type="text" autocomplete="off" placeholder="Username" required>
                        <div id="usernameError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="user_email">Email</label>
                        <input id="user_email" name="user_email" type="email" autocomplete="off" placeholder="example@email.com" required>
                        <div id="emailError" class="error-message"></div>
                    </div>
                    <div class="form-group">
                        <label for="user_password">Password</label>
                        <input id="user_password" name="user_password" type="password" autocomplete="off" placeholder="********" required>
                        <div id="passwordError" class="error-message"></div>
                    </div>
                    <input type="submit" class="submit-btn" name="signup" value="Sign Up">
                </form>
                <div class="footer">
                    Already have an account? <a href="..\USER\user_login.php" class="login-link">Log in</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function validateForm() {
        let isValid = true;
        const nameRegex = /^[a-zA-Z\s]{2,30}$/;
        const usernameRegex = /^[a-zA-Z0-9_]{3,20}$/;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

        // Validate Full Name
        const fullName = document.getElementById('full_name').value;
        if (!nameRegex.test(fullName)) {
            document.getElementById('nameError').textContent = "Name should be 2-30 characters long and contain only letters and spaces.";
            isValid = false;
        } else {
            document.getElementById('nameError').textContent = "";
        }

        // Validate Username
        const username = document.getElementById('user_username').value;
        if (!usernameRegex.test(username)) {
            document.getElementById('usernameError').textContent = "Username should be 3-20 characters long and can contain letters, numbers, and underscores.";
            isValid = false;
        } else {
            document.getElementById('usernameError').textContent = "";
        }

        // Validate Email
        const email = document.getElementById('user_email').value;
        if (!emailRegex.test(email)) {
            document.getElementById('emailError').textContent = "Please enter a valid email address.";
            isValid = false;
        } else {
            document.getElementById('emailError').textContent = "";
        }

        // Validate Password
        const password = document.getElementById('user_password').value;
        if (!passwordRegex.test(password)) {
            document.getElementById('passwordError').textContent = "Password must be at least 8 characters long and contain at least one letter and one number.";
            isValid = false;
        } else {
            document.getElementById('passwordError').textContent = "";
        }

        return isValid;
    }
    </script>
</body>
</html>