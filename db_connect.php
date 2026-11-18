<?php 
$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db   = getenv('DB_NAME') ?: 'tpts_db';
$port = (int)(getenv('DB_PORT') ?: (ini_get('mysqli.default_port') ?: 3306));

// 1. Try primary configured credentials
$conn = @new mysqli($host, $user, $pass, $db, $port);

// 2. Fallback to dedicated container user
if ($conn->connect_error) {
    $conn = @new mysqli($host, 'park_user', 'park123', $db, $port);
}

// 3. Fallback to localhost socket
if ($conn->connect_error) {
    $conn = @new mysqli('localhost', $user, $pass, $db, $port);
}

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
