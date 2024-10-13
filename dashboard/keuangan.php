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

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
    <title>Keuangan</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-square-rounded'></i>
            <span class="text">Jimpitan</span>
        </a>
        <ul class="side-menu top">
            <li><a href="index.php"><i class='bx bxs-dashboard'></i><span class="text">Dashboard</span></a></li>
            <li><a href="kk.php"><i class='bx bxs-group'></i><span class="text">KK</span></a></li>
            <li><a href="report.php"><i class='bx bxs-report'></i><span class="text">Report</span></a></li>
            <li class="active"><a href="#"><i class='bx bxs-wallet'></i><span class="text">Keuangan</span></a></li>
        </ul>
        <ul class="side-menu">
            <li><a href="setting.php"><i class='bx bxs-cog'></i><span class="text">Settings</span></a></li>
            <li><a href="logout.php" class="logout"><i class='bx bxs-log-out-circle'></i><span class="text">Logout</span></a></li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <nav>
            <i class='bx bx-menu'></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" id="search-input" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
                </div>
            </form>
            <input type="checkbox" id="switch-mode" hidden>
            <label for="switch-mode" class="switch-mode"></label>
        </nav>

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Jimpitan - RT07 Salatiga</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Keuangan</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active" href="index.php">Home</a></li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Keuangan</h3>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inputModal">
                            Tambah Transaksi
                        </button>

                        <button type="button" id="addcreditdebBtn" class="btn-download" data-bs-toggle="modal" data-bs-target="#inputModal">
                            <i class='bx bxs-add-to-queue'></i> Deb/Cr
                        </button>
                        <input type="text" id="datePicker" class="custom-select" placeholder="Pilih Tanggal">
                            <button type="button" id="resetFilterBtn">
                            <i class="bx bx-x-circle" style="font-size: 24px; color: red;"></i>
                            </button>
                    </div>
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Kode</th>
                                <th style="text-align: center;">Tanggal</th>
                                <th style="text-align: center;">Reff</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: center;">Debet</th>
                                <th style="text-align: center;">Kredit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($data) {
                                    foreach ($data as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row["coa_code"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["date_trx"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["reff"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["description"]); ?></td>
                                            <td><?php echo "Rp " . number_format(htmlspecialchars($row["debet"]), 0, ',', '.'); ?></td> 
                                            <td><?php echo "Rp " . number_format(htmlspecialchars($row["kredit"]), 0, ',', '.'); ?></td> <!-- asem variable billingual hahahahah :) -->
                                        </tr>
                                    <?php endforeach; 
                                } else {
                                    echo '<tr><td colspan="6" style="text-align:center;">No data available</td></tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>  
            </div>
                        <!-- Modal -->
            <div class="modal fade" id="inputModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel">Form Tambah Data</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="dataForm">
                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <!--<input type="text" class="form-control" id="nama" name="nama" required>-->
                                    <input type="text" id="datePicker" class="custom-select" placeholder="Pilih Tanggal" name="tanggal" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kode" class="form-label">Kode</label>
                                    <input type="text" class="form-control" id="kode" name="kode" required>
                                </div>
                                    <!-- Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pilih Opsi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">Debet</a></li>
                                        <li><a class="dropdown-item" href="#">Kredit</a></li>
                                    </ul>
                                </div>
                                <div class="mb-3">
                                    <label for="reff" class="form-label">Reff</label>
                                    <input type="text" class="form-control" id="reff" name="reff" required>
                                </div>
                                    <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                                </div>
                                <div class="mb-3">
                                    <label for="debet" class="form-label">Debet</label>
                                    <input type="number" class="form-control" id="debet" name="debet" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kredit" class="form-label">Kredit</label>
                                    <input type="number" class="form-control" id="kredit" name="kredit" required>
                                </div>

                                <button type="submit" class="btn btn-success">Simpan</button>
                            </form>
                        </div>
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
    </script>
</body>
</html>
