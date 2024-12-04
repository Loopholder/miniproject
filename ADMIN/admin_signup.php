<?php
include ('../DATABASE/database.php');

if(isset($_POST['signup']))
{
    $a_user=$_POST['admin_username'];
    $a_email=$_POST['admin_email'];
    $a_pass=$_POST['admin_password'];

    $ins= "INSERT INTO TBL_ADMIN(
        admin_username,admin_email,admin_password)
        VALUES ('$a_user', '$a_email', '$a_pass')";
        mysqli_query($conn,$ins);

}
?>


<html>
<head>
    
    <title>Admin Sign Up</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: black;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        input[type="text"], input[type="password"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
    </style>
</head>
<body>

    <form method="POST">
        <h2>Admin Sign Up</h2>
        <input type="text" name="admin_username" placeholder="Username" required>
        <input type="email" name="admin_email" placeholder="Email" required>
        <input type="password" name="admin_password" placeholder="Password" required>
        <input type="submit" name="signup" value="Sign Up">
    </form>

</body>
</html>
