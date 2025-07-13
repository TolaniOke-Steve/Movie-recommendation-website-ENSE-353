<?php
session_start();
$host = 'localhost';
$dbname = 'TolaniDB';
$user = 'root';
$pass = 'Emma90nuel';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['name'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Please fill all fields.']);
        exit;
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Use email_verified instead of confirmed
        if (!$user['email_verified']) {
            echo json_encode(['success' => false, 'message' => 'Please confirm your email first.']);
        } else {
            $_SESSION['username'] = $username;
            $isAdmin = stripos($username, 'admin') !== false; // case-insensitive check
            $redirectUrl = $isAdmin ? 'Admin.php' : 'index.php';
            echo json_encode(['success' => true, 'redirect' => $redirectUrl]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid username or password.']);
    }
    exit;
}

// If GET request and ajax=1, return only form HTML
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    ?>
    <h2 style="color:#dedbdb; text-align:center;">Login</h2>
    <form method="POST" action="login.php">
        <div class="text">
            <input type="text" class="input-field" id="name" name="name" placeholder="Username" required />
        </div>
        <div class="text">
            <input type="password" class="input-field" id="pwd" name="password" placeholder="Password" required />
        </div>
          <div class="input-submit" style="display:flex; justify-content:center;">
            <button type="submit" class="submit" id="submit"> Login </button>
        </div>
        <p style="color:#91c31b; text-align:center; cursor:pointer; margin-top:10px;" onclick="loadForm('signup')">
    Don't have an account? Signup
</p>
    </form>
    <?php
    exit;
}
?>