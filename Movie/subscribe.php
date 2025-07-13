<?php
session_start();

if (!isset($_SESSION['username'])) {
    echo "<script>alert('You must be logged in to subscribe.'); window.location='index.php'</script>";
    exit;
}

$username = $_SESSION['username'];
$movie = $_POST['movie'] ?? '';

$movieIdMap = [
    "Sinners" => 1233413,
    "Ant-man" => 102899,
    "The Dark Knight" => 155,
    "Interstellar" => 157336,
    "Coming 2 America" => 484718,
    "Top Gun Maverick" => 361743,
];

if (!isset($movieIdMap[$movie])) {
    echo "<script>alert('Invalid movie selection.'); window.location='index.php'</script>";
    exit;
}

$tmdb_id = $movieIdMap[$movie];

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "Emma90nuel";
$dbname = "TolaniDB";

$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
if ($conn->connect_error) {
    die("Database connection failed.");
}

// Check if already subscribed
$stmt = $conn->prepare("SELECT id FROM subscriptions WHERE username = ? AND tmdb_id = ?");
$stmt->bind_param("si", $username, $tmdb_id);
$stmt->execute();

if ($stmt->get_result()->num_rows > 0) {
    echo "<script>alert('You are already subscribed to this movie.'); window.location='index.php'</script>";
} else {
    $stmt = $conn->prepare("INSERT INTO subscriptions (username, tmdb_id) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $tmdb_id);

    if ($stmt->execute()) {
        echo "<script>alert('Subscription successful'); window.location='index.php'</script>";
    } else {
        echo "<script>alert('Subscription failed'); window.location='index.php'</script>";
    }
}

$stmt->close();
$conn->close();
?>