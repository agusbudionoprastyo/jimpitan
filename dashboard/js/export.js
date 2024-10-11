document.getElementById('reportBtn').addEventListener('click', async function() {
    const response = await fetch('../dashboard/api/fetch_reports.php'); // Ganti dengan path file PHP Anda
    const data = await response.json();

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Reports');

    // Tambahkan judul di A1
    worksheet.getCell('A1').value = 'Jimpitan - RT07 Salatiga';
    worksheet.getCell('A1').alignment = { horizontal: 'left', vertical: 'middle' };
    worksheet.getCell('A1').font = { bold: true, size: 14 }; // Set font bold dan ukuran

    // Tambahkan bulan dan tahun di A2
    const currentDate = new Date();
    const monthYear = currentDate.toLocaleString('default', { month: 'long', year: 'numeric' });
    worksheet.getCell('A2').value = monthYear;
    worksheet.getCell('A2').alignment = { horizontal: 'left', vertical: 'middle' };

    // Baris A3 dikosongkan
    worksheet.getCell('A3').value = '';

    // Set header di baris ke-5
    const headerRow = worksheet.addRow(['Nama', ...Array.from({ length: 31 }, (_, i) => (i + 1).toString()), 'Total']);

        // Atur warna latar belakang header menjadi biru dan border
        headerRow.eachCell((cell) => {
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: '8EACCD' } // bg header
            };
            cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Align center untuk header
            
            // Set font bold
            cell.font = { bold: true, color: { argb: '000000' } }; // Font bold dan warna putih

            // Menambahkan border untuk header
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
        });


    // Tambahkan data dengan warna baris bergantian
    data.forEach((row, index) => {
        const rowData = [row.kk_name];
        let total = 0;

        for (let i = 1; i <= 31; i++) {
            const value = row[i] !== null ? Number(row[i]) : ''; // Konversi nilai menjadi angka, jika ada
            rowData.push(value);
            if (value) {
                total += value; // Hitung total jika ada nilai
            }
        }

        rowData.push(total > 0 ? total : ''); // Tambahkan total sebagai angka, kosong jika 0
        const newRow = worksheet.addRow(rowData);

        // Tentukan warna latar belakang berdasarkan indeks baris
        const fillColor = (index % 2 === 0) ? 'FFFFFFFF' : 'FFCCCCCC'; // Putih untuk baris genap, abu-abu untuk baris ganjil

        // Atur style untuk setiap sel di baris data
        newRow.eachCell((cell) => {
            cell.alignment = { horizontal: 'middle', vertical: 'middle' }; // Align center
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: fillColor } // Set warna latar belakang
            };
        });
    });

    // Atur lebar kolom
    worksheet.getColumn(1).width = 25; // Lebar kolom kk_name
    for (let i = 2; i <= 33; i++) { // Kolom 2 sampai 33 untuk hari 1-31 + Total
        worksheet.getColumn(i).width = 5; // Lebar kolom 5 karakter
    }

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
