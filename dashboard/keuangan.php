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
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"> -->

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
                        <button type="button" id="openModal" class="btn-download" data-bs-toggle="modal" data-bs-target="#inputModal">
                            <i class='bx bxs-add-to-queue'></i> Transaksi
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
            <div id="inputModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"> 
                <div class="modal-overlay absolute inset-0 bg-black opacity-50 z-40"></div>
                <div class="modal-container bg-white w-11/12 md:w-1/3 mx-auto rounded-lg shadow-lg z-50">
                    <div class="modal-header flex justify-between items-center p-4 border-b">
                        <h5 class="text-lg font-semibold" id="modalLabel">Tambah Data Keuangan</h5>
                        <button type="button" class="close-modal text-gray-500" aria-label="Close">&times;</button>
                    </div>
                    <div class="modal-body p-4">
                        <form id="dataForm">
                            <div class="mb-3">
                                <label for="tanggal" class="block text-sm font-medium">Tanggal</label>
                                <input type="text" id="datePicker" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Pilih Tanggal" name="tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label for="kode" class="block text-sm font-medium">Kode</label>
                                <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" id="kode" name="kode" required>
                            </div>
                            <div class="mb-3">
                                <label for="dropdown" class="block text-sm font-medium">Reff</label>
                                <select id="dropdown" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="" disabled>-- Pilih Opsi --</option>
                                    <option value="Opsi 1">Debet</option>
                                    <option value="Opsi 2">Kredit</option>
                                </select>
                            </div>
                            <div class="mb-3" id="debitBox" style="display: none;">
                                <label for="debitTextbox" class="block text-sm font-medium">Debit</label>
                                <input type="text" id="debitTextbox" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Detail debit">
                            </div>
                            <div class="mb-3" id="kreditBox" style="display: none;">
                                <label for="kreditTextbox" class="block text-sm font-medium">Kredit</label>
                                <input type="text" id="kreditTextbox" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Detail kredit">
                            </div>
                            <div class="mb-3">
                                <label for="keterangan" class="block text-sm font-medium">Keterangan</label>
                                <textarea id="keterangan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Isi keterangan" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </section>

    <!-- jQuery (Optional for extra functionality) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="js/monthSelectPlugin.js"></script>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>
    <script src="js/script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.1/exceljs.min.js"></script>

    <script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = new DataTable('#example', {
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            searching: true,
            order: [[2, 'desc']],
            columnDefs: [
                { targets: 0, className: "text-left" },
                { targets: 1, className: "text-center" },
                { targets: 2, className: "text-center" },
                { targets: 3, className: "text-center" },
                { targets: 4, className: "text-right" },
                { targets: 5, className: "text-right" }
            ],
            language: {
                lengthMenu: "_MENU_ Entri per halaman",
                zeroRecords: "No records found",
                info: "Showing page _PAGE_ of _PAGES_",
                infoEmpty: "No records available",
                infoFiltered: "(filtered from _MAX_ total records)"
            }
        });

        // Search functionality
        $('#search-input').keypress(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                var searchText = $(this).val();
                table.search(searchText).draw();
            }
        });

        $('.search-btn').click(function() {
            var searchText = $('#search-input').val();
            table.search(searchText).draw();
        });

        $('.clear-btn').click(function() {
            $('#search-input').val('');
            table.search('').draw();
        });

        // Date picker initialization
        flatpickr("#datePicker", {
            dateFormat: "Y-m-d",
            onChange: function(selectedDates, dateStr) {
                filterTableByDate(dateStr);
            }
        });

        // Filter table by date
        function filterTableByDate(selectedDate) {
            const rows = document.querySelectorAll("#example tbody tr");
            rows.forEach(row => {
                const dateCell = row.cells[1].textContent; // Assuming date is in the second column
                row.style.display = (dateCell === selectedDate) ? "" : "none";
            });
        }

        // Reset filters
        document.getElementById("resetFilterBtn").addEventListener("click", function() {
            document.getElementById("datePicker")._flatpickr.clear();
            const rows = document.querySelectorAll("#example tbody tr");
            rows.forEach(row => row.style.display = "");
        });

        // Open modal
        $('#openModal').click(function() {
            $('#inputModal').removeClass('hidden');
        });

        // Close modal
        $('#inputModal').on('click', '.close-modal, .modal-overlay', function() {
            console.log('Modal closed'); // Debug log
            $('#inputModal').addClass('hidden');
        });

        // Form submission handling
        $('#dataForm').on('submit', function(e) {
            e.preventDefault(); // Prevent page refresh

            // Validate form inputs
            if (!$(this).get(0).checkValidity()) {
                return; // Prevent submission if form is invalid
            }

            const tanggal = $('#datePicker').val();
            const kode = $('#kode').val();
            const reff = $('#dropdown').val();
            const keterangan = $('#keterangan').val();
            const debit = $('#debitTextbox').val();
            const kredit = $('#kreditTextbox').val();

            console.log(`Tanggal: ${tanggal}, Kode: ${kode}, Reff: ${reff}, Keterangan: ${keterangan}, Debit: ${debit}, Kredit: ${kredit}`);

            // Reset form and close modal
            $(this).trigger('reset');
            $('#inputModal').addClass('hidden');
            Swal.fire('Data saved!', '', 'success');
        });

        // Dropdown logic for debit/kredit
        $('#dropdown').change(function() {
            const selectedValue = $(this).val();
            $('#debitBox').toggle(selectedValue === "Opsi 1");
            $('#kreditBox').toggle(selectedValue === "Opsi 2");
        });
    });
    </script>

</body>
</html>
