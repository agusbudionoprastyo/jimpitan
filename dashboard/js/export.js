// document.getElementById('reportBtn').addEventListener('click', async function() {
//     // const month = document.getElementById('month').value;
//     // const year = document.getElementById('year').value;
//     // const response = await fetch(`../dashboard/api/fetch_reports.php?month=${month}&year=${year}`); // Ganti dengan path file PHP Anda
//     // const data = await response.json();
//         // Ambil nilai dari monthPicker
//         const monthPicker = document.getElementById('monthPicker').value; // Format "Oct 2024"
//         if (!monthPicker) {
//             alert('Please select a month');
//             return;
//         }
        
//         // Split the string by space to get the month name and year
//         const [month, year] = monthPicker.split(' ');
        
//         // Convert the month name to a number
//         const monthNumber = new Date(Date.parse(month + " 1, 2024")).getMonth() + 1; // Adding 1 because getMonth() returns 0-11
//         if (isNaN(monthNumber) || monthNumber < 1 || monthNumber > 12) {
//             alert('Invalid month selected');
//             return;
//         }
        
//         console.log('Selected Month:', monthNumber);
//         console.log('Selected Year:', year);
        
//         const response = await fetch(`../dashboard/api/fetch_reports.php?month=${monthNumber}&year=${year}`);
//         const data = await response.json();
        
    

//     const workbook = new ExcelJS.Workbook();
//     const worksheet = workbook.addWorksheet('Reports');

//     // Tambahkan judul di A1
//     worksheet.getCell('A1').value = 'Jimpitan - RT07 Salatiga';
//     worksheet.getCell('A1').alignment = { horizontal: 'left', vertical: 'middle' };
//     worksheet.getCell('A1').font = { bold: true, size: 14 }; // Set font bold dan ukuran

//     // Tambahkan bulan dan tahun di A2
//     const monthNames = [
//         "January", "February", "March", "April", "May", "June",
//         "July", "August", "September", "October", "November", "December"
//     ];
//     const monthYear = `${monthNames[monthNumber- 1]} ${year}`; // Menggunakan nama bulan penuh
//     worksheet.getCell('A2').value = monthYear;
//     worksheet.getCell('A2').alignment = { horizontal: 'left', vertical: 'middle' };


//     // Baris A3 dikosongkan
//     worksheet.getCell('A3').value = '';

//     // Set header di baris ke-4 tanpa 'Nama' dan 'Total'
//     const headerRow = worksheet.addRow(['', ...Array.from({ length: 31 }, (_, i) => i + 1), '']);

//     // Atur warna latar belakang header
//     headerRow.eachCell((cell) => {
//         cell.fill = {
//             type: 'pattern',
//             pattern: 'solid',
//             fgColor: { argb: 'D8D2C2' } // bg header
//         };
//         cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Align center untuk header
        
//         // Set font bold
//         cell.font = { bold: true, color: { argb: '000000' } }; // Font bold dan warna hitam

//         // Menambahkan border untuk header
//         cell.border = {
//             top: { style: 'thin', color: { argb: 'FF000000' } },
//             left: { style: 'thin', color: { argb: 'FF000000' } },
//             bottom: { style: 'thin', color: { argb: 'FF000000' } },
//             right: { style: 'thin', color: { argb: 'FF000000' } }
//         };
        
//         // Format cell as number if it’s not empty
//         if (cell.value !== '') {
//             cell.numFmt = '0'; // Set number format
//         }
//     });

//     function setMergedCell(worksheet, cellRange, value) {
//         worksheet.mergeCells(cellRange);
//         const cell = worksheet.getCell(cellRange.split(':')[0]);
//         cell.value = value;
//         cell.alignment = { horizontal: 'center', vertical: 'middle' }; // Center alignment
//         cell.font = { bold: true }; // Bold font
//         cell.fill = { 
//             type: 'pattern', 
//             pattern: 'solid', 
//             fgColor: { argb: 'D8D2C2' } // Background color
//         };
//         worksheet.getCell(cellRange).border = {
//             top: { style: 'thin', color: { argb: 'FF000000' } },
//             left: { style: 'thin', color: { argb: 'FF000000' } },
//             bottom: { style: 'thin', color: { argb: 'FF000000' } },
//             right: { style: 'thin', color: { argb: 'FF000000' } }
//         };
//     }
    
//     // Set up cells
//     setMergedCell(worksheet, 'B3:AF3', 'Tanggal');
//     setMergedCell(worksheet, 'A3:A4', 'Nama');
//     setMergedCell(worksheet, 'AG3:AG4', 'Total');    

//     // Atur lebar kolom
//     worksheet.getColumn(1).width = 25; // Lebar kolom kk_name
//     for (let i = 2; i <= 32; i++) { // Kolom 2 sampai 32 untuk hari 1-31 + Total
//         worksheet.getColumn(i).width = 6; // Lebar kolom 5 karakter
//     }

//     data.forEach((row, index) => {
//         const rowData = [row.kk_name];
//         let total = 0;
    
//         for (let i = 1; i <= 31; i++) {
//             const value = row[i] !== null ? Number(row[i]) : ''; // Konversi nilai menjadi angka, jika ada
//             rowData.push(value);
//             if (value) {
//                 total += value; // Hitung total jika ada nilai
//             }
//         }
    
//         rowData.push(total > 0 ? total : ''); // Tambahkan total sebagai angka, kosong jika 0
//         const newRow = worksheet.addRow(rowData);
    
//         // Tentukan warna latar belakang berdasarkan indeks baris
//         let fillColor = (index % 2 === 0) ? 'FFFFFFFF' : 'F5F5F5'; // Putih untuk baris genap, abu-abu untuk baris ganjil
    
//         // Jika ini adalah baris terakhir, atur warna latar belakang khusus
//         if (index === data.length - 1) {
//             fillColor = 'D8D2C2'; // Warna latar belakang khusus untuk baris terakhir
//         }
    
//         // Atur style untuk setiap sel di baris data
//         newRow.eachCell((cell, colNumber) => {
//             cell.alignment = { horizontal: 'middle', vertical: 'middle' }; // Align center
//             cell.border = {
//                 top: { style: 'thin', color: { argb: 'FF000000' } },
//                 left: { style: 'thin', color: { argb: 'FF000000' } },
//                 bottom: { style: 'thin', color: { argb: 'FF000000' } },
//                 right: { style: 'thin', color: { argb: 'FF000000' } }
//             };
//             cell.fill = {
//                 type: 'pattern',
//                 pattern: 'solid',
//                 fgColor: { argb: fillColor } // Set warna latar belakang
//             };
    
//             // Jika ini adalah kolom terakhir, set bold dan latar belakang
//             if (colNumber === rowData.length) {
//                 cell.font = { bold: true }; // Bold untuk kolom terakhir
//                 cell.width = 8;
//                 cell.fill = {
//                     type: 'pattern',
//                     pattern: 'solid',
//                     fgColor: { argb: 'EDE8DC' } // Latar belakang untuk kolom terakhir
//                 };
//             }
//         });
//     });
    
//     // Untuk baris terakhir, set bold untuk semua sel
//     const lastRow = worksheet.lastRow;
//     lastRow.eachCell((cell) => {
//         cell.font = { bold: true }; // Bold untuk baris terakhir
//     });

//     // Ekspor ke XLSX
//     const monthName = monthNames[monthNumber- 1]; // Ambil nama bulan penuh
//     const fileName = `Report_${monthName}_${year}.xlsx`; // Format nama file

//     workbook.xlsx.writeBuffer().then((buffer) => {
//         const blob = new Blob([buffer], { type: 'application/octet-stream' });
//         const url = URL.createObjectURL(blob);
//         const a = document.createElement('a');
//         a.href = url;
//         a.download = fileName; // Gunakan nama file yang sesuai
//         document.body.appendChild(a);
//         a.click();
//         document.body.removeChild(a);
//         URL.revokeObjectURL(url);
//     });

// });

document.getElementById('reportBtn').addEventListener('click', async function() {
    const monthPicker = document.getElementById('monthPicker').value; // Format "Oct 2024"
    if (!monthPicker) {
        alert('Please select a month');
        return;
    }
    
    const [month, year] = monthPicker.split(' ');
    const monthNumber = new Date(Date.parse(month + " 1, 2024")).getMonth() + 1;
    if (isNaN(monthNumber) || monthNumber < 1 || monthNumber > 12) {
        alert('Invalid month selected');
        return;
    }
    
    const response = await fetch(`../dashboard/api/fetch_reports.php?month=${monthNumber}&year=${year}`);
    const data = await response.json();

    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Reports');

    worksheet.getCell('A1').value = 'Jimpitan - RT07 Salatiga';
    worksheet.getCell('A1').alignment = { horizontal: 'left', vertical: 'middle' };
    worksheet.getCell('A1').font = { bold: true, size: 14 };

    const monthNames = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];
    const monthYear = `${monthNames[monthNumber - 1]} ${year}`;
    worksheet.getCell('A2').value = monthYear;
    worksheet.getCell('A2').alignment = { horizontal: 'left', vertical: 'middle' };
    worksheet.getCell('A2').font = { bold: true, size: 12 };

    worksheet.getCell('A3').value = '';


    // Determine the number of days in the selected month
    const daysInMonth = new Date(year, monthNumber, 0).getDate();

    const headerRow = worksheet.addRow(['Nama', ...Array.from({ length: daysInMonth }, (_, i) => i + 1), 'Total']);

    // Merging the "Nama" cell with the cell above it
    // worksheet.mergeCells(`A4:A5`);

    // Merging from B3 to the last cell corresponding to the last day of the month
    const lastColumn = String.fromCharCode(66 + daysInMonth - 1); // 'B' is 66 in ASCII
    const mergeRange = `B3:${lastColumn}3`; // Merging in row 3

    // Check if the cells are already merged to avoid errors
    if (!worksheet.mergedCells.includes(mergeRange)) {
        worksheet.mergeCells(mergeRange);
    }
    
    headerRow.eachCell((cell) => {
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'D8D2C2' }
        };
        cell.alignment = { horizontal: 'center', vertical: 'middle' };
        cell.font = { bold: true, color: { argb: '000000' } };
        cell.border = {
            top: { style: 'thin', color: { argb: 'FF000000' } },
            left: { style: 'thin', color: { argb: 'FF000000' } },
            bottom: { style: 'thin', color: { argb: 'FF000000' } },
            right: { style: 'thin', color: { argb: 'FF000000' } }
        };
        if (cell.value !== '') {
            cell.numFmt = '0';
        }
    });
    

    // function setMergedCell(worksheet, cellRange, value) {
    //     worksheet.mergeCells(cellRange);
    //     const cell = worksheet.getCell(cellRange.split(':')[0]);
    //     cell.value = value;
    //     cell.alignment = { horizontal: 'center', vertical: 'middle' };
    //     cell.font = { bold: true };
    //     cell.fill = {
    //         type: 'pattern',
    //         pattern: 'solid',
    //         fgColor: { argb: 'D8D2C2' }
    //     };
    //     worksheet.getCell(cellRange).border = {
    //         top: { style: 'thin', color: { argb: 'FF000000' } },
    //         left: { style: 'thin', color: { argb: 'FF000000' } },
    //         bottom: { style: 'thin', color: { argb: 'FF000000' } },
    //         right: { style: 'thin', color: { argb: 'FF000000' } }
    //     };
    // }

    // setMergedCell(worksheet, 'B3:AF3', 'Tanggal');
    // setMergedCell(worksheet, 'A3:A4', 'Nama');
    // setMergedCell(worksheet, 'AG3:AG4', 'Total');

    worksheet.getColumn(1).width = 25;
    for (let i = 2; i <= (daysInMonth + 1); i++) {
        worksheet.getColumn(i).width = 6; // Set width for days
    }

    data.forEach((row, index) => {
        const rowData = [row.kk_name];
        let total = 0;

        for (let i = 1; i <= daysInMonth; i++) {
            const value = row[i] !== null ? Number(row[i]) : '';
            rowData.push(value);
            if (value) {
                total += value;
            }
        }

        rowData.push(total > 0 ? total : '');
        const newRow = worksheet.addRow(rowData);

        let fillColor = (index % 2 === 0) ? 'FFFFFFFF' : 'F5F5F5';

        if (index === data.length - 1) {
            fillColor = 'D8D2C2';
        }

        newRow.eachCell((cell, colNumber) => {
            cell.alignment = { horizontal: 'middle', vertical: 'middle' };
            cell.border = {
                top: { style: 'thin', color: { argb: 'FF000000' } },
                left: { style: 'thin', color: { argb: 'FF000000' } },
                bottom: { style: 'thin', color: { argb: 'FF000000' } },
                right: { style: 'thin', color: { argb: 'FF000000' } }
            };
            cell.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: fillColor }
            };

            if (colNumber === rowData.length) {
                cell.font = { bold: true };
                cell.width = 8;
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'EDE8DC' }
                };
            }
        });
    });

    const lastRow = worksheet.lastRow;
    lastRow.eachCell((cell) => {
        cell.font = { bold: true };
    });

    const monthName = monthNames[monthNumber - 1];
    const fileName = `Report_${monthName}_${year}.xlsx`;

    workbook.xlsx.writeBuffer().then((buffer) => {
        const blob = new Blob([buffer], { type: 'application/octet-stream' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = fileName;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
    });
});
