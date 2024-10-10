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
    
    document.getElementById('reportBtn').addEventListener('click', async function() {
        try {
            const response = await fetch('../dashboard/api/fetch_reports.php'); // Ganti dengan path file PHP Anda
            const data = await response.json();
    
            // Siapkan data untuk XLSX
            const worksheetData = [];
            // Set header
            worksheetData.push(['report_id', ...Array.from({ length: 31 }, (_, i) => (i + 1).toString())]);
    
            // Tambahkan baris data
            data.forEach(row => {
                worksheetData.push([row.report_id, ...Array.from({ length: 31 }, (_, i) => row[i + 1] || 0)]);
            });
    
            // Buat worksheet dari data
            const worksheet = XLSX.utils.aoa_to_sheet(worksheetData);
            
            // Atur lebar kolom (1 cm ≈ 37.79 piksel)
            const colWidth = 27.79; // Lebar kolom dalam piksel
            worksheet['!cols'] = [
                { wpx: 80 }, // Lebar untuk report_id
                ...Array(31).fill({ wpx: colWidth }) // Lebar untuk hari 1 sampai 31
            ];
    
            // Menambahkan border ke setiap sel
            const range = XLSX.utils.decode_range(worksheet['!ref']); // Dapatkan rentang worksheet
            for (let R = range.s.r; R <= range.e.r; ++R) {
                for (let C = range.s.c; C <= range.e.c; ++C) {
                    const cell = worksheet[XLSX.utils.encode_cell({ r: R, c: C })];
                    if (!cell) continue; // Lewati sel kosong
    
                    // Inisialisasi gaya sel jika belum ada
                    if (!cell.s) cell.s = {};
                    // Set border
                    cell.s.border = {
                        top: { style: 'thin', color: { rgb: '000000' } },
                        bottom: { style: 'thin', color: { rgb: '000000' } },
                        left: { style: 'thin', color: { rgb: '000000' } },
                        right: { style: 'thin', color: { rgb: '000000' } },
                    };
                }
            }
    
            // Buat workbook dan tambahkan worksheet
            const workbook = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(workbook, worksheet, 'Reports');
    
            // Ekspor ke XLSX
            XLSX.writeFile(workbook, 'reports.xlsx');
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    });
    