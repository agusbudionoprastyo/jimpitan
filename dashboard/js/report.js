$(document).ready(function() {
	// table initialize
	var table = new DataTable('#example', {
		searching: true, // Aktifkan pencarian
		order: [[1, 'asc']], // Urutkan berdasarkan kolom kedua (indeks 1), urutan ascending
		columnDefs: [
			{ 
				"orderable": false, 
				"targets": [0] // Disable ordering for the third column (index 0)
			},
			{ 
				"targets": 1,  // Kolom ke-2 (indeks mulai dari 0)
				"className": "text-left" 
			}
		]
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