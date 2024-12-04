<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferro Faab Landing Page</title>
    <style>
        /* General Styles */
        body {
            margin: 0;
            padding: 0;
            font-family: algerian;
            background-color: black;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            box-sizing: border-box;
            position: relative;
            flex-direction: column;
        }

        /* Letter-by-Letter Animation */
        .title {
            font-size: 72px; /* Bigger text */
            font-weight: bold;
            display: flex;
            gap: 5px;
            position: relative;
            overflow: hidden; /* Prevent underline overflow */
        }

        .title::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -5px; /* Adjust to move the underline closer/further */
            width: 0; /* Start with no width */
            height: 4px; /* Thickness of the underline */
            background-color: white; /* Color of the underline */
            animation: growUnderline 4.5s forwards; /* Match with letter animation */
        }

        .title span {
            opacity: 0;
            animation: fadeIn 0.5s forwards;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Delaying animation for each letter */
        .title span:nth-child(1) { animation-delay: 0s; }
        .title span:nth-child(2) { animation-delay: 0.1s; }
        .title span:nth-child(3) { animation-delay: 0.2s; }
        .title span:nth-child(4) { animation-delay: 0.3s; }
        .title span:nth-child(5) { animation-delay: 0.4s; }
        .title span:nth-child(6) { animation-delay: 0.5s; }
        .title span:nth-child(7) { animation-delay: 0.6s; }
        .title span:nth-child(8) { animation-delay: 0.7s; }
        .title span:nth-child(9) { animation-delay: 0.8s; }

        /* Animation for the underline */
        @keyframes growUnderline {
            0% {
                width: 0;
            }
            100% {
                width: 100%;
            }
        }

        /* Signup Button */
        .signup-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: white;
            color: blue;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .signup-btn:hover {
            background-color: lightblue;
            color: black;
        }

        /* Cupboard Animation */
        .container {
            position: relative;
            width: 150px;
            height: 250px;
            margin-top: 20px; /* Space between title and cupboard */
        }

        .stick {
            position: absolute;
            width: 10px;
            height: calc(100% ); /* Adjusted to fit between green sticks */
            background-color: white;
            opacity: 0; /* Initially hidden */
            animation: drop 1s ease forwards;
        }

        .stick.left {
            left: 0;
            animation-delay: 0.5s;
        }

        .stick.right {
            right: 0;
            animation-delay: 1s;
        }

        .stick.middle {
            left: calc(50% - 5px);
            animation-delay: 1.5s;
        }

        .shelf {
            position: absolute;
            width: calc(100% - 10px);
            height: 10px;
            background-color: white;
            opacity: 0; /* Initially hidden */
            animation: dropShelf 1s ease forwards;
        }

        .shelf.top {
            top: 0;
            left: 5px;
            animation-delay: 2s;
        }

        .shelf.middle {
            top: calc(50% - 5px);
            left: 5px;
            animation-delay: 2s;
        }

        .shelf.bottom {
            bottom: 0;
            left: 5px;
            animation-delay: 2s;
        }

        @keyframes drop {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes dropShelf {
            0% {
                transform: translateY(-100%);
                opacity: 0;
            }
            50% {
                opacity: 1;
            }
            100% {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Line Animation */
        .line {
            opacity: 0;
            animation: fadeInLine 2s ease forwards;
            animation-delay: 3s; /* Delay after cupboard animation */
            margin-top: 20px;
            font-size: 24px;
        }

        @keyframes fadeInLine {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <div class="title">
        <span>F</span><span>E</span><span>R</span><span>R</span><span>O</span>
        <span>F</span><span>A</span><span>A</span><span>B</span>
    </div>

    <div class="container">
        <div class="stick left"></div>
        <div class="stick right"></div>
        <div class="stick middle"></div>

        <div class="shelf top"></div>
        <div class="shelf middle"></div>
        <div class="shelf bottom"></div>
    </div>

    <div class="line">
        <p>Build your dream with us ..</p>
    </div>

    <a href="../USER/signup.php"><button class="signup-btn">Sign Up</button></a>

</body>
</html>
