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
                        <button type="button" id="resetFilterBtn" class="btn-download">
                            <i class="bx bx-x-circle" style="font-size : 20px;"></i>
                        </button>
                        <input type="text" id="datePicker" class="custom-select" placeholder="Pilih Tanggal">
                        <!-- <button type="button" id="reportBtn" class="btn-download">
                            <i class='bx bxs-file-export'></i> Unduh
                        </button> -->
                    </div>
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th style="text-align: left;">Kode</th>
                                <th style="text-align: center;">Tanggal</th>
                                <th style="text-align: left;">Reff</th>
                                <th style="text-align: center;">Keterangan</th>
                                <th style="text-align: left;">Debet</th>
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
                                        <td><?php echo htmlspecialchars($row["debit"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["credit"]); ?></td>
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
        </main>
    </section>

    <script src="js/monthSelectPlugin.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>
    <script src="js/script.js"></script>
    <script src="js/report.js"></script>
    <!-- <script src="js/export.js"></script> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.1/exceljs.min.js"></script>

    <script>
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
