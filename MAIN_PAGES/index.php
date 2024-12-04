<?php
include('../DATABASE/database.php');

// Start session
session_start();
// Fetch images from the database for the "Our Works" section
$sql = "SELECT image_name FROM TBL_MAIN_PAGE_IMAGES";
$result = mysqli_query($conn, $sql);
$images = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row['image_name']; // Store image names
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FERRO FAAB</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            background-color: #f2f2f2;
            color: black;
            font-family: Arial, sans-serif;
        }
        .hero {
            margin-top: 60px;
            background-image: url('../images/ferro_background.png');
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
        }
        .overlay {
            background: rgba(0, 0, 0, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }
        .buttons {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.8);
            padding: 10px 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            margin-left: 20px;
            font-family: 'Fredoka One', cursive;
            font-size: 28px;
            color: white;
        }
        .buttons a {
            color: white;
            border: 1px solid white;
            padding: 10px 20px;
            margin: 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            letter-spacing: 1px;
            margin-left: auto;
        }
        .buttons a:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        /* Profile button in header */
        .profile-button {
            background-color: transparent;
            border: 1px solid white;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-left: 20px;
            position: relative;
        }
        .profile-button img {
            width: 20px; /* Adjust size of mannequin icon */
            height: 20px;
        }
        .section {
            padding: 80px 20px;
            height: 100vh;
            text-align: center;
            background-color: rgba(0, 0, 0, 0.1);
            margin-top: 60px;
        }
        .section h2 {
            font-size: 32px;
            color: black;
            margin-bottom: 20px;
        }
        .section p {
            font-size: 18px;
            color: #555;
            line-height: 1.6;
            margin: 0 auto;
            max-width: 800px;
        }
        #about {
            max-width: 1000px; /* Set a max width to ensure it doesn't stretch too wide */
            margin: 20px auto; /* Center the section and add margin */
            padding: 30px; /* Add padding for spacing */
            background-color: #f9f9f9; /* Light background for contrast */
            border-radius: 8px; /* Optional: rounded corners */
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Optional: subtle shadow for depth */
            overflow: auto; /* Allow scrolling if content overflows */
        }
        /* Gallery grid styling */
        .works-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            overflow-y: auto;
            max-height: 500px; /* You can adjust the height */
            padding: 10px;
            border: 1px solid #ccc;
        }
        /* Scroll only when mouse is over the grid */
        .works-gallery:hover {
            overflow-y: scroll;
        }
        /* Image styling */
        .image-container {
            position: relative;
            overflow: hidden;
        }
        .work-image {
            width: 100%;
            height: auto;
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .work-image:hover {
            transform: scale(1.1);
        }
        /* Modal styling for enlarged image */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }
        .modal-content {
            margin: auto;
            display: block;
            max-width: 90%;
            max-height: 80%;
        }
        .close {
            position: absolute;
            top: 50px;
            right: 50px;
            color: white;
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
        }
        footer {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 20px;
            margin-top: 40px;
        }
        footer p {
            margin: 0;
        }
        /* Bottom link button */
        .more-link {
            display: block;
            text-align: center;
            margin: 20px 0;
            font-size: 18px;
            color: #007bff;
            text-decoration: none; /* No decoration */
        }
    </style>
</head>
<body>

    <div class="hero">
        <div class="overlay">
            <div class="content">
                <!-- No additional heading here -->
            </div>
        </div>
    </div>

    <div class="buttons">
        <div class="logo">FERRO FAAB</div>
        <div>
            <a href="#intro">Intro</a>
            <a href="#works">Works</a>
            <a href="#about">About</a>
            <a href="#contact">Contact</a>
            <!-- Profile button in header -->
            <a href="../USER/profile.php" class="profile-button">
                <img src="../images/profile.webp" alt="Profile Icon"> <!-- Use the path to your mannequin icon -->
            </a>
        </div>
    </div>

    <div id="intro" class="section">
        <h2>Introduction</h2>
        <p>Welcome to Ferro Faab, your trusted partner in quality ferro slab manufacturing and construction solutions. Our state-of-the-art technology and skilled artisans ensure that every ferro slab we produce meets the highest standards of strength, durability, and aesthetic appeal. Whether you’re looking to build residential homes, commercial spaces, or infrastructure projects, our ferro slabs provide a solid foundation that can withstand the test of time. With a commitment to excellence and customer satisfaction, we are dedicated to delivering innovative solutions tailored to your specific needs. Explore our offerings and discover how Ferro Faab can elevate your next project.</p>
        <img src="../images/Design 2.jpg" height=390px; width=400px;>
    </div>

    <div id="works" class="section">
        <h2>Our Works</h2>
        <p>Explore our extensive portfolio of completed projects featuring our premium ferro slabs.</p>

        <!-- Works Gallery -->
        <div class="works-gallery" id="works-gallery">
            <?php if (!empty($images)): ?>
                <?php foreach ($images as $image_name): ?>
                    <div class="image-container">
                        <!-- Display Image: Correct path for image location -->
                        <img src="../images/<?php echo $image_name; ?>" alt="Ferro Slab Design" class="work-image" onclick="enlargeImage(this)">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No works available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal for Enlarged Image -->
    <div id="image-modal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modal-image">
    </div>

    <div id="about" class="section">
        <h2>About Us</h2>
        <p>At Ferro Faab, we pride ourselves on being industry leaders in the production of high-quality ferro slabs. Our team is comprised of experienced professionals who are passionate about delivering exceptional products and services. With a focus on sustainability and innovation, we are committed to reducing our environmental impact while providing...</p>
        <h3>Our Mission</h3>
        <p>At Ferro Faab, our mission is to revolutionize the construction industry by providing high-quality ferro slabs that meet the diverse needs of our clients. We strive to create durable, efficient, and sustainable building solutions that not only enhance architectural designs but also contribute positively to the environment. By leveraging advanced technology and innovative manufacturing processes, we aim to lead the way in modern construction methods, ensuring that our products stand the test of time.</p>        
        <h3>What We Do</h3>
        <p>Ferro Faab specializes in the production and supply of ferro slabs, a versatile building material known for its strength and adaptability. Our slabs are designed for various applications, including residential, commercial, and industrial projects. Whether you are constructing a new building or renovating an existing structure, our ferro slabs provide the perfect combination of aesthetics and functionality. Our team of experts works closely with architects, contractors, and builders to deliver customized solutions that align with specific project requirements.</p>
        <h3>Quality and Innovation</h3>
        <p>Quality is at the core of everything we do. We adhere to stringent quality control measures throughout the manufacturing process, ensuring that every ferro slab meets the highest industry standards. Our commitment to innovation drives us to continuously improve our products and services. We invest in research and development to explore new materials, technologies, and methods that enhance the performance and sustainability of our ferro slabs. This dedication to quality and innovation sets us apart as a leader in the construction materials industry.</p>
        <h3>Join Us in Building the Future</h3>
        <p>As we continue to grow and evolve, we invite you to join us on this journey of innovation and excellence. Whether you are an industry professional or a homeowner, we welcome you to explore the possibilities that Ferro Faab offers. Together, we can shape the future of construction with our high-quality ferro slabs, setting new standards for durability, design, and sustainability. Let’s build a better tomorrow, one slab at a time.</p>
    </div>

    <!-- Bottom link to profile -->
    <a href="../USER/profile.php" class="more-link">What to know more?</a>

    <footer>
        <p>&copy; 2024 Ferro Faab. All rights reserved.</p>
    </footer>

    <script>
        // Enlarge image in modal
        function enlargeImage(img) {
            const modal = document.getElementById("image-modal");
            const modalImg = document.getElementById("modal-image");
            modal.style.display = "block";
            modalImg.src = img.src;
        }

        // Close modal
        function closeModal() {
            const modal = document.getElementById("image-modal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
