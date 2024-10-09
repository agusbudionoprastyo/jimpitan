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
			}
		]
	});
	});