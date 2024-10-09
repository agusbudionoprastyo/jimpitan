document.addEventListener('DOMContentLoaded', function() {
    const eventSource = new EventSource('../api/sse.php');
    const audio = document.getElementById('audio'); // Pastikan audio sudah didefinisikan di halaman HTML

    eventSource.onmessage = function(event) {
        const data = JSON.parse(event.data);

        // Update UI for top 5 fastest check-ins
        var medalList = document.getElementById('medal-list');
				// Contoh penggunaan:
				for (var i = 0; i < 6; i++) {
					var position = i + 1;
					var suffix = getRankSuffix(position);
					var listItem = medalList.children[i];
				
					if (data.fastest_checkin[i]) {
						listItem.innerHTML = '<p>' + position + suffix + ' ' + data.fastest_checkin[i].NAMA_GENG + '</p>' +
											 '<i class="bx bx-medal"></i>';
					} else {
						listItem.innerHTML = '<p>' + position + suffix + '</p>' +
											 '<i class="bx bx-medal"></i>';

					}
				}
                
                //
                // Panggil fungsi fetchDataAndRun untuk pertama kali
                fetchDataAndRun();

                // Function to fetch data and run
                function fetchDataAndRun() {
                    var xhr = new XMLHttpRequest();
                    xhr.open("GET", "../api/running_text.php", true);
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            var runnerNames = JSON.parse(xhr.responseText);
                            displayRunningText(runnerNames);
                            previousRunnerNames = runnerNames.slice(); // Simpan data runnerNames sebelumnya
                        }
                    };
                    xhr.send();
                }

                // Function to display running text
                function displayRunningText(runnerNames) {
                    var runningTextElement = document.getElementById('running-text');
                    if (runningTextElement) {
                        // Kosongkan konten sebelum menambahkan data baru
                        runningTextElement.innerHTML = "";

                        // Mengisi konten running text dengan nama-nama runner
                        runnerNames.forEach(function(name, index) {
                            // Gunakan index untuk menambahkan karakter atau spasi yang sesuai
                            var separator = (index === runnerNames.length - 1) ? "" : " • ";
                            runningTextElement.innerHTML += "<span class='running-text'>" + name + separator + "</span>";
                        });
                    }
                }
            //

		function getRankSuffix(position) {
			// Mendapatkan angka terakhir dari posisi (misalnya 1, 2, 3, dst)
			var lastDigit = position % 10;
			var secondLastDigit = Math.floor(position / 10) % 10;
		
			// Aturan dasar:
			if (secondLastDigit === 1) {
				return "<sup>th</sup>";
			} else {
				switch (lastDigit) {
					case 1:
						return "<sup>st</sup>";
					case 2:
						return "<sup>nd</sup>";
					case 3:
						return "<sup>rd</sup>";
					default:
						return "<sup>th</sup>";
				}
			}
		}

        // Update statistik
        document.getElementById('totalPeserta').innerText = data.total_peserta;
        document.getElementById('totalCheck').innerText = data.total_check;
        document.getElementById('totalUncheck').innerText = data.total_uncheck;

       // Ambil data terakhir dari localStorage
        const storedData = JSON.parse(localStorage.getItem('lastData'));

        // Bandingkan dengan data saat ini
        if (data.max_timestamp_data &&
            (!storedData ||
            data.max_timestamp_data.max_timestamp !== storedData.max_timestamp ||
            data.max_timestamp_data.NAMA_GENG !== storedData.NAMA_GENG ||
            data.max_timestamp_data.BIB_NUMBER !== storedData.BIB_NUMBER)) {
            
            // Update data terbaru di localStorage
            // localStorage.setItem('lastData', JSON.stringify(data.max_timestamp_data));

            // Memastikan tidak menampilkan SweetAlert pada inisialisasi pertama kali
            // if (storedData && storedData.max_timestamp) {
                playAudio();

                // Tampilkan notifikasi menggunakan SweetAlert2
                Swal.fire({
                    title: data.max_timestamp_data.NAMA_GENG + '\n' + data.max_timestamp_data.BIB_NUMBER,
                    html: `Telah Check In<br>Timestamp: ${data.max_timestamp_data.max_timestamp}`,
                    icon: 'info',
                    showConfirmButton: false, // Tidak ada tombol konfirmasi
                    timer: 10000, // Durasi notifikasi 10 detik
                    timerProgressBar: true, // Tampilkan progress bar
                });
            // }
            // Call the fetchData function to initiate the data fetching and table population
            fetchData();
        }

        // Simpan data terbaru di localStorage
        localStorage.setItem('lastData', JSON.stringify(data.max_timestamp_data));
        };

        eventSource.onerror = function(event) {
            console.error('Error dengan SSE:', event);
        };


    // Function to play audio
    function playAudio() {
        audio.play().catch(function(error) {
            console.error('Error playing audio:', error);
        });
    }
});

// Function to update current time
function updateCurrentTime() {
    var currentTimeElement = document.getElementById('current-time');
    var currentTime = new Date().toLocaleTimeString();
    currentTimeElement.textContent = currentTime;
}

// Update current time initially
updateCurrentTime();

// Update current time every second (1000 milliseconds)
setInterval(updateCurrentTime, 1000);

//

let data = []; // Variabel untuk menyimpan data dari server

// Function to populate the table with checked data
function populateCheckinTable(data) {
    const tableBody = document.getElementById('checkin-table-body');
    tableBody.innerHTML = ''; // Clear existing rows

    const rowsToShow = data.slice(startIndex, startIndex + rowsToShowCount);

    rowsToShow.forEach(entry => {
        const row = document.createElement('tr');

        const gengCell = document.createElement('td');
        gengCell.textContent = entry.NAMA_GENG;
        row.appendChild(gengCell);

        const bibCell = document.createElement('td');
        bibCell.textContent = entry.BIB_NUMBER;
        row.appendChild(bibCell);

        // Creating and appending cells for each data point
        const timestampCell = document.createElement('td');
        const timePart = entry.timestamp.split(' ')[1]; // Assuming timestamp format is "YYYY-MM-DD HH:MM:SS"
        timestampCell.textContent = timePart; // Display only the time part
        row.appendChild(timestampCell);

        // Add 'added' class to apply transition effect
        row.classList.add('added');

        // Append the row to the table body
        tableBody.appendChild(row);

        // Remove 'added' class after transition completes
        setTimeout(() => {
            row.classList.remove('added');
        }, 500); // Adjust timing to match transition duration (0.3s)

    });
}

// Function to fetch data from server and update table
function updateTable() {
    fetchData(); // Fetch updated data from server

    const tableBody = document.getElementById('checkin-table-body');
    tableBody.innerHTML = ''; // Clear existing rows

    const rowsToShow = data.slice(startIndex, startIndex + rowsToShowCount);

    rowsToShow.forEach(entry => {
        const row = document.createElement('tr');

        const gengCell = document.createElement('td');
        gengCell.textContent = entry.NAMA_GENG;
        row.appendChild(gengCell);

        const bibCell = document.createElement('td');
        bibCell.textContent = entry.BIB_NUMBER;
        row.appendChild(bibCell);

        // Creating and appending cells for each data point
        const timestampCell = document.createElement('td');
        const timePart = entry.timestamp.split(' ')[1]; // Assuming timestamp format is "YYYY-MM-DD HH:MM:SS"
        timestampCell.textContent = timePart; // Display only the time part
        row.appendChild(timestampCell);

        // Append the row to the table body
        tableBody.appendChild(row);

    });

    // Move startIndex to the next set of rows
    startIndex += rowsToShowCount;
    if (startIndex >= data.length) {
        startIndex = 0; // Reset startIndex if end of data is reached
    }
}

// Function to fetch data from server
function fetchData() {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', '../api/checked_data.php', true); // Adjust URL based on your server endpoint
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            data = JSON.parse(xhr.responseText); // Update global variable 'data' with fetched data
            populateCheckinTable(data); // Populate the table with fetched data
        }
    };
    xhr.send();
}

// Call fetchData function to initiate data fetching and table population
fetchData();

let startIndex = 0; // Index of the first row to display
const rowsToShowCount = 6; // Number of rows to display each time

// Call updateTable function initially
updateTable();

// Call updateTable every 15 seconds
setInterval(updateTable, 30000); // 10000 milliseconds = 10 seconds

// script.js