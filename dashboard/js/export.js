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


    // Menentukan jumlah hari dalam bulan yang dipilih
    const daysInMonth = new Date(year, monthNumber, 0).getDate();

    // Membuat baris header
    const headerRow = worksheet.addRow(['', ...Array.from({ length: daysInMonth }, (_, i) => i + 1), '']);

    // Fungsi untuk mengonversi indeks kolom menjadi huruf kolom
    function getColumnLetter(columnIndex) {
        let temp;
        let letter = '';
        while (columnIndex > 0) {
            temp = (columnIndex - 1) % 26;
            letter = String.fromCharCode(temp + 65) + letter;
            columnIndex = Math.floor((columnIndex - temp) / 26);
        }
        return letter;
    }

    // Menghitung huruf kolom terakhir berdasarkan jumlah hari
    const lastColumnIndex = daysInMonth + 1; // +1 untuk 'Total'
    const lastColumnLetter = getColumnLetter(lastColumnIndex);

    // Menghitung huruf kolom terakhir berdasarkan jumlah hari
    const totalColumnIndex = daysInMonth + 2; // +1 untuk 'Total'
    const totalColumnLetter = getColumnLetter(totalColumnIndex);

    setMergedCell(worksheet, `B3:${lastColumnLetter}3`, 'Tanggal');
    setMergedCell(worksheet, 'A3:A4', 'Nama');
    setMergedCell(worksheet, `${totalColumnLetter}3:${totalColumnLetter}4`, 'Total');

    function setMergedCell(worksheet, cellRange, value) {
        worksheet.mergeCells(cellRange);
        const cell = worksheet.getCell(cellRange.split(':')[0]);
        cell.value = value;
        cell.alignment = { horizontal: 'center', vertical: 'middle' };
        cell.font = { bold: true };
        cell.fill = {
            type: 'pattern',
            pattern: 'solid',
            fgColor: { argb: 'D8D2C2' }
        };
        worksheet.getCell(cellRange).border = {
            top: { style: 'thin', color: { argb: 'FF000000' } },
            left: { style: 'thin', color: { argb: 'FF000000' } },
            bottom: { style: 'thin', color: { argb: 'FF000000' } },
            right: { style: 'thin', color: { argb: 'FF000000' } }
        };
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

        let fillColor = (index % 2 === 0) ? 'FFFFFFFF' : 'E4E0E1';

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
                    fgColor: { argb: 'D8D2C2' }
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
