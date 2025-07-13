<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

$username = $_SESSION['username'];
$movieId = $_GET['movie_id'] ?? null;
$apiKey = '1ef89355e22238f9fef5c64e4680b1b2';

if (!$movieId) {
    echo "No movie selected.";
    exit();
}

$movieUrl = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey";
$trailerUrl = "https://api.themoviedb.org/3/movie/$movieId/videos?api_key=$apiKey";

function fetchFromTMDb($url) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return json_decode($response, true);
}

$movieData = fetchFromTMDb($movieUrl);
$videoData = fetchFromTMDb($trailerUrl);

$trailerKey = null;
if (!empty($videoData['results'])) {
    foreach ($videoData['results'] as $video) {
        if ($video['site'] === 'YouTube' && $video['type'] === 'Trailer') {
            $trailerKey = $video['key'];
            break;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', 'Emma90nuel', 'TolaniDB');
    if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

    $stmt = $conn->prepare("DELETE FROM subscriptions WHERE username = ? AND tmdb_id = ?");
    $stmt->bind_param("si", $username, $movieId);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: my_sub.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo htmlspecialchars($movieData['title']); ?> - Details</title>
    <style>
        body { 
             font-family: Arial, sans-serif;
            background-color: #3d179c;
            font-size: 17px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; 
            color:  #ffffff;
        }
        .container { 
            display: flex; 
            gap: 30px; 
            align-items: 
            flex-start; 
        }
        iframe { 
            border: none; 
            margin-top: 20px; 
        }
        .actions { 
            margin-top: 20px; 
        }
        .actions form button {
            background: #ff3366; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 5px;
            color: white; 
            font-size: 16px; 
            cursor: pointer;
        }
        h1 {
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-header-1{
            width: 100px;
            height: 50px;
            font-size: 17px;
            padding: 0 15px;
            margin-top: 5px;
            margin-bottom: 5px;
            border-radius: 18px;
            background: #8557fa;
            border: none;
            cursor: pointer;
            transition: .3s;
     }
     .auth-container {
        position: absolute;
        top: 20px;
        right: 20px;
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }
    #h-img {
        width: 30px;
        height: 30px;
        margin-left: 8px;
        mixed-blend-mode: multiply;
    }
     .login-header{
        width: 100px;
        height: 50px;
        font-size: 17px;
        padding: 0 15px;
        margin-top: 18px;
        margin-bottom: 15px;
        border-radius: 18px;
        background: #8557fa;
        border: none;
        cursor: pointer;
        transition: .3s;
    }
    .auth-container {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
    }
    #user-info {
            color: white;
            font-size: 16px;
            margin-right: 10px;
            display: flex;
            align-items: center;
    }
    #img-1 { 
            max-width: 300px; 
            border-radius: 10px; 
            box-shadow: 0 0 12px rgba(0,0,0,0.5); 
        }
    </style>
</head>
<body>
<div class="auth-container">
        <?php if (isset($_SESSION['username'])): ?>
            <div id="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!
                <form method="post" action="logout.php" style="margin-left: 10px;">
                    <button class="login-header" type="submit">Logout</button>
                </form>
            </div>
        <?php else: ?>
            <button class="login-header" onclick="loadForm('login')">Login</button>
            <button class="login-header" onclick="loadForm('signup')">Sign Up</button>
        <?php endif; ?>
    </div>
    <h1>MoviesJoys <img src="images/logo.png" id="h-img" alt="Logo"></h1>

    <button class="login-header-1" onclick="window.location.href='index.php'">Home</button>
    <h2><?php echo htmlspecialchars($movieData['title']); ?></h2>
    <div class="container">
        <div>
            <img id= "img-1"src="https://image.tmdb.org/t/p/w500<?php echo $movieData['poster_path']; ?>" alt="Poster">
        </div>
        <div>
            <p><strong>Release Date:</strong> <?php echo $movieData['release_date']; ?></p>
            <p><strong>Overview:</strong> <?php echo $movieData['overview']; ?></p>
            <p><strong>Rating:</strong> <?php echo $movieData['vote_average']; ?> ⭐</p>

            <div class="actions">
                <form method="POST">
                    <button type="submit">Unsubscribe</button>
                </form>
            </div>

            <?php if ($trailerKey): ?>
                <iframe width="560" height="315"
                    src="https://www.youtube.com/embed/<?php echo $trailerKey; ?>"
                    allow="autoplay; encrypted-media"
                    allowfullscreen>
                </iframe>
            <?php else: ?>
                <p>No trailer available.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
