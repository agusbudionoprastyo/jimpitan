$(document).ready(function() {
    // Table initialize
    var table = new DataTable('#example', {
        pageLength: 10, // Set the default number of records per page to 10
        lengthMenu: [10, 25, 50, 100], // Options for the dropdown
        searching: true, // Enable searching
        order: [[2, 'desc']], // Sort by the third column (index 2), descanding order
        columnDefs: [
			{ 
				"targets": 0,  // Kolom ke-1 (indeks mulai dari 0)
				"className": "text-left" 
			},
			{ 
				"targets": 1,  // Kolom ke-2 (indeks mulai dari 0)
				"className": "text-center" 
			}
            ,
			{ 
				"targets": 2,  // Kolom ke-3 (indeks mulai dari 0)
				"className": "text-center" 
			},
			{ 
				"targets": 3,  // Kolom ke-4 (indeks mulai dari 0)
				"className": "text-center" 
			},
			{ 
				"targets": 4,  // Kolom ke-5 (indeks mulai dari 0)
				"className": "text-center" 
			},
			{ 
				"targets": 5,  // Kolom ke-6 (indeks mulai dari 0)
				"className": "text-center" 
			}
		],
        language: {
            lengthMenu: "_MENU_ Entri per halaman",
            zeroRecords: "No records found",
            info: "Showing page _PAGE_ of _PAGES_",
            infoEmpty: "No records available",
            infoFiltered: "(filtered from _MAX_ total records)"
        }
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