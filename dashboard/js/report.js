$(document).ready(function() {
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

    document.getElementById('reportBtn').addEventListener('click', function() {
        // Membuat workbook dan worksheet
        const wb = XLSX.utils.book_new();
        const wsData = [];
    
        // Menulis header
        wsData.push(['No', 'Nama KK']); // Kolom A dan B
    
        // Mendapatkan tanggal awal dan akhir bulan ini
        const now = new Date();
        const startDate = new Date(now.getFullYear(), now.getMonth(), 1); // Tanggal 1 bulan ini
        const endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0); // Tanggal terakhir bulan ini
    
        // Menyimpan tanggal dalam format 'dd/mm/yyyy' mulai dari kolom C
        const dateRow = [];
        for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
            dateRow.push((new Date(d)).toLocaleDateString('en-GB')); // Format dd/mm/yyyy
        }
    
        // Menambahkan baris tanggal di baris kedua, mulai dari kolom C
        const headerRow = new Array(2).fill(''); // Membuat dua kolom kosong untuk A dan B
        wsData.push(headerRow.concat(dateRow)); // Gabungkan dengan header yang kosong
    
        // Menambahkan data "No" dan "Nama KK" untuk setiap tanggal
        for (let i = 1; i <= dateRow.length; i++) {
            wsData.push([i, `Nama KK ${i}`]); // Kolom A dan B
        }
    
        // Mengonversi data menjadi worksheet
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
    
        // Mengunduh file Excel
        XLSX.writeFile(wb, 'hello_world.xlsx');
    });    
    