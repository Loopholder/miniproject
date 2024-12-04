<?php
session_start(); // Start the session to access the user_id and profile data
include ('../DATABASE/database.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to submit contact information.");
}

// Define a variable to check if the profile is submitted
$profileSubmitted = false;

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

    // Handle image upload
    $targetDir = "C:\Users\hp\OneDrive\Pictures";  // Correct directory path format
    $targetFile = $targetDir . basename($_FILES["pro_img"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a real image or fake
    $check = getimagesize($_FILES["pro_img"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["pro_img"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats (JPEG, PNG, JPG, GIF)
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // if everything is ok, try to upload file
        if (move_uploaded_file($_FILES["pro_img"]["tmp_name"], $targetFile)) {
            echo "The file " . htmlspecialchars(basename($_FILES["pro_img"]["name"])) . " has been uploaded.";
            // Save the image path in session for later display
            $_SESSION['pro_img'] = $targetFile;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Store submitted data in session to display on profile section
    $_SESSION['profile'] = [
        'full_name' => $full_name,
        'address' => $address,
        'place' => $place,
        'district' => $district,
        'email' => $email,
        'phone_number' => $phone_number,
        'pro_img' => $targetFile // Correctly use the file path from upload
    ];

    // Prepare SQL statement to insert data into TBL_CONTACT
    $sql = "INSERT INTO TBL_CONTACT (user_id, full_name, Address, Place, District, E_mail, phone_number, pro_img)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    // Bind parameters (s for string, i for integer)
    $stmt->bind_param("isssssss", $user_id, $full_name, $address, $place, $district, $email, $phone_number, $targetFile);

    // Execute the statement
    if ($stmt->execute()) {
        $profileSubmitted = true; // Indicate that the profile is created successfully
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
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 400px;
            padding: 30px;
            transition: transform 0.3s ease-in-out;
        }

        .profile-container:hover {
            transform: translateY(-10px);
        }

        .profile-header {
            background-color: #6A1B9A;
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .profile-header img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .profile-header h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .profile-header p {
            font-size: 14px;
            margin-bottom: 0;
            color: #d1c4e9;
        }

        .profile-details {
            padding: 20px;
            background-color: #fff;
            border-radius: 0 0 8px 8px;
        }

        .profile-details h4 {
            font-size: 18px;
            color: #6A1B9A;
            margin-bottom: 15px;
            text-align: center;
        }

        .profile-details p {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .profile-details p span {
            font-weight: bold;
            color: #6A1B9A;
        }

        .profile-card-footer {
            text-align: center;
            margin-top: 20px;
            text-decoration:none;
        }

        .profile-card-footer button {
            background-color: #6A1B9A;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .profile-card-footer button:hover {
            background-color: #4A148C;
        }

        .form-container {
            display: flex;
            flex: 2;
            padding: 20px;
        }

        .profile-settings {
            width: 100%;
        }

        /* CSS to set image dimensions */
        #profileImage {
            width: 200px;
            height: 200px;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8); /* Black with transparency */
        }

        /* Modal Content (enlarged image) */
        .modal-content {
            margin: auto;
            display: block;
            max-width: 80%;
        }

        /* Caption of the image */
        #caption {
            margin: auto;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
        }

        /* The Close Button */
        .close {
            position: absolute;
            top: 50px;
            right: 100px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
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

        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .input-group input[type="file"] {
            padding: 3px;
        }
    </style>
</head>
<body>

<div class="container">
<?php if ($profileSubmitted): ?>
    <div class="profile-container">
        <div class="profile-header">
            <?php if (isset($_SESSION['pro_img'])): ?>
                <img src="<?php echo $_SESSION['pro_img']; ?>" alt="Profile Image" id="profileImage" onclick="openModal();">
            <?php else: ?>
                <img src="default-profile.png" alt="Default Profile Image" id="profileImage" onclick="openModal();">
            <?php endif; ?>
            <h3><?php echo $_SESSION['profile']['full_name'] ?? 'Your Name'; ?></h3>
            <p><?php echo $_SESSION['profile']['email'] ?? 'your.email@example.com'; ?></p>
        </div>

        <div class="profile-details">
            <h4>Profile Details</h4>
            <p><span>Full Name:</span> <?php echo $_SESSION['profile']['full_name'] ?? ''; ?></p>
            <p><span>Address:</span> <?php echo $_SESSION['profile']['address'] ?? ''; ?></p>
            <p><span>Place:</span> <?php echo $_SESSION['profile']['place'] ?? ''; ?></p>
            <p><span>District:</span> <?php echo $_SESSION['profile']['district'] ?? ''; ?></p>
            <p><span>Email:</span> <?php echo $_SESSION['profile']['email'] ?? ''; ?></p>
            <p><span>Phone Number:</span> <?php echo $_SESSION['profile']['phone_number'] ?? ''; ?></p>
        </div>

        <div class="profile-card-footer" style="text-decoration: none;">
            <button onclick="document.getElementById('settingsModal').style.display='block'"><a href="../USER/edit_profile.php">Edit Profile</a></button>
        </div>
    </div>
</div>

<!-- The Modal for enlarged image -->
<div id="imageModal" class="modal">
    <span class="close" onclick="closeModal()">&times;</span>
    <img class="modal-content" id="img01">
    <div id="caption"></div>
</div>

<!-- Settings Modal -->
    <div id="settingsModal" class="modal">
        <div class="modal-content" style="padding: 20px;">
            <span class="close" onclick="closeSettingsModal()">&times;</span>
            <h2>Edit Profile</h2>

            <?php else: ?>

            <form method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <label for="full_name">Full Name:</label>
                    <input type="text" id="full_name" name="full_name" required>
                </div>
                <div class="input-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="input-group">
                    <label for="place">Place:</label>
                    <input type="text" id="place" name="place" required>
                </div>
                <div class="input-group">
                    <label for="district">District:</label>
                    <input type="text" id="district" name="district" required>
                </div>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="input-group">
                    <label for="phone_number">Phone Number:</label>
                    <input type="tel" id="phone_number" name="phone_number" required>
                </div>
                <div class="input-group">
                    <label for="pro_img">Profile Image:</label>
                    <input type="file" id="pro_img" name="pro_img" accept="image/*">
                </div>
                <button type="submit">Submit</button>
            </form>
            <?php endif; ?>

        </div>
</div>

<script>
    // Function to open the modal for the profile image
    function openModal() {
        document.getElementById('imageModal').style.display = "block";
        var img = document.getElementById('profileImage');
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
    }

    // Function to close the modal for the profile image
    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
    }

    // Function to close the settings modal
    function closeSettingsModal() {
        document.getElementById('settingsModal').style.display = "none";
    }
</script>

</body>
</html>
