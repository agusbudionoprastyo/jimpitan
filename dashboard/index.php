<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>

    <!-- My CSS -->
    <link rel="stylesheet" href="css/style.css">

    <!-- sweetalert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Jimpitan - RT07 Salatiga</title>
</head>
<body>
    <!-- SIDEBAR -->
    <section id="sidebar">
        <a href="#" class="brand">
            <i class='bx bx-medal'></i>
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
                    <span class="text">List KK</span>
                </a>
            </li>
        </ul>
        <!-- <ul class="side-menu">
			<li>
				<a href="#">
					<i class='bx bxs-cog' ></i>
					<span class="text">Settings</span>
				</a>
			</li>
			<li>
				<a href="#" class="logout">
					<i class='bx bxs-log-out-circle' ></i>
					<span class="text">Logout</span>
				</a>
			</li>
		</ul> -->
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
                    <!-- <h1>Fun Run - Lari Antar Geng</h1> -->
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

            <!-- <div class="marquee">
                <div class="marquee__inner">
                    <p class="marquee__line" id="running-text">Fun Run - Lari Antar Geng</p>
                </div>
            </div> -->
            
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
						<!-- <h3>Recent</h3> -->
                        <h3 id="current-time"></h3>
					</div>
					<table id="checkin-table">
						<thead>
							<tr>
								<th>GENG's</th>
								<th>BIBNUMBER</th>
                                <th>TIMESTAMP</th>
							</tr>
						</thead>
						<tbody id="checkin-table-body">
						</tbody>
					</table>
                </div>

                    <div class="todo">
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
                            </div>
                        </div>
                    </main>
            <!-- MAIN -->
        </section>

    <!-- CONTENT -->
    <audio id="audio" src="assets/magic.wav"></audio>
    
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="js/script.js"></script>
    <script src="js/sse.js"></script>
</body>
</html>