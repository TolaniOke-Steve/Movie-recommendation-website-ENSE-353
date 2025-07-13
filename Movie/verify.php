<?php
session_start();

$host = "localhost";
$db = "TolaniDB";
$user = "root";
$pass = "Emma90nuel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (!isset($_GET['token'])) {
        die("Invalid access.");
    }

    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE verification_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && !$user['email_verified']) {
        $update = $pdo->prepare("UPDATE users SET email_verified = 1, verification_token = NULL WHERE id = ?");
        $update->execute([$user['ID']]);
        echo "<h2>Email verified successfully. You can now <a href='index.php' style='color:green;'>login</a>.</h2>";
    } else {
        echo "<h2>Invalid or already used verification link.</h2>";
    }
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}
?>
