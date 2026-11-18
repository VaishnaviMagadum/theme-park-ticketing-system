<?php 
mysqli_report(MYSQLI_REPORT_OFF);

$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'park_user';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : 'park123';
$db   = getenv('DB_NAME') ?: 'tpts_db';
$port = (int)(getenv('DB_PORT') ?: (ini_get('mysqli.default_port') ?: 3306));

// 1. Try configured user (park_user on Render)
$conn = @new mysqli($host, $user, $pass, $db, $port);

// 2. If that fails, try root with blank password (local XAMPP default)
if ($conn->connect_error) {
    $conn = @new mysqli($host, 'root', '', $db, $port);
}

// 3. Fallback to localhost socket
if ($conn->connect_error) {
    $conn = @new mysqli('localhost', $user, $pass, $db, $port);
}
if ($conn->connect_error) {
    $conn = @new mysqli('localhost', 'root', '', $db, $port);
}

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
