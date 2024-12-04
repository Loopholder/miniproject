<?php
include('../DATABASE/database.php');

// Start session
session_start();

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload'])) {
    // Check if the image was uploaded
    if (isset($_FILES['image_url']) && $_FILES['image_url']['error'] == 0) {
        // Define the directory where images will be saved
        $target_dir = "../images/"; // Make sure this directory exists
        $target_file = $target_dir . basename($_FILES['image_url']['name']);

        // Check if the file is an actual image
        $check = getimagesize($_FILES['image_url']['tmp_name']);
        if ($check !== false) {
            // Try to move the uploaded file to the target directory
            if (move_uploaded_file($_FILES['image_url']['tmp_name'], $target_file)) {
                // Insert image details into the database
                $image_name = basename($_FILES['image_url']['name']);
                $sql = "INSERT INTO TBL_MAIN_PAGE_IMAGES (image_name, upload_date) VALUES (?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $image_name);

                if ($stmt->execute()) {
                    $upload_success = "The image has been uploaded and saved to the database.";
                } else {
                    $upload_error = "Error saving image to database: " . $stmt->error;
                }
                $stmt->close();
            } else {
                $upload_error = "Sorry, there was an error uploading your file.";
            }
        } else {
            $upload_error = "File is not an image.";
        }
    } else {
        $upload_error = "No image was uploaded or there was an error uploading.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-size: 16px;
            margin-bottom: 5px;
            color: #333;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Upload Image</h2>
    <form method="POST" action="" enctype="multipart/form-data"> <!-- Added enctype for file uploads -->
        <div class="form-group">
            <label for="image_url">Select Image</label>
            <input type="file" name="image_url" id="image_url" required> <!-- 'file' input for image uploads -->
        </div>
        <div class="form-group">
            <button type="submit" name="upload">Upload Image</button> <!-- Added name for the submit button -->
        </div>
        <?php if (isset($upload_success)) echo "<p class='message'>$upload_success</p>"; ?>
        <?php if (isset($upload_error)) echo "<p class='message'>$upload_error</p>"; ?>
    </form>
</div>


</body>
</html>