<!DOCTYPE html>
<html>
<head>
  <title>Service Disabled</title>
  <style>
    body {
      background-color: #4a4a4a;
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      text-align: center;
    }
    
    .container {
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh;
      animation: fade-in 1s ease-in-out;
    }
    
    h1 {
      font-size: 36px;
      margin-bottom: 20px;
      color: #ffffff;
    }
    
    p {
      font-size: 20px;
      color: #ffffff;
    }
    
    @keyframes fade-in {
      0% {
        opacity: 0;
      }
      100% {
        opacity: 1;
      }
    }
    
    .logo {
      width: 150px;
      height: 150px;
      margin-bottom: 30px;
    }
  </style>
</head>
<body>
  <div class="container">
    <img src="{{$logo}}" alt="Logo" class="logo">
    <h1>Service Disabled</h1>
    <p>{{$message}}</p>
  </div>
</body>
</html>
