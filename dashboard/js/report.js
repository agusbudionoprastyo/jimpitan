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
    // Membuat workbook dan worksheet
    const wb = XLSX.utils.book_new();
    const wsData = [];

    // Mendapatkan tanggal awal dan akhir bulan ini
    const now = new Date();
    const startDate = new Date(now.getFullYear(), now.getMonth(), 1);
    const endDate = new Date(now.getFullYear(), now.getMonth() + 1, 0);

    // Menyimpan tanggal dalam format 'dd/mm/yyyy'
    const dateRow = [];
    for (let d = startDate; d <= endDate; d.setDate(d.getDate() + 1)) {
        dateRow.push((new Date(d)).toLocaleDateString('en-GB'));
    }

    // Menambahkan header di baris ketiga, termasuk tanggal
    wsData.push(['No', 'Nama KK', ...dateRow]);

    // Mengambil data dari database
    const response = await fetch('../api/fetch_reports.php');
    const reports = await response.json();

    // Menambahkan data "Nama KK" dan "Nominal" sesuai tanggal
    const reportMap = {};
    reports.forEach(report => {
        const date = new Date(report.jimpitan_date).toLocaleDateString('en-GB');
        reportMap[date] = report.nominal; // Memetakan tanggal ke nominal
    });

    // Menambahkan data ke worksheet
    for (let i = 1; i <= reports.length; i++) {
        const nominal = reportMap[dateRow[i - 1]] || ''; // Ambil nominal sesuai tanggal, jika ada
        wsData.push([i, `Nama KK ${i}`, ...dateRow.map(date => (date === dateRow[i - 1] ? nominal : ''))]);
    }

    // Mengonversi data menjadi worksheet
    const ws = XLSX.utils.aoa_to_sheet(wsData);
    XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');

    // Mengunduh file Excel
    XLSX.writeFile(wb, 'hello_world.xlsx');
});
    