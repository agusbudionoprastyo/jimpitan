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
  <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
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

    .floating-button {
      position: fixed;
      top: 20px; /* Jarak dari atas */
      right: 20px; /* Jarak dari kanan */
      background-color: #14505c; /* Warna latar belakang dengan transparansi */
      border-radius: 50%; /* Membuat tombol bulat */
      width: 60px; /* Lebar tombol */
      height: 60px; /* Tinggi tombol */
      display: flex;
      justify-content: center;
      align-items: center;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2); /* Bayangan */
      z-index: 1000; /* Pastikan di atas elemen lain */
  }

  .floating-button a {
      color: white; /* Warna teks */
      font-size: 24px; /* Ukuran teks */
      text-decoration: none; /* Menghilangkan garis bawah */
  }
  button {
    margin: 10px;
    padding: 10px 20px;
    border-radius: 25px;
    background-color: #14505c;
    color: white;
    border: none;
    cursor: pointer;
  }
  button:disabled {
      background-color: #ccc;
      cursor: not-allowed;
  }

  </style>
</head>
<body>

<div id="landscapeBlocker">
  <img src="assets/image/block.gif" alt="Please rotate your device to portrait mode">
  <p>Please rotate your device to portrait mode.</p>
</div>

<div class="container">
  <h3>Jimpitan</h3>
  <p>RT07 SALATIGA</p>
  <p>
    <?php
    date_default_timezone_set('Asia/Jakarta');
    $tanggal_sekarang=date('l, j F Y');
    echo"Tanggal: ".$tanggal_sekarang;
    ?>
  </p>
  <div class="floating-button">
    <a href="dashboard/logout.php"><i class="bx bx-log-out-circle bx-tada bx-flip-horizontal" style="font-size:24px" ></i></a>
    <button id="clearCacheButton"><i class='bx bx-refresh'></i></button>
  </div>
  <div id="qr-reader"></div>
  <button id="startButton" class="roundedBtn">Start Scan</button>
  <button id="stopButton" class="roundedBtn" disabled>Stop Scan</button>
  <label for="qr-input-file" class="roundedBtn"><i class='bx bxs-camera'></i></label>
  <input type="file" id="qr-input-file" accept="image/*" capture hidden>
</div>

<audio id="audio" src="assets/audio/interface.wav"></audio>

<script src="js/app.js"></script>
  <script>
        // Clear cache and reload
        document.getElementById('clearCacheButton').addEventListener('click', function() {
        if (caches) {
            caches.keys().then(function(cacheNames) {
                cacheNames.forEach(function(cacheName) {
                    caches.delete(cacheName);
                });
            }).then(function() {
                location.reload(true); // Force reload
            });
        } else {
            location.reload(); // Fallback if caches are not supported
        }
    });
        // Register the service worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
        navigator.serviceWorker.register('/service-worker.js')
            .then((registration) => {
            console.log('Service Worker registered with scope:', registration.scope);
            })
            .catch((error) => {
            console.error('Service Worker registration failed:', error);
            });
        });
    }
  </script>
</body>
</html>
