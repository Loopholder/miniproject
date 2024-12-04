<?php
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password is empty for XAMPP
$dbname = "ferro_faab_db";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($sql);
// Select the database
$conn->select_db($dbname);

// SQL to create tables

// TBL_USER
$sql = "CREATE TABLE IF NOT EXISTS TBL_USER (
    user_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(50) NOT NULL,
    user_username VARCHAR(50) NOT NULL,
    user_password VARCHAR(255) NOT NULL,   -- Passwords should allow for hash storage
    user_email VARCHAR(100) NOT NULL
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_USER table: " . $conn->error;
}

// TBL_ADMIN
$sql = "CREATE TABLE IF NOT EXISTS TBL_ADMIN (
    admin_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    admin_username VARCHAR(50) NOT NULL,
    admin_password VARCHAR(255) NOT NULL,  -- Passwords should allow for hash storage
    admin_email VARCHAR(100) NOT NULL,
    admin_created_at DATE NOT NULL
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_ADMIN table: " . $conn->error;
}

// TBL_IMAGE
$sql = "CREATE TABLE IF NOT EXISTS TBL_IMAGE (
    image_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(100) NOT NULL,
    upload_date DATE NOT NULL,
    admin_id INT(10) NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES TBL_ADMIN(admin_id)
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_IMAGE table: " . $conn->error;
}

// TBL_USER_REVIEW
$sql = "CREATE TABLE IF NOT EXISTS TBL_USER_REVIEW (
    review_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) NOT NULL,
    review_text VARCHAR(255) NOT NULL,
    review_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES TBL_USER(user_id)
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_USER_REVIEW table: " . $conn->error;
}

// TBL_ADMIN_REVIEW
$sql = "CREATE TABLE IF NOT EXISTS TBL_ADMIN_REVIEW (
    review_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) NOT NULL,
    admin_id INT(10) NOT NULL,
    admin_comment VARCHAR(255) NOT NULL,
    admin_review_date DATE NOT NULL,
    FOREIGN KEY (user_id) REFERENCES TBL_USER(user_id),
    FOREIGN KEY (admin_id) REFERENCES TBL_ADMIN(admin_id)
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_ADMIN_REVIEW table: " . $conn->error;
}

// TBL_MAIN_PAGE_IMAGES
$sql = "CREATE TABLE IF NOT EXISTS TBL_MAIN_PAGE_IMAGES (
    image_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(100) NOT NULL,
    upload_date DATE NOT NULL
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_MAIN_PAGE_IMAGES table: " . $conn->error;
}

// TBL_LANDING_PAGE_IMAGES
$sql = "CREATE TABLE IF NOT EXISTS TBL_LANDING_PAGE_IMAGES (
    image_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    image_name VARCHAR(100) NOT NULL,
    upload_date DATE NOT NULL,
    admin_id INT(10) NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES TBL_ADMIN(admin_id)
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_LANDING_PAGE_IMAGES table: " . $conn->error;
}

// TBL_CONTACT
$sql = "CREATE TABLE IF NOT EXISTS TBL_CONTACT (
    contact_id INT(10) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(10) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    Address VARCHAR(255) NOT NULL,  
    Place VARCHAR(100) NOT NULL,
    District VARCHAR(100) NOT NULL,
    E_mail VARCHAR(100) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    pro_img VARCHAR(100) NOT NULL,
     FOREIGN KEY (user_id) REFERENCES TBL_USER(user_id)
)";
if ($conn->query($sql) !== TRUE) {
    echo "Error creating TBL_CONTACT table: " . $conn->error;
}

?>
