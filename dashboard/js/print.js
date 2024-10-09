$(document).ready(function() {
	// table initialize
	var table = new DataTable('#example', {
		searching: true, // Aktifkan pencarian
		order: [[1, 'asc']], // Urutkan berdasarkan kolom kedua (indeks 1), urutan ascending
		columnDefs: [
			{ 
				"orderable": false, 
				"targets": [2] // Disable ordering for the third column (index 0)
			},
			{ 
				"targets": 1,  // Kolom ke-2 (indeks mulai dari 0)
				"className": "text-center" 
			},
			{ 
				"targets": 2,  // Kolom ke-3 (indeks mulai dari 0)
				"className": "text-center" 
			}
		]
	});
	// Select All functionality
	$('#selectAllCheckbox').change(function() {
		var isChecked = $(this).prop('checked');
		$('.print-checkbox').prop('checked', isChecked);
	});

	// Handle individual checkbox change
	$('.print-checkbox').change(function() {
		var allChecked = $('.print-checkbox:checked').length === $('.print-checkbox').length;
		$('#selectAllCheckbox').prop('checked', allChecked);
	});

	// Event listener untuk "Enter" pada #search-input
	$('#search-input').keypress(function(event) {
		if (event.which === 13) {
			event.preventDefault(); // Mencegah form submit (jika ada)
			var searchText = $(this).val();
			table.search(searchText).draw();
		}
	});

	// Event listener untuk klik pada tombol .search-btn
	$('.search-btn').click(function(event) {
		var searchText = $('#search-input').val();
		table.search(searchText).draw();
	});

	// Event listener untuk klik pada tombol .clear-btn
	$('.clear-btn').click(function(event) {
		$('#search-input').val(''); // Mengosongkan nilai input pencarian
		table.search('').draw(); // Mereset pencarian pada tabel
		});	

	});

	 // Event listener untuk tombol Print Selected
	 document.addEventListener('DOMContentLoaded', function() {
		document.getElementById('printSelectedBtn').addEventListener('click', function() {
			var checkboxes = document.querySelectorAll('.print-checkbox:checked');
			if (checkboxes.length === 0) {
				alert('Select at least one entry to print.');
				return;
			}

			var selectedEntries = [];
			checkboxes.forEach(function(checkbox) {
				var row = checkbox.closest('tr');
				var text = row.cells[0].textContent.trim();
				// Potong teks menjadi array kata-kata
				var words = text.split(' ');

				// Ambil hanya 3 kata pertama
				var firstThreeWords = words.slice(0, 3);

				// Gabungkan 3 kata tersebut kembali dengan spasi di antara mereka
				var namaKK = firstThreeWords.join(' ');

				var codeID = row.cells[1].textContent.trim();
				selectedEntries.push({ namaKK: namaKK, codeID: codeID });
			});

			printSelectedEntries(selectedEntries);
		});
	});

	function printSelectedEntries(entries) {
		// Create an iframe element dynamically
		var iframe = document.createElement('iframe');
		iframe.style.position = 'absolute';
		iframe.style.left = '-9999px'; // Position outside the screen
		iframe.style.width = '210mm'; // Set width to A4
		iframe.style.height = '297mm'; // Set height to A4
		iframe.style.border = 'none'; // Remove border
		document.body.appendChild(iframe);
	
		var iframeDoc = iframe.contentWindow.document;
		iframeDoc.open();
		iframeDoc.write(`
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Print QR Codes</title>
			<style>
				@page { 
					size: A4;
					margin: 0; /* No margin for better label alignment */
				}
				body {
					display: flex;
					flex-wrap: wrap;
					justify-content: flex-start; /* Align items to the start */
					align-items: flex-start; /* Align items to the top */
					padding: 0;
					margin: 0;
					height: 100%;
					font-family: 'Adumu', sans-serif; /* Add your font */
				}
				.label {
					width: 100mm; /* Label width */
					height: 50mm; /* Label height */
					margin: 0; /* No margin */
					padding: 5mm; /* Optional padding */
					box-sizing: border-box; /* Include padding in width/height */
					border: 1px solid #000; /* Add border around each label */
					display: flex;
					flex-direction: column;
					justify-content: center; /* Center content vertically */
					align-items: center; /* Center content horizontally */
					position: relative;
					page-break-inside: avoid; /* Avoid page breaks inside labels */
				}
				.qrCode {
					margin: 0 auto;
				}
				.NamaKK {
					font-size: 18px; /* Adjust size as needed */
					text-align: center; /* Center text */
					margin-top: 5px; /* Space between QR and text */
				}
			</style>
		</head>
		<body>
		`);
	
		entries.forEach(function(entry) {
			var labelDiv = document.createElement('div');
			labelDiv.classList.add('label');
	
			var qrCodeDiv = document.createElement('div');
			qrCodeDiv.classList.add('qrCode');
	
			new QRCode(qrCodeDiv, {
				text: entry.codeID,
				width: 40, // Adjust QR size as needed
				height: 40,
				colorDark: '#000000',
				colorLight: 'rgba(255, 255, 255, 0)',
				correctLevel: QRCode.CorrectLevel.H
			});
	
			labelDiv.appendChild(qrCodeDiv);
			var NamaKKDiv = document.createElement('div');
			NamaKKDiv.className = 'NamaKK';
			NamaKKDiv.textContent = entry.namaKK;
	
			labelDiv.appendChild(NamaKKDiv);
			iframeDoc.body.appendChild(labelDiv);
		});
	
		iframeDoc.write(`
		</body>
		</html>
		`);
	
		iframeDoc.close();
	
		iframe.onload = function() {
			iframe.contentWindow.print();
			setTimeout(function() {
				document.body.removeChild(iframe);
			}, 100);
		};
	}			