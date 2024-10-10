<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Redirect to login page
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Jimpitan</title>
  <link rel="manifest" href="manifest.json">
  <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  <style>
    body, html {
        margin: 10px;
        padding: 0;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }
    #landscapeBlocker {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 10000;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    #landscapeBlocker img {
        max-width: 30%;
        max-height: 30%;
    }
    .container {
        text-align: center;
        margin-top: 50px;
    }
    .rounded {
        border-radius: 25px;
    }
    .roundedBtn {
        border-radius: 25px;
        background-color: #14505c;
        color: white;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 16px;
    }
    .custom-timer-progress-bar {
        height: 4px; /* Height of the progress bar */
        background-color: #FF8A8A; /* Color of the progress bar */
        width: 80%; /* Adjust width as needed */
        margin: 0 auto; /* Center the progress bar horizontally */
    }
  </style>
</head>
<body>

<div id="landscapeBlocker">
  <img src="assets/image/block.gif" alt="Please rotate your device to portrait mode">
  <p>Please rotate your device to portrait mode.</p>
</div>

<div class="container">
  <h1>Jimpitan</h1>
  <p>RT07 SALATIGA</p>
  <p>
    <?php
    date_default_timezone_set('Asia/Jakarta');
    $tanggal_sekarang=date('d-m-Y');
    echo"Tanggal: ".$tanggal_sekarang;
    ?>
  </p>
  <div id="qr-reader"></div>
</div>

<audio id="audio" src="assets/audio/interface.wav"></audio>

<script src="js/app.js"></script>
</body>
</html>
