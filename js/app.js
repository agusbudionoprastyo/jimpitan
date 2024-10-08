let isScanning = false; // Track scanning state

// Function to show or hide the landscape blocker
function updateLandscapeBlocker() {
    let landscapeBlocker = document.getElementById('landscapeBlocker');
    if (landscapeBlocker) {
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
    if (audio) {
        audio.play().catch(function(error) {
            console.error('Error playing audio:', error);
        });
    }
}

function onScanSuccess(decodedText, decodedResult) {
    if (isScanning) return; // Prevent further scans if already scanning

    isScanning = true; // Set scanning state to true
    const id = decodedText;
    playAudio();

    // First, fetch KK name from your server
    fetch('/api/get_kk.php', {
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
                willClose: () => {
                    startScanning();
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Not Found',
                text: 'No record found for the scanned ID.',
                confirmButton: 'OK',
                willClose: () => {
                    startScanning();
                }
            });
        }
    })
    .catch(error => {
        console.error('Error fetching KK name:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'There was an error fetching data.',
            confirmButton: 'OK',
            willClose: () => {
                startScanning();
            }
        });
    })
    .finally(() => {
        // Now send the ID to Google Apps Script
        fetch('https://script.google.com/macros/s/AKfycbxD7iXEFOCCOrX5Ryln_NrzptYjtWf6Ia_WJu-j8Gtfgv3cefqdHIg4KL9N-5U4n60d/exec', {
            method: 'POST',
            mode: 'no-cors',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .catch(error => {
            console.error('Error sending data to GAS:', error);
        })
        .finally(() => {
            html5QrCode.stop();
            isScanning = false; // Reset scanning state after processing
        });
    });
}

function onScanError(errorMessage) {
    console.warn(`Scan error: ${errorMessage}`);
}

function startScanning() {
    if (isScanning) return; // Prevent starting a new scan if already scanning

    isScanning = true; // Set scanning state to true
    html5QrCode.start(
        { facingMode: "environment" },
        {
            fps: 20,
            qrbox: 200
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
