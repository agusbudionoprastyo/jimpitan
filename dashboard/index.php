<?php
// Include the database connection
include 'api/db.php';

// Get the current day of the week (e.g., "Sunday", "Monday", etc.)
$currentDay = date('l'); // 'l' gives full textual representation of the day

// Prepare the SQL statement to select only today's shift
$stmt = $pdo->prepare("
    SELECT master_kk.kk_name, users.shift 
    FROM users 
    JOIN master_kk ON users.id_code = master_kk.code_id
    WHERE users.shift = :currentDay
");

// Bind the parameter
$stmt->bindParam(':currentDay', $currentDay);

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

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">

    <title>Jimpitan - RT07 Salatiga</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-square-rounded'></i>
            <span class="text">Jimpitan</span>
        </a>
        <ul class="side-menu top">
            <li class="active">
                <a href="#">
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
            <li>
            <a href="report.php">
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
                    <!-- <input type="search" id="search-input" placeholder="Search...">
                    <button type="submit" class="search-btn"><i class='bx bx-search' ></i></button> -->
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
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right' ></i></li>
                        <li>
                            <a class="active" href="#">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <ul class="box-info">
                <li>
                    <i class='bx bxs-group bx-lg' ></i>
                    <span class="text">
                        <h3 id="totalPeserta">0</h3>
                        <p>Total KK</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-badge-check bx-lg' ></i>
                    <span class="text">
                        <h3 id="totalCheck">0</h3>
                        <p>Checked</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-info-circle bx-lg'></i>
                    <span class="text">
                        <h3 id="totalUncheck">0</h3>
                        <p>Unchecked</p>
                    </span>
                </li>
            </ul>


            <div class="table-data">
				<div class="order">
					<div class="head">
                    <h3>Jaga Malam</h3>
					</div>
					<table id="checkin-table">
						<thead>
							<tr>
								<th>NAMA</th>
								<th>SHIFT</th>
							</tr>
						</thead>
						<tbody>
                        <?php
                            if ($data) {
                                foreach ($data as $row): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row["kk_name"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["shift"]); ?></td>
                                    </tr>
                                <?php endforeach; 
                            } else {
                                echo '<tr><td colspan="3">No data available</td></tr>';
                            }
                        ?>
						</tbody>
					</table>
                </div>

                    <!-- <div class="todo">
                        <div class="head">
                            <h3>Jaga Malam</h3>
                        </div>
                                <ul id="medal-list" class="todo-list">
                            <li class="first">
                            </li>
                        <li class="second">
                        </li>
                    <li class="third">
                    </li>
                        <li class="fourth">
                        </li>
                            <li class="fifth">
                            </li>
                            <li class="sixth">
                            </li>
                                </ul>
                            </div> -->
                        </div>
                    </main>
            <!-- MAIN -->
        </section>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="js/script.js"></script>
</body>
</html>