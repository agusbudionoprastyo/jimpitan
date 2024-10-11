<?php
// Include the database connection
include 'api/db.php';

// Prepare the SQL statement to select only today's shift
$stmt = $pdo->prepare("
    SELECT master_kk.kk_name, report.* 
    FROM report 
    JOIN master_kk ON report.report_id = master_kk.code_id
");

// Execute the SQL statement
$stmt->execute();

// Fetch all results
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    <title>Jimpitan</title>
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
                    <i class='bx bxs-dashboard' ></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
            <a href="kk.php">
                    <i class='bx bxs-group' ></i>
                    <span class="text">KK</span>
                </a>
            </li>
            <li class="active">
            <a href="#">
                    <i class='bx bxs-report'></i>
                    <span class="text">Report</span>
                </a>
            </li>
        </ul>

        <ul class="side-menu">
			<!-- <li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li> -->
			<li>
				<a href="#" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
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
            <i class='bx bx-menu' ></i>
            <form action="#">
                <div class="form-input">
                    <input type="search" id="search-input" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button>
					<!-- <button type="button" class="clear-btn"><i class='bx bx-reset' ></i></button> -->
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
                    <h1>Jimpitan - RT07 Salatiga</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Report</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="index.php">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="table-data">
                <div class="order">
                    <div class="head">
                        <h3>Report</h3>
                        <label for="month">Bulan</label>
                            <select id="month" name="month">
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($i == date('n')) ? 'selected' : '' ?>>
                                        <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                                    </option>
                                <?php endfor; ?>
                            </select>

                            <label for="year">Tahun</label>
                            <select id="year" name="year">
                                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                                    <option value="<?= $y ?>" <?= ($y == date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="button" id="reportBtn" class="btn-download">
                                <i class='bx bxs-file-export' style="font-size:24px"></i>
                            </button>
                    </div>
                    <table id="example" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Nama KK</th>
                                <th>Code</th>
                                <th>Jimpitan Tanggal</th>
                                <th>Nominal</th>
                                <th>Input By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                if ($data) {
                                    foreach ($data as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row["kk_name"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["report_id"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["jimpitan_date"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["nominal"]); ?></td>
                                            <td><?php echo htmlspecialchars($row["collector"]); ?></td>
                                        </tr>
                                    <?php endforeach; 
                                } else {
                                    echo '<tr><td colspan="5" class="px-6 py-4 text-center">No data available</td></tr>';
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
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.tailwindcss.js"></script>

    <script src="js/script.js"></script>
    <script src="js/report.js"></script>
    <script src="js/export.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.2.1/exceljs.min.js"></script>


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
        })

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
        })
    </script>
</body>
</html>