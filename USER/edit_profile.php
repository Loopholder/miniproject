<?php
// Include database connection
include('../DATABASE/database.php');

// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

<?php
session_start();
require '../path_to_your_db_connection_file.php'; // Adjust the path

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $place = $_POST['place'];
    $district = $_POST['district'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $pro_img = isset($_FILES['pro_img']['name']) && $_FILES['pro_img']['name'] !== '' ? 'uploads/' . basename($_FILES['pro_img']['name']) : $_SESSION['profile']['pro_img'];

    // Upload new profile image if provided
    if (!empty($_FILES['pro_img']['tmp_name'])) {
        move_uploaded_file($_FILES['pro_img']['tmp_name'], $pro_img);
    }

    // Update the database
    $sql = "UPDATE TBL_CONTACT SET full_name = ?, address = ?, place = ?, district = ?, email = ?, phone_number = ?, pro_img = ? WHERE contact_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssi", $full_name, $address, $place, $district, $email, $phone_number, $pro_img, $_SESSION['profile']['contact_id']);
        if ($stmt->execute()) {
            // Update session data
            $_SESSION['profile'] = [
                'full_name' => $full_name,
                'address' => $address,
                'place' => $place,
                'district' => $district,
                'email' => $email,
                'phone_number' => $phone_number,
                'pro_img' => $pro_img,
                'contact_id' => $_SESSION['profile']['contact_id'],
            ];

            // Redirect to profile.php with anchor
            header('Location: profile.php#profile-details');
            exit();
        } else {
            $_SESSION['error_msg'] = "Error updating profile: " . $stmt->error;
        }
    } else {
        $_SESSION['error_msg'] = "Error preparing statement: " . $conn->error;
    }
}


$user_id = $_SESSION['user_id'];

// Function to sanitize input
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fetch the contact details associated with the current user
$sql = "SELECT * FROM TBL_CONTACT WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $contact = $result->fetch_assoc();
    $contact_id = $contact['contact_id'];
} else {
    $_SESSION['error_msg'] = "No contact info found!";
    header("Location: profile.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    $full_name = sanitize_input($_POST['full_name']);
    $address = sanitize_input($_POST['Address']);
    $place = sanitize_input($_POST['Place']);
    $district = sanitize_input($_POST['District']);
    $email = filter_var($_POST['E_mail'], FILTER_SANITIZE_EMAIL);
    $phone_number = sanitize_input($_POST['phone_number']);

    // Handle file upload for profile image
    $pro_img = $contact['pro_img'];
    if (isset($_FILES['pro_img']) && $_FILES['pro_img']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (in_array($_FILES['pro_img']['type'], $allowed_types) && $_FILES['pro_img']['size'] <= $max_size) {
            $target_dir = "uploads/";
            $file_extension = pathinfo($_FILES['pro_img']['name'], PATHINFO_EXTENSION);
            $file_name = uniqid() . '.' . $file_extension;
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($_FILES['pro_img']['tmp_name'], $target_file)) {
                $pro_img = $target_file;
            } else {
                $_SESSION['error_msg'] = "Error uploading your file.";
            }
        } else {
            $_SESSION['error_msg'] = "Invalid file. Max size: 5MB. Allowed types: JPG, PNG, GIF.";
        }
    }

  /*  // Prepare SQL query to update contact details
    $sql = "UPDATE TBL_CONTACT SET full_name = ?, address = ?, place = ?, district = ?, email = ?, phone_number = ?, pro_img = ? WHERE contact_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssi", $full_name, $address, $place, $district, $email, $phone_number, $pro_img, $contact_id);

    if ($stmt->execute()) {
        $_SESSION['success_msg'] = "Profile updated successfully!";
        header('Location: profile.php');
        exit();
    } else {
        $_SESSION['error_msg'] = "Error updating profile: " . $stmt->error;
    }   */

    // Prepare SQL query to update contact details
        $sql = "UPDATE TBL_CONTACT SET full_name = ?, Address = ?, Place = ?, District = ?, E_mail = ?, phone_number = ?, pro_img = ? WHERE contact_id = ?";
        $stmt = $conn->prepare($sql);

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            die("Error preparing the SQL statement: " . $conn->error);
        }

        $stmt->bind_param("sssssssi", $full_name, $address, $place, $district, $email, $phone_number, $pro_img, $contact_id);

        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Profile updated successfully!";
            header('Location: profile.php');
            exit();
        } else {
            $_SESSION['error_msg'] = "Error updating profile: " . $stmt->error;
        }

}

// Generate CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .modal {
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
        img {
            display: block;
            margin-top: 10px;
            max-width: 150px;
            max-height: 150px;
        }
    </style>
</head>
<body>

<div class="modal">
    <div class="modal-content">
        <h2>Edit Profile</h2>
        <?php
        if (isset($_SESSION['error_msg'])) {
            echo "<p style='color: red;'>" . $_SESSION['error_msg'] . "</p>";
            unset($_SESSION['error_msg']);
        }
        if (isset($_SESSION['success_msg'])) {
            echo "<p style='color: green;'>" . $_SESSION['success_msg'] . "</p>";
            unset($_SESSION['success_msg']);
        }
        ?>
        <form method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div class="input-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($contact["full_name"] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($contact["Address"] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="place">Place:</label>
                <input type="text" id="place" name="place" value="<?php echo htmlspecialchars($contact["Place"] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="district">District:</label>
                <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($contact["District"] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($contact["E_mail"] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="phone_number">Phone Number:</label>
                <input type="tel" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($contact["phone_number"] ?? ''); ?>" required>
            </div>
            <div class="input-group">
                <label for="pro_img">Profile Image:</label>
                <input type="file" id="pro_img" name="pro_img" accept="image/*">
                <?php if (!empty($contact['pro_img'])): ?>
                    <img src="<?php echo htmlspecialchars($contact['pro_img']); ?>" alt="Profile Image">
                <?php endif; ?>
            </div>
            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script>
function validateForm() {
    var fullName = document.getElementById('full_name').value;
    var email = document.getElementById('email').value;
    var phoneNumber = document.getElementById('phone_number').value;

    if (fullName.length < 2) {
        alert('Full name must be at least 2 characters long.');
        return false;
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert('Please enter a valid email address.');
        return false;
    }

    var phoneRegex = /^\d{10}$/;
    if (!phoneRegex.test(phoneNumber)) {
        alert('Please enter a valid 10-digit phone number.');
        return false;
    }

    return true;
}
</script>

</body>
</html>
