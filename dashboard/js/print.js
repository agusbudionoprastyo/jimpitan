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
				var namaGeng = firstThreeWords.join(' ');

				var nomorBIB = row.cells[1].textContent.trim();
				selectedEntries.push({ namaGeng: namaGeng, nomorBIB: nomorBIB });
			});

			printSelectedEntries(selectedEntries);
		});
	});

		function printSelectedEntries(entries) {
		// Buat elemen iframe secara dinamis
		var iframe = document.createElement('iframe');
		// Tetapkan beberapa gaya untuk iframe
		iframe.style.position = 'absolute';
		iframe.style.left = '-9999px'; // Mengatur posisi di luar layar
		iframe.style.width = '200mm'; // Menetapkan lebar iframe sesuai gaya label
		iframe.style.height = '145mm'; // Menetapkan tinggi iframe sesuai gaya label
		iframe.style.border = 'none'; // Menghapus border iframe
		iframe.style.display = 'none'; // Sembunyikan iframe dari tampilan pengguna
		document.body.appendChild(iframe);

		var iframeDoc = iframe.contentWindow.document;
		iframeDoc.open();
		iframeDoc.write(`
		<!DOCTYPE html>
		<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<title>Print Qr</title>
		<style>
		@font-face {
						font-family: 'Adumu'; /* Nama font yang akan digunakan */
						src: url('assets/Adumu.ttf') format('truetype'); /* Lokasi file TTF */
					}

		  @page { 
			size: A4;
			margin:3.5mm;
		  }

		  body {
			padding: 0;
			flex-direction: column; /* Tampilkan konten secara vertikal */
			justify-content: center; 
			width: 100%; /* Lebar penuh untuk memastikan konten mengisi halaman */
			height: 100%; /* Setengah tinggi halaman untuk setiap konten */
			color: white !important;
			-webkit-print-color-adjust: exact;
		  }

		  .container {
			height: 145mm; /* Ketinggian halaman */
			width: 200mm; /* Lebar halaman */
			position: relative;
			border: 1px solid grey;
			page-break-after: always; /* Force page break after each container */
		  }

		  .img {
				max-width: 100%;
				height: auto;
				display: block;
				position: absolute;
				top: 50%;
				left: 50%;
				transform: translate(-50%, -50%);
				z-index: -1; /* Letakkan di belakang konten utama */
			}		

		  .NameGroup {
			position: absolute;
			top: 50%; /* Adjust vertically */
			left: 50%;
			transform: translate(-50%, -50%);
			text-align: center;
			font-size: 88px;
			font-family: 'Adumu';
			line-height: 88px;
			letter-spacing: 10px;
		  }

		  .BIBText {
			position: absolute;
			top: 95mm; /* Adjust vertically */
			left: 2mm;
			text-align: center;
			font-size: 45px;
			font-family: 'Adumu';
			letter-spacing: 5px;
		  }
		</style>
	</head>
	<body>
`);

entries.forEach(function(entry, index) {
    var qrCodeDiv = document.createElement('div');
    // Setting style for qrCodeDiv...
	qrCodeDiv.style.position = 'absolute'; // atau 'relative' tergantung dari kebutuhan layout
	qrCodeDiv.style.top = '88mm'; // Atur posisi dari atas
	qrCodeDiv.style.right = '2mm'; // Atur posisi dari left

    new QRCode(qrCodeDiv, {
        text: entry.nomorBIB,
        width: 80,
        height: 80,
        colorDark: '#000000',
        colorLight: 'rgba(255, 255, 255, 0)',
        correctLevel: QRCode.CorrectLevel.H
    });

    // Membuat container untuk setiap entri
    var containerDiv = document.createElement('div');
    containerDiv.classList.add('container');
    containerDiv.innerHTML = `
        // <img src="assets/bg.png" class="img">
        <div class="NameGroup">${entry.namaGeng}</div>
        <div class="BIBText">${entry.nomorBIB}</div>
    `;
    containerDiv.appendChild(qrCodeDiv);

	iframeDoc.body.appendChild(containerDiv);

    // // Tambahkan page break setelah setiap dua konten
    // if ((index + 1) % 2 === 0 && index !== entries.length - 1) {
    //     var pageBreakDiv = document.createElement('div');
    //     pageBreakDiv.style.pageBreakAfter = 'always';
    //     iframeDoc.body.appendChild(pageBreakDiv);
    // }
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