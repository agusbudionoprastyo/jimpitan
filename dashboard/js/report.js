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
        const response = await fetch('../dashboard/api/fetch_reports.php'); // Ganti dengan path file PHP Anda
        const data = await response.json();

        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Reports');

        // Set header
        const headerRow = worksheet.addRow(['report_id', ...Array.from({ length: 31 }, (_, i) => (i + 1).toString())]);

        // Atur alignment untuk header
        headerRow.eachCell((cell) => {
            cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Align center
        });

        // Tambahkan data
        data.forEach(row => {
            const newRow = worksheet.addRow([row.report_id, ...Array.from({ length: 31 }, (_, i) => row[i + 1] || 0)]);
            
            // Atur alignment untuk setiap sel di baris data
            newRow.eachCell((cell) => {
                cell.alignment = { horizontal: 'middle', vertical: 'middle' }; // Align center
            });
        });

        // Atur lebar kolom
        worksheet.columns.forEach(column => {
            column.width = 5; // Lebar kolom 15 karakter
        });

        // Menambahkan border
        worksheet.eachRow((row) => {
            row.eachCell((cell) => {
                cell.border = {
                    top: { style: 'thin', color: { argb: 'FF000000' } },
                    left: { style: 'thin', color: { argb: 'FF000000' } },
                    bottom: { style: 'thin', color: { argb: 'FF000000' } },
                    right: { style: 'thin', color: { argb: 'FF000000' } }
                };
            });
        });

        // Ekspor ke XLSX
        workbook.xlsx.writeBuffer().then((buffer) => {
            const blob = new Blob([buffer], { type: 'application/octet-stream' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'reports.xlsx';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        });
    });