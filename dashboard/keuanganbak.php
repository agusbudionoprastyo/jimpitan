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
    <title>Keuangan</title>

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.0.8/css/dataTables.tailwindcss.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" rel="stylesheet">
</head>
<body>

    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-square-rounded'></i>
            <span class="text">Jimpitan</span>
        </a>
        <ul class="side-menu top">
            <li>
                <a href="index.php">
                    <i class='bx bxs-dashboard'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="kk.php">
                    <i class='bx bxs-group'></i>
                    <span class="text">KK</span>
                </a>
            </li>
            <li>
                <a href="report.php">
                    <i class='bx bxs-report'></i>
                    <span class="text">Report</span>
                </a>
            </li>
            <li class="active">
                <a href="#">
                    <i class='bx bxs-report'></i>
                    <span class="text">Keuangan</span>
                </a>
            </li>
        </ul>
        <ul class="side-menu">
            <li>
                <a href="logout.php" class="logout">
                    <i class='bx bxs-log-out-circle'></i>
                    <span class="text">Logout</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
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
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Keuangan - RT07 Salatiga</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Kas Umum</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="index.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Kas Umum</h3>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="tanggal" class="form-label">Pilih Tanggal:</label>
                                <input type="text" class="form-control datepicker" id="tanggal" name="tanggal" placeholder="Pilih tanggal" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>

                        <button type="button" id="printSelectedBtn" class="btn-download">
                            <i class='bx bxs-printer' style="font-size:24px"></i>
                        </button>
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
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->    

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Bootstrap Datepicker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();

            $('.datepicker').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>

    <script src="js/script.js"></script>
    <script src="js/print.js"></script>
    <script src="js/qrcode.min.js"></script>

    <script>
        const searchButton = document.querySelector('#content nav form .form-input button');
        const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
        const searchForm = document.querySelector('#content nav form');

        searchButton.addEventListener('click', function (e) {
            if(window.innerWidth < 576) {
                e.preventDefault();
                searchForm.classList.toggle('show');
                if(searchForm.classList.contains('show')) {
                    searchButtonIcon.classList.replace('bx-search', 'bx-x');
                } else {
                    searchButtonIcon.classList.replace('bx-x', 'bx-search');
                }
            }
        });

        if(window.innerWidth < 768) {
            sidebar.classList.add('hide');
        } else if(window.innerWidth > 576) {
            searchButtonIcon.classList.replace('bx-x', 'bx-search');
            searchForm.classList.remove('show');
        }

        window.addEventListener('resize', function () {
            if(this.innerWidth > 576) {
                searchButtonIcon.classList.replace('bx-x', 'bx-search');
                searchForm.classList.remove('show');
            }
        });
    </script>
</body>
</html>
