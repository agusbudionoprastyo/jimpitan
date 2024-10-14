<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role'])) {
    header('Location: ../login.php'); // Redirect to login page
    exit;
}

// Check if user is admin
if ($_SESSION['user']['role'] !== 'admin') {
    header('Location: ../login.php'); // Redirect to unauthorized page
    exit;
}

// Include the database connection
include 'api/db.php';

// Prepare and execute the SQL statement
$stmt = $pdo->prepare("SELECT * FROM kas_umum ORDER BY date_trx ASC");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['tanggal'])) {
    $tanggal = htmlspecialchars($_POST['tanggal']); // Sanitize input
    echo "<div class='alert alert-success mt-3'>Tanggal yang dipilih: <strong>$tanggal</strong></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <title>Keuangan</title>
</head>
<body class="bg-gray-100">

    <!-- SIDEBAR -->
    <section id="sidebar" class="bg-white shadow-md w-64 h-screen fixed">
        <a href="#" class="brand p-4 flex items-center">
            <i class='bx bx-square-rounded'></i>
            <span class="text-xl font-semibold">Jimpitan</span>
        </a>
        <ul class="side-menu mt-4">
            <li><a href="index.php" class="block p-4">Dashboard</a></li>
            <li><a href="kk.php" class="block p-4">KK</a></li>
            <li><a href="report.php" class="block p-4">Report</a></li>
            <li class="active"><a href="#" class="block p-4">Keuangan</a></li>
        </ul>
        <ul class="side-menu mt-4">
            <li><a href="setting.php" class="block p-4">Settings</a></li>
            <li><a href="logout.php" class="block p-4">Logout</a></li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content" class="ml-64 p-4">
        <nav class="flex justify-between items-center bg-white p-4 shadow">
            <h1 class="text-xl font-semibold">Jimpitan - RT07 Salatiga</h1>
            <input type="search" id="search-input" placeholder="Search..." class="border border-gray-300 rounded p-2">
        </nav>

        <main class="mt-4">
            <div class="head-title flex justify-between items-center">
                <h1 class="text-2xl font-semibold">Keuangan</h1>
                <button type="button" class="bg-blue-500 text-white rounded p-2" onclick="toggleModal()">
                    Tambah Transaksi
                </button>
            </div>

            <div class="table-data mt-4">
                <table class="min-w-full bg-white shadow-md rounded">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Kode</th>
                            <th class="py-2 px-4 border-b">Tanggal</th>
                            <th class="py-2 px-4 border-b">Reff</th>
                            <th class="py-2 px-4 border-b">Keterangan</th>
                            <th class="py-2 px-4 border-b">Debet</th>
                            <th class="py-2 px-4 border-b">Kredit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($data) {
                            foreach ($data as $row): ?>
                                <tr>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row["coa_code"]); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row["date_trx"]); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row["reff"]); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($row["description"]); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo "Rp " . number_format(htmlspecialchars($row["debet"]), 0, ',', '.'); ?></td>
                                    <td class="py-2 px-4 border-b"><?php echo "Rp " . number_format(htmlspecialchars($row["kredit"]), 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; 
                        } else {
                            echo '<tr><td colspan="6" class="py-2 px-4 border-b text-center">No data available</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div id="inputModal" class="fixed inset-0 flex items-center justify-center z-50 hidden">
                <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-1/3">
                    <div class="modal-header flex justify-between items-center p-4 border-b">
                        <h5 class="text-lg font-semibold">Form Tambah Data</h5>
                        <button type="button" class="text-gray-500" onclick="toggleModal()">&times;</button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="dataForm">
                            <div class="mb-3">
                                <label for="tanggal" class="block text-sm font-medium">Tanggal</label>
                                <input type="text" id="datePicker" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Pilih Tanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="kode" class="block text-sm font-medium">Kode</label>
                                <input type="text" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="kode" name="kode" required>
                            </div>
                            <div class="mb-3">
                                <label for="dropdown" class="block text-sm font-medium">Reff:</label>
                                <select id="dropdown" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                                    <option value="">-- Pilih Opsi --</option>
                                    <option value="Debit">Debet</option>
                                    <option value="Kredit">Kredit</option>
                                </select>
                            </div>
                            <div class="mb-3 hidden" id="debitBox">
                                <label for="debitTextbox" class="block text-sm font-medium">Debit:</label>
                                <input type="text" id="debitTextbox" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Isi detail debit...">
                            </div>
                            <div class="mb-3 hidden" id="kreditBox">
                                <label for="kreditTextbox" class="block text-sm font-medium">Kredit:</label>
                                <input type="text" id="kreditTextbox" class="mt-1 block w-full border border-gray-300 rounded-md p-2" placeholder="Isi detail kredit...">
                            </div>
                            <button type="submit" class="mt-4 w-full bg-green-500 text-white font-semibold py-2 rounded-md">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

    <!-- jQuery (Optional for extra functionality) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        // Event listener untuk form submit
        $('#dataForm').on('submit', function(e) {
            e.preventDefault();  // Mencegah refresh halaman

            // Ambil data input dari form
            const nama = $('#nama').val();
            const umur = $('#umur').val();
            const alamat = $('#alamat').val();

            // Tampilkan data di konsol (bisa disesuaikan)
            console.log(`Nama: ${nama}, Umur: ${umur}, Alamat: ${alamat}`);

            // Reset form dan tutup modal
            $(this).trigger('reset');
            $('#inputModal').modal('hide');
        });
    </script>

    <script src="js/monthSelectPlugin.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.1/exceljs.min.js"></script>

    <script>
        $(document).ready(function() {
        // Table initialize
        var table = new DataTable('#example', {
            pageLength: 10, // Set the default number of records per page to 10
            lengthMenu: [10, 25, 50, 100], // Options for the dropdown
            searching: true, // Enable searching
            order: [[2, 'desc']], // Sort by the third column (index 2), descanding order
            columnDefs: [
                { 
                    "targets": 0,  // Kolom ke-1 (indeks mulai dari 0)
                    "className": "text-left" 
                },
                { 
                    "targets": 1,  // Kolom ke-2 (indeks mulai dari 0)
                    "className": "text-center" 
                }
                ,
                { 
                    "targets": 2,  // Kolom ke-3 (indeks mulai dari 0)
                    "className": "text-center" 
                },
                { 
                    "targets": 3,  // Kolom ke-4 (indeks mulai dari 0)
                    "className": "text-center" 
                },
                { 
                    "targets": 4,  // Kolom ke-5 (indeks mulai dari 0)
                    "className": "text-right" 
                },
                { 
                    "targets": 5,  // Kolom ke-6 (indeks mulai dari 0)
                    "className": "text-right" 
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
        // Inisialisasi Flatpickr untuk input tanggal
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d", // Format untuk tanggal
            onChange: function(selectedDates, dateStr) {
                filterTableByDate(dateStr); // Panggil fungsi filter saat tanggal berubah
            }
        });

        // Fungsi untuk menyaring baris tabel berdasarkan tanggal yang dipilih
        function filterTableByDate(selectedDate) {
            const table = document.getElementById("example");
            const rows = table.querySelectorAll("tbody tr");

            rows.forEach(row => {
                const dateCell = row.cells[1].textContent; // Mengasumsikan tanggal ada di kolom kedua
                if (dateCell === selectedDate) {
                    row.style.display = ""; // Tampilkan baris jika tanggal cocok
                } else {
                    row.style.display = "none"; // Sembunyikan baris jika tidak cocok
                }
            });
        }

        // Pendengar acara untuk tombol reset filter
        document.getElementById("resetFilterBtn").addEventListener("click", function() {
            document.getElementById("datePicker")._flatpickr.clear(); // Hapus input tanggal
            const rows = document.querySelectorAll("#example tbody tr");
            rows.forEach(row => {
                row.style.display = ""; // Tampilkan semua baris
            });
        });

        // modal
        function toggleModal() {
            const modal = document.getElementById('inputModal');
            modal.classList.toggle('hidden');
        }

        // Event listener for dropdown change
        const dropdown = document.getElementById('dropdown');
        dropdown.addEventListener('change', function() {
            document.getElementById('debitBox').classList.toggle('hidden', this.value !== 'Debit');
            document.getElementById('kreditBox').classList.toggle('hidden', this.value !== 'Kredit');
        });

        // Handle form submission
        document.getElementById('dataForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page refresh

            const tanggal = document.getElementById('datePicker').value;
            const kode = document.getElementById('kode').value;
            const reff = dropdown.value;
            const keterangan = document.getElementById('keterangan').value;

            console.log(`Tanggal: ${tanggal}, Kode: ${kode}, Reff: ${reff}, Keterangan: ${keterangan}`);

            // Reset form and close modal
            this.reset();
            toggleModal();
        });

        // Initialize Flatpickr for the date picker
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d", // Set the desired date format
        });

    </script>

    <!-- JavaScript -->
    <!-- <script>
        const dropdown = document.getElementById('dropdown');
        const debitbox = document.getElementById('debitbox');
        const kreditbox = document.getElementById('kreditbox');


        dropdown.addEventListener('change', function () {
        const selectedValue = dropdown.value;


            // Tampilkan textbox debit jika pilihan adalah 'debit'
            if (selectedValue === 'Debit') {
                    debitBox.style.display = 'block';  // Tampilkan textbox Debit
                    kreditBox.style.display = 'none';  // Sembunyikan textbox Kredit
                } else if (selectedValue === 'Kredit') {
                    kreditBox.style.display = 'block'; // Tampilkan textbox Kredit
                    debitBox.style.display = 'none';   // Sembunyikan textbox Debit
                } else {
                    // Sembunyikan kedua textbox jika bukan Debit atau Kredit
                    debitBox.style.display = 'none';
                    kreditBox.style.display = 'none';
                }
            });
    </script> -->

</body>
</html>
