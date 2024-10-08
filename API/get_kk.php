// Database connection (update with your credentials)
$servername = "localhost";
$username = "dafm5634_ag";
$password = "Ag7us777__";
$dbname = "dafm5634_jimpitan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming your table is named 'master_kk'
$sql = "SELECT kk_name FROM master_kk WHERE code_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $code_id);
$stmt->execute();
$stmt->bind_result($kk_name);
$stmt->fetch();

if ($kk_name) {
    echo json_encode(['success' => true, 'kk_name' => $kk_name]);
} else {
    echo json_encode(['success' => false, 'message' => 'Record not found']);
}

$stmt->close();
$conn->close();