<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unauthorized</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .logo {
            /* Replace with your company logo image URL */
            background-image: url('{{$logo}}');
            background-size: contain;
            background-repeat: no-repeat;
            width: 200px; /* Adjust the size as needed */
            height: 200px; /* Adjust the size as needed */
            margin-bottom: 20px;
        }

        .message {
            text-align: center;
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }

        .animation {
            /* Add your animation styles here */
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-20px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body>
    <div class="logo"></div>
    <div class="message">Unauthorized Access</div>
    <div class="animation"> <!-- Apply animation to this element -->
        <p>Please contact the administrator for assistance.</p>
    </div>
</body>
</html>
