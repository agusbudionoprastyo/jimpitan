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
//     const id = decodedText;

//     // Play audio
//     playAudio();

//     // First, send the ID to your server
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
//             Swal.fire({
//                 icon: 'success',
//                 title: `${data.kk_name}`,
//                 text: 'Checked',
//                 timer: 10000,
//                 timerProgressBar: true,
//                 customClass: {
//                     popup: 'rounded',
//                     timerProgressBar: 'custom-timer-progress-bar',
//                     confirmButton: 'roundedBtn'
//                 },
//                 willClose: () => {
//                     startScanning();
//                 }
//             });
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Not Found',
//                 text: 'No record found for the scanned ID.',
//                 confirmButton: 'OK',
//                 willClose: () => {
//                     startScanning();
//                 }
//             });
//         }
//     })
//     .catch(error => {
//         console.error('Error fetching KK name:', error);
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'There was an error fetching data.',
//             confirmButton: 'OK',
//             willClose: () => {
//                 startScanning();
//             }
//         });
//     })
//     .finally(() => {
//         // Send the ID to Google Apps Script at the end
//         fetch('https://script.google.com/macros/s/AKfycbxD7iXEFOCCOrX5Ryln_NrzptYjtWf6Ia_WJu-j8Gtfgv3cefqdHIg4KL9N-5U4n60d/exec', {
//             method: 'POST',
//             mode: 'no-cors', // Use no-cors mode
//             headers: {
//                 'Content-Type': 'application/json'
//             },
//             body: JSON.stringify({ id: id })
//         })
//         .catch(error => {
//             console.error('Error sending data to GAS:', error);
//         })
//         .finally(() => {
//             // Stop scanning after processing
//             html5QrCode.stop();
//         });
//     });
// }

// function onScanError(errorMessage) {
//     console.warn(`Scan error: ${errorMessage}`);
// }

// function startScanning() {
//     html5QrCode.start(
//         { facingMode: "environment" },
//         {
//             fps: 20,
//             qrbox: 200
//         },
//         onScanSuccess,
//         onScanError
//     );
// }

// // Initialize the QR code scanner instance
// const html5QrCode = new Html5Qrcode("qr-reader");

// // Start scanning with the camera
// startScanning();
  
// // Register the service worker
// if ('serviceWorker' in navigator) {
//     window.addEventListener('load', () => {
//         navigator.serviceWorker.register('/service-worker.js')
//             .then((registration) => {
//                 console.log('Service Worker registered with scope:', registration.scope);
//             })
//             .catch((error) => {
//                 console.error('Service Worker registration failed:', error);
//             });
//     });
// }

 // Function to show or hide the landscape blocker
 function updateLandscapeBlocker() {
    let landscapeBlocker = document.getElementById('landscapeBlocker');
    if (window.orientation === 90 || window.orientation === -90) {
        landscapeBlocker.style.display = 'flex';
        html5QrCode.stop().catch(function(err) {
            console.error('Error stopping QR Code scanner:', err);
        });
    } else {
        landscapeBlocker.style.display = 'none';
        startScanning();
    }
}

// Start scanning when document is loaded
document.addEventListener('DOMContentLoaded', function() {
    updateLandscapeBlocker();
});

// Handle orientation change
window.addEventListener('orientationchange', function() {
    updateLandscapeBlocker();
});
    
// Function to play audio
function playAudio() {
    const audio = document.getElementById('audio');
    audio.play().catch(function(error) {
        console.error('Error playing audio:', error);
    });
}
    
function onScanSuccess(decodedText, decodedResult) {
  // Ambil ID dari QR code
  const id = decodedText;

  // Play audio
  playAudio();

  // Show SweetAlert2 popup with scanned text
  const alertPromise = Swal.fire({
      icon: 'success',
      title: 'Scanned',
      text: `${id}`, // Display the scanned text
      timer: 10000, // Automatically closes after 10 seconds
      timerProgressBar: true,
      customClass: {
          popup: 'rounded',
          timerProgressBar: 'custom-timer-progress-bar',
          confirmButton: 'roundedBtn'
      }
  });

  // Perform fetch request
  fetch('https://script.google.com/macros/s/AKfycbxD7iXEFOCCOrX5Ryln_NrzptYjtWf6Ia_WJu-j8Gtfgv3cefqdHIg4KL9N-5U4n60d/exec', {
      method: 'POST',
      mode: 'no-cors',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: id }) // Ganti nama sesuai kebutuhan
  })
  .finally(() => {
      // Ensure scanning restarts after the alert and fetch are handled
      alertPromise.then(() => {
          startScanning();
      }).catch(() => {
          startScanning();
      });
  });

  // Stop scanning after successful read
  html5QrCode.stop();
}

function onScanError(errorMessage) {
    // Handle scan error (optional)function onScanSuccess(decodedText, decodedResult) {
  const id = decodedText; // Ambil ID dari QR code

  // Play audio
  playAudio();

  // Kirim ID ke server GAS
  fetch('https://script.google.com/macros/s/AKfycbxD7iXEFOCCOrX5Ryln_NrzptYjtWf6Ia_WJu-j8Gtfgv3cefqdHIg4KL9N-5U4n60d/exec', {
      method: 'POST',
      mode: 'no-cors',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ id: id })
  })
  .then(response => {
      // Jika perlu, tambahkan logika untuk memeriksa status respons
      console.log('Data sent to GAS:', id);
  })
  .catch(error => {
      console.error('Error sending data to GAS:', error);
  });

  // Fetch ke API get_kk.php untuk mendapatkan nama
  fetch('../api/get_kk.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json'
      },
      body: JSON.stringify({ code_id: id })
  })
  .then(response => response.json())
  .then(data => {
      if (data.success && data.kk_name) {
          Swal.fire({
              icon: 'success',
              title: `${data.kk_name}`,
              text: 'Checked',
              timer: 10000,
              timerProgressBar: true,
              customClass: {
                  popup: 'rounded',
                  timerProgressBar: 'custom-timer-progress-bar',
                  confirmButton: 'roundedBtn'
              },
              willClose: startScanning // Mulai kembali pemindaian setelah alert ditutup
          });
      } else {
          Swal.fire({
              icon: 'error',
              title: 'Not Found',
              text: 'No record found for the scanned ID.',
              confirmButton: 'OK',
              willClose: startScanning // Mulai kembali pemindaian setelah alert ditutup
          });
      }
  })
  .catch(error => {
      console.error('Error fetching data:', error);
      Swal.fire({
          icon: 'error',
          title: 'Fetch Error',
          text: 'Could not retrieve data from server.',
          confirmButton: 'OK',
          willClose: startScanning // Mulai kembali pemindaian setelah alert ditutup
      });
  });

  // Stop scanning after successful read
  html5QrCode.stop();
}

  function onScanError(errorMessage) {
      // Handle scan error (optional)
      console.warn(`Scan error: ${errorMessage}`);
  }

  function startScanning() {
      // Initialize the QR code scanner
      html5QrCode.start(
          { facingMode: "environment" }, // Use rear camera
          {
              fps: 20, // Frame rate
              qrbox: 200 // QR code scanning box size
          },
          onScanSuccess,
          onScanError
      );
  }

  // Initialize the QR code scanner instance
  const html5QrCode = new Html5Qrcode("qr-reader");

  // Start scanning with the camera
  startScanning();
  
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