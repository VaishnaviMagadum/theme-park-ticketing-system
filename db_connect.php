<?php 
$host = getenv('DB_HOST') ?: '127.0.0.1';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '';
$db   = getenv('DB_NAME') ?: 'tpts_db';
$port = (int)(getenv('DB_PORT') ?: (ini_get('mysqli.default_port') ?: 3306));

// Try 127.0.0.1 (TCP), then fallback to localhost (Unix socket)
$conn = @new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    $conn = @new mysqli('localhost', $user, $pass, $db, $port);
}
if ($conn->connect_error) {
    die("Could not connect to mysql: " . $conn->connect_error);
}
?>
