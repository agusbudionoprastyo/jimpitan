$(document).ready(function() {
	// // table initialize
	// var table = new DataTable('#example', {
	// 	searching: true, // Aktifkan pencarian
	// 	order: [[1, 'asc']], // Urutkan berdasarkan kolom kedua (indeks 1), urutan ascending
	// 	columnDefs: [
	// 		{ 
	// 			"orderable": false, 
	// 			"targets": [0] // Disable ordering for the third column (index 0)
	// 		},
	// 		{ 
	// 			"targets": 1,  // Kolom ke-2 (indeks mulai dari 0)
	// 			"className": "text-left" 
	// 		}
	// 	]
	// });

    // Table initialize
    var table = new DataTable('#example', {
        pageLength: 10, // Set the default number of records per page to 10
        lengthMenu: [10, 25, 50, 100], // Options for the dropdown
        searching: true, // Enable searching
        order: [[1, 'asc']], // Sort by the second column (index 1), ascending order
        columnDefs: [
            { 
                orderable: false, 
                targets: [0] // Disable ordering for the first column (index 0)
            },
            { 
                targets: 1,  // Second column (index starting from 0)
                className: "text-left" 
            }
        ],
        language: {
            lengthMenu: "Display _MENU_ records per page",
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