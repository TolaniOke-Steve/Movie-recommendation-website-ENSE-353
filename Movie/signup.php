<?php
session_start();

$host = "localhost";
$db = "TolaniDB";
$user = "root";
$pass = "Emma90nuel";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
) {
        echo json_encode(['success' => false, 'message' => 'Database error.']);
        exit;
    } else {
        die("Database error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
         echo json_encode(['success' => false, 'message' => 'Please fill out all fields.']);
        exit;
    }

    // Check if username exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => false, 'message' => 'Username already taken.']);
        exit;
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
     $stmt->execute([$username, $email, $hashedPassword]);

        $_SESSION['username'] = $username;
        echo json_encode(['success' => true]);
        exit;
    }
}


if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    ?>
    <h2 style="color:#dedbdb; text-align:center;">Create an account</h2>
    <form method="POST" action="signup.php">
        <div class="text">
           <input type="text" class="input-field" name="name" placeholder="Username" required />
        </div>
        <div class="text">
            <input type="email" class="input-field" name="email" placeholder="Email" required />
        </div>
        <div class="text">
            <input type="password" class="input-field" name="password" placeholder="Password" required />
        </div>
        <div class="input-submit" style="display:flex; justify-content:center;">
            <button type="submit" class="submit">Sign up</button>
        </div>
        <p style="color:#91c31b; text-align:center; cursor:pointer; margin-top:10px;" onclick="loadForm('login')">
    Already have an account? Login
</p>

    </form>
    <?php
    exit;
}

?>
