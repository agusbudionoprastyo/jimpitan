// // Function to show or hide the landscape blocker
// function updateLandscapeBlocker() {
//     let landscapeBlocker = document.getElementById('landscapeBlocker');
//     if (landscapeBlocker) {
//         if (window.orientation === 90 || window.orientation === -90) {
//             landscapeBlocker.style.display = 'flex';
//             html5QrCode.stop().catch(function(err) {
//                 console.error('Error stopping QR Code scanner:', err);
//             });
//         } else {
//             landscapeBlocker.style.display = 'none';
//             startScanning();
//         }
//     }
// }

// // Start scanning when document is loaded
// document.addEventListener('DOMContentLoaded', function() {
//     updateLandscapeBlocker();
// });

// // Handle orientation change
// window.addEventListener('orientationchange', function() {
//     updateLandscapeBlocker();
// });

// // Function to play audio
// function playAudio() {
//     const audio = document.getElementById('audio');
//     if (audio) {
//         audio.play().catch(function(error) {
//             console.error('Error playing audio:', error);
//         });
//     }
// }

// function onScanSuccess(decodedText, decodedResult) {
//     const id = decodedText; // Ambil ID dari QR code

//     // Play audio
//     playAudio();

//     // Kirim ID ke server GAS
//     fetch('https://script.google.com/macros/s/AKfycbxD7iXEFOCCOrX5Ryln_NrzptYjtWf6Ia_WJu-j8Gtfgv3cefqdHIg4KL9N-5U4n60d/exec', {
//         method: 'POST',
//         mode: 'no-cors',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({ id: id })
//     })
//     .then(response => {
//         // Jika perlu, tambahkan logika untuk memeriksa status respons
//         console.log('Data sent to GAS:', id);
//     })
//     .catch(error => {
//         console.error('Error sending data to GAS:', error);
//     });

//     // Fetch ke API get_kk.php untuk mendapatkan nama
//     fetch('../api/get_kk.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/json'
//         },
//         body: JSON.stringify({ code_id: id })
//     })
//     .then(response => response.json())
//     .then(data => {
//         if (data.success && data.kk_name) {
//             const options = { day: 'numeric', month: 'long', year: 'numeric' };
//             const today = new Date().toLocaleDateString('id-ID', options); // Format tanggal
//             const nominal = 'Rp500'; // Nominal yang ingin ditampilkan
        
//             Swal.fire({
//                 icon: 'success',
//                 title: `${data.kk_name}`,
//                 text: `Jimpitan tanggal ${today} tercatat dengan nominal ${nominal}`,
//                 timer: 10000,
//                 timerProgressBar: true,
//                 customClass: {
//                     popup: 'rounded',
//                     timerProgressBar: 'custom-timer-progress-bar',
//                     confirmButton: 'roundedBtn'
//                 },
//                 willClose: startScanning // Mulai kembali pemindaian setelah alert ditutup
//             });               
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Not Found',
//                 text: 'No record found for the scanned ID.',
//                 confirmButton: 'OK',
//                 willClose: startScanning // Mulai kembali pemindaian setelah alert ditutup
//             });
//         }
//     })
//     .catch(error => {
//         console.error('Error fetching data:', error);
//         Swal.fire({
//             icon: 'error',
//             title: 'Fetch Error',
//             text: 'Could not retrieve data from server.',
//             confirmButton: 'OK',
//             willClose: startScanning // Mulai kembali pemindaian setelah alert ditutup
//         });
//     });

//     // Stop scanning after successful read
//     html5QrCode.stop();
// }


//   function onScanError(errorMessage) {
//       // Handle scan error (optional)
//       console.warn(`Scan error: ${errorMessage}`);
//   }

//   function startScanning() {
//       // Initialize the QR code scanner
//       html5QrCode.start(
//           { facingMode: "environment" }, // Use rear camera
//           {
//               fps: 20, // Frame rate
//               qrbox: 200 // QR code scanning box size
//           },
//           onScanSuccess,
//           onScanError
//       );
//   }

//   // Initialize the QR code scanner instance
//   const html5QrCode = new Html5Qrcode("qr-reader");

//   // Start scanning with the camera
//   startScanning();

let isScanning = false;
const html5QrCode = new Html5Qrcode("qr-reader");

// Function to show or hide the landscape blocker
function updateLandscapeBlocker() {
    const landscapeBlocker = document.getElementById('landscapeBlocker');
    if (landscapeBlocker) {
        if (window.orientation === 90 || window.orientation === -90) {
            landscapeBlocker.style.display = 'flex';
            stopScanning();
        } else {
            landscapeBlocker.style.display = 'none';
        }
    }
}

// Start scanning when document is loaded
document.addEventListener('DOMContentLoaded', updateLandscapeBlocker);
window.addEventListener('orientationchange', updateLandscapeBlocker);

// Function to play audio
function playAudio() {
    const audio = document.getElementById('audio');
    if (audio) {
        audio.play().catch(error => console.error('Error playing audio:', error));
    }
}

// Handle successful scan
function onScanSuccess(decodedText) {
    const id = decodedText; // Get ID from QR code
    playAudio();

    // Send ID to server
    fetch('https://script.google.com/macros/s/AKfycbxD7iXEFOCCOrX5Ryln_NrzptYjtWf6Ia_WJu-j8Gtfgv3cefqdHIg4KL9N-5U4n60d/exec', {
        method: 'POST',
        mode: 'no-cors',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: id })
    }).then(() => console.log('Data sent to GAS:', id))
      .catch(error => console.error('Error sending data to GAS:', error));

    // Fetch from API to get name
    fetch('../api/get_kk.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ code_id: id })
    }).then(response => response.json())
      .then(data => {
          const today = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
          const nominal = 'Rp500';
          if (data.success && data.kk_name) {
              Swal.fire({
                  icon: 'success',
                  title: `${data.kk_name}`,
                  text: `Jimpitan tanggal ${today} tercatat dengan nominal ${nominal}`,
                  timer: 10000,
                  timerProgressBar: true,
                  customClass: {
                      popup: 'rounded',
                      timerProgressBar: 'custom-timer-progress-bar',
                      confirmButton: 'roundedBtn'
                  },
                  willClose: startScanning
              });
          } else {
              Swal.fire({
                  icon: 'error',
                  title: 'Not Found',
                  text: 'No record found for the scanned ID.',
                  confirmButton: 'OK',
                  willClose: startScanning
              });
          }
      }).catch(error => {
          console.error('Error fetching data:', error);
          Swal.fire({
              icon: 'error',
              title: 'Fetch Error',
              text: 'Could not retrieve data from server.',
              confirmButton: 'OK',
              willClose: startScanning
          });
      });

    // start scanning after successful read
    startScanning();
}

function onScanError(errorMessage) {
    console.warn(`Scan error: ${errorMessage}`);
}

function startScanning() {
    if (!isScanning) {
        isScanning = true;
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 20, qrbox: 200 },
            onScanSuccess,
            onScanError
        ).catch(err => console.error('Error starting the QR code scanning:', err));
    }
}

function stopScanning() {
    if (isScanning) {
        isScanning = false;
        html5QrCode.stop().catch(err => console.error('Error stopping the QR code scanning:', err));
    }
}

const fileinput = document.getElementById('qr-input-file');
const fileInputLabel = document.getElementById('fileInputLabel');

fileInputLabel.addEventListener('click', (e) => {
    e.preventDefault();
    stopScanning();
    fileinput.click();
});

fileinput.addEventListener('change', e => {
    if (e.target.files.length === 0) {
        startScanning();
        return;
    }

    const imageFile = e.target.files[0];
    if (imageFile.type.startsWith('image/')) {
        html5QrCode.scanFile(imageFile, false)
            .then(qrCodeMessage => {
                onScanSuccess(qrCodeMessage);
            })
            .catch(err => {
                console.error(`Error scanning file. Reason: ${err}`);
                alert('Failed to scan QR code. Please try again.');
            });
    } else {
        alert('Please upload a valid image file.');
    }

    // Reset file input for the next scan
    fileinput.value = '';
});

// Start scanning with the camera
startScanning();
