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

    // Set header di baris ke-4 tanpa 'Nama' dan 'Total'
    const headerRow = worksheet.addRow(['', ...Array.from({ length: 31 }, (_, i) => i + 1), '']);

    // Atur warna latar belakang header
    headerRow.eachCell((cell) => {
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'EDDFE0' } // bg header
        };
        cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Align center untuk header
        
        // Set font bold
        cell.font = { bold: true, color: { argb: '000000' } }; // Font bold dan warna hitam

        // Menambahkan border untuk header
        cell.border = {
            top: { style: 'thin', color: { argb: 'FF000000' } },
            left: { style: 'thin', color: { argb: 'FF000000' } },
            bottom: { style: 'thin', color: { argb: 'FF000000' } },
            right: { style: 'thin', color: { argb: 'FF000000' } }
        };
        
        // Format cell as number if it’s not empty
        if (cell.value !== '') {
            cell.numFmt = '0'; // Set number format
        }
    });
 
    function setMergedCell(worksheet, cellRange, value) {
        worksheet.mergeCells(cellRange);
        const cell = worksheet.getCell(cellRange.split(':')[0]);
        cell.value = value;
        cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Center alignment
        cell.font = { bold: true }; // Bold font
        cell.fill = { 
            type: 'pattern', 
            pattern: 'solid', 
            fgColor: { argb: 'EDDFE0' } // Background color
        };
        worksheet.getCell(cellRange).border = {
            top: { style: 'thin', color: { argb: 'FF000000' } },
            left: { style: 'thin', color: { argb: 'FF000000' } },
            bottom: { style: 'thin', color: { argb: 'FF000000' } },
            right: { style: 'thin', color: { argb: 'FF000000' } }
        };
    }
    
    // Set up cells
    setMergedCell(worksheet, 'B3:AF3', 'Tanggal');
    setMergedCell(worksheet, 'A3:A4', 'Nama');
    setMergedCell(worksheet, 'AG3:AG4', 'Total');    

    // // Tambahkan data dengan warna baris bergantian
    // data.forEach((row, index) => {
    //     const rowData = [row.kk_name];
    //     let total = 0;

    //     for (let i = 1; i <= 31; i++) {
    //         const value = row[i] !== null ? Number(row[i]) : ''; // Konversi nilai menjadi angka, jika ada
    //         rowData.push(value);
    //         if (value) {
    //             total += value; // Hitung total jika ada nilai
    //         }
    //     }

    //     rowData.push(total > 0 ? total : ''); // Tambahkan total sebagai angka, kosong jika 0
    //     const newRow = worksheet.addRow(rowData);

    //     // Tentukan warna latar belakang berdasarkan indeks baris
    //     const fillColor = (index % 2 === 0) ? 'FFFFFFFF' : 'F5F5F5'; // Putih untuk baris genap, abu-abu untuk baris ganjil

    //     // Atur style untuk setiap sel di baris data
    //     newRow.eachCell((cell) => {
    //         cell.alignment = { horizontal: 'middle', vertical: 'middle' }; // Align center
    //         cell.border = {
    //             top: { style: 'thin', color: { argb: 'FF000000' } },
    //             left: { style: 'thin', color: { argb: 'FF000000' } },
    //             bottom: { style: 'thin', color: { argb: 'FF000000' } },
    //             right: { style: 'thin', color: { argb: 'FF000000' } }
    //         };
    //         cell.fill = {
    //             type: 'pattern',
    //             pattern: 'solid',
    //             fgColor: { argb: fillColor } // Set warna latar belakang
    //         };
    //     });
    // });

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
        let fillColor = (index % 2 === 0) ? 'FFFFFFFF' : 'F5F5F5'; // Putih untuk baris genap, abu-abu untuk baris ganjil
    
        // Jika ini adalah baris terakhir, atur warna latar belakang khusus
        if (index === data.length - 1) {
            fillColor = 'EDDFE0'; // Warna latar belakang khusus untuk baris terakhir
        }
    
        // Atur style untuk setiap sel di baris data
        newRow.eachCell((cell, colNumber) => {
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
    
            // Jika ini adalah kolom terakhir, set bold dan latar belakang
            if (colNumber === rowData.length) {
                cell.font = { bold: true }; // Bold untuk kolom terakhir
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'EDDFE0' } // Latar belakang untuk kolom terakhir
                };
            }
        });
    });
    
    // Untuk baris terakhir, set bold untuk semua sel
    const lastRow = worksheet.lastRow;
    lastRow.eachCell((cell) => {
        cell.font = { bold: true }; // Bold untuk baris terakhir
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
