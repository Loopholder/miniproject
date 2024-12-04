<?php
session_start(); // Start the session to access the user_id
include ('../DATABASE/database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit contact information.");
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the user_id from the session
    $user_id = $_SESSION['user_id'];
    
    // Get form data
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $place = $_POST['place'];
    $district = $_POST['district'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];

    // Store submitted data in session to display on profile page
    $_SESSION['profile'] = [
        'full_name' => $full_name,
        'address' => $address,
        'place' => $place,
        'district' => $district,
        'email' => $email,
        'phone_number' => $phone_number
    ];

    // Prepare SQL statement to insert data into TBL_CONTACT
    $sql = "INSERT INTO TBL_CONTACT (user_id, full_name, Address, Place, District, E_mail, phone_number)
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters (s for string, i for integer)
    $stmt->bind_param("issssss", $user_id, $full_name, $address, $place, $district, $email, $phone_number);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to profile page after successful submission
        header("Location: profile.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Settings</title>
    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f2f2f2;
}

.container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.profile-container {
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    max-width: 1000px;
    width: 100%;
    padding: 20px;
}

.profile-card {
    background-color: #6A1B9A;
    color: white;
    padding: 20px;
    text-align: center;
    border-radius: 8px 0 0 8px;
    flex: 2;
}

.profile-image img {
    border-radius: 50%;
    width: 100px;
    height: 100px;
}

.profile-card h3 {
    margin: 10px 0;
}

.profile-card p {
    color: #d1c4e9;
}

.form-container {
    display: flex;
    justify-content: space-between;
    flex: 2;
    padding: 20px;
}

.profile-settings, .experience-settings {
    width: 100%;
}

h2 {
    font-size: 20px;
    margin-bottom: 10px;
    color: #6A1B9A;
}

form {
    display: flex;
    flex-direction: column;
}

.input-group {
    margin-bottom: 15px;
}

.input-group label {
    margin-bottom: 5px;
    font-weight: 500;
}

.input-group input, 
.input-group textarea {
    width: 100%;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

textarea {
    height: 100px;
}

button {
    padding: 10px;
    background-color: #6A1B9A;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

button:hover {
    background-color: #4A148C;
}

    </style>
</head>
<body>
    <div class="container">
        <div class="profile-container">
            <div class="profile-card">
                <div class="profile-image">
                    <img src="https://via.placeholder.com/150" alt="Profile Picture">
                </div>
                <h3></h3>
                <p>edogaru@mail.com.my</p>
            </div>

            <div class="form-container">
                <div class="profile-settings">
                    <h2>Profile Settings</h2>
                    <form>
                        <div class="input-group">
                            <label for="firstName">First Name</label>
                            <input type="text" id="firstName" placeholder="Enter first name">
                        </div>
                        <div class="input-group">
                            <label for="lastName">Last Name</label>
                            <input type="text" id="lastName" placeholder="Enter last name">
                        </div>
                        <div class="input-group">
                            <label for="address">Address:</label>
                            <input type="text" id="address" name="address"  placeholder="Enter Address" required>
                        </div>
                        <div class="input-group">
                            <label for="place">Place:</label>
                            <input type="text" id="place" name="place"  placeholder="Enter Place" required>
                        </div>
                        <div class="input-group">
                        <label for="district">District:</label>
                        <input type="text" id="district" name="district"  placeholder="Enter District" required>
                        </div>
                        <div class="input-group">
                            <label for="district">E-mail:</label>
                            <input type="text" id="email" name="email"  placeholder="Enter E-mail" required>
                        </div>
                        <div class="input-group">
                            <label for="phone_number">Phone Number:</label>
                            <input type="text" id="phone_number" name="phone_number"  placeholder="Enter Phone_number" required>
                        </div>
                        <button type="submit">Save Profile</button>
                    </form>
                </div>
                <!--
                <div class="experience-settings">
                    <h2>Edit Experience</h2>
                    <form>
                        <div class="input-group">
                            <label for="experience">Experience</label>
                            <input type="text" id="experience" placeholder="Experience in Designing">
                        </div>
                        <div class="input-group">
                            <label for="additionalDetails">Additional Details</label>
                            <textarea id="additionalDetails" placeholder="Additional details"></textarea>
                        </div>
                        <button type="submit">Experience</button>
                    </form>
                </div>-->
            </div>
        </div>
    </div>
</body>
</html>
