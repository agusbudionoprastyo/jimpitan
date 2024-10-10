document.getElementById('reportBtn').addEventListener('click', async function() {
    const response = await fetch('../dashboard/api/fetch_reports.php'); // Ganti dengan path file PHP Anda
    const data = await response.json();

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Reports');

    // Tambahkan judul di A1
    worksheet.getCell('A1').value = 'Jimpitan - RT07 Salatiga';
    worksheet.getCell('A1').alignment = { horizontal: 'center', vertical: 'middle' };
    worksheet.getCell('A1').font = { bold: true, size: 14 }; // Set font bold dan ukuran

    // Tambahkan bulan dan tahun di A2
    const currentDate = new Date();
    const monthYear = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
    worksheet.getCell('A2').value = monthYear;
    worksheet.getCell('A2').alignment = { horizontal: 'center', vertical: 'middle' };

    // Set header di baris ke-3
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
    worksheet.getColumn(1).width = 15; // Lebar kolom report_id
    for (let i = 2; i <= 32; i++) { // Kolom 2 sampai 32 untuk hari 1-31
        worksheet.getColumn(i).width = 5; // Lebar kolom 5 karakter
    }

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
