<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php"); 
    exit();
}

$username = $_SESSION['username'];    

$servername = "localhost";
$dbUsername = "root";
$dbPassword = "Emma90nuel";
$dbName = "TolaniDB";

$con = new mysqli($servername, $dbUsername, $dbPassword, $dbName);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

$stmt = $con->prepare("SELECT tmdb_id FROM subscriptions WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

$subscribedMovies = [];

while ($row = $result->fetch_assoc()) {
    $subscribedMovies[] = $row;
}

$stmt->close();
$con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>MoviesJoys</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #3d179c;
        font-size: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }  .container {
        width: 50%;
        max-width: 800px;
        text-align: center;
    }
    table {
        border-collapse: collapse;
        width: 50%;
    }
    th, td {
        border: 1px solid black;
        padding: 12px 20px;
    }
    th {
        background-color: #dedbdb;
    }
    h1 {
        color: #ffffff;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .s-text {
        color: #dedbdb;
        font-size: 18px;
        margin-top: 10px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .search {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 30px;
    }
    .movie-list {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
        margin-left: 20px;
        margin-right: 20px;
    }
    #h-img {
        width: 30px;
        height: 30px;
        margin-left: 8px;
    }
        .input-field{
        width: 100%;
        height: 60px;
        font-size: 17px;
        padding: 0 25px;
        margin-bottom: 15px;
        border-radius: 30px;
        border: none;
        box-shadow: 0px 5px 10px 1px rgba(0,0,0, 0.05);
        outline: none;
        transition: .3s;
    }
    #search-button {
        width: 70px;
        height: 60px;
        border-radius: 60%;
        border: none;
        background-color: #8557fa;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.1);
        transition: 0.3s;
        margin-left: 9px;
        margin-top: -8px;
    }
    #search-button::before {
        content: "→"; 
        font-size: 30px;
        color: #3d179c;
    }
    #search-button:hover{
        transform: scale(1.05,1);
    }

    .movie-list > div {
    background: rgba(255,255,255,0.08);
    border-radius: 18px;
    margin: 15px;
    padding: 18px 10px 10px 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.10);
    border: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 200px;
    }
    .movie-poster {
        width: 180px;
        height: 270px;
        border-radius: 15px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        object-fit: cover;
        background: #fff;
        display: block;
    }
    .movie-poster:hover {
        transform: scale(1.05,1);
    }
    .movie-title {
        color: #dedbdb;
        text-align: center;
        margin-top: 10px;
        font-size: 15px;
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
        .submit {
        width: 200px;
        height: 60px;
        font-size: 17px;
        margin-top: 18px;
        margin-bottom: 15px;
        border-radius: 30px;
        background: #8557fa;
        border: none;
        cursor: pointer;
        transition: .3s;
    }
    .input-submit {
        display: flex;
        justify-content: center;
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
    .movie-poster {
        width: 180px;
        height: 270px;
        border-radius: 15px;
        box-shadow: 0 4px 16px rgb(0 0 0 / 0.15);
        object-fit: cover;
        background: #fff;
        display: block;
    }
    .movie-poster:hover {
        transform: scale(1.05);
    }
    .movie-title {
        color: #dedbdb;
        text-align: center;
        margin-top: 10px;
        font-size: 15px;
    }
    .movie-card {
        position: relative;
        transition: transform 0.3s;
        background: #fff1;
        border-radius: 18px;
        margin: 15px;
        padding: 18px 10px 10px 10px;
        box-shadow: 0 2px 8px rgb(0 0 0 / 0.1);
        border: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 200px;
    }
    .movie-card:hover {
        transform: scale(1.05);
    }
    .movie-details {
        display: none;
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #000000d9;
        color: #00ff00;
        padding: 12px 18px;
        border-radius: 10px;
        font-size: 16px;
        z-index: 2;
        white-space: nowrap;
        pointer-events: auto;
        transition: opacity 0.3s;
        transition: opacity 0.3s;
    }
    .movie-card:hover .movie-details {
        display: block;
        opacity: 1;
    }
    .movie-details a {
        color: #00ff00;
        text-decoration: none;
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

    <div class="s-text">
        <p>Your Currnet Subscriptions</p>
    </div>

    <div class="movie-list">
    <?php if (empty($subscribedMovies)): ?>
        <p style="color: #dedbdb;">You have no subscriptions yet.</p>
    <?php else: ?>
        <?php foreach ($subscribedMovies as $movie): ?>
            <div class="movie-card">
                <?php
                    $movieId = $movie['tmdb_id'];
                    // fetch poster from TMDb
                    $api_key = '1ef89355e22238f9fef5c64e4680b1b2';
                    $api_response = file_get_contents("https://api.themoviedb.org/3/movie/$movieId?api_key=$api_key");

                    if ($api_response) {
                        $movieData = json_decode($api_response, true);
                        $movieTitle = $movieData['title'];
                        $moviePoster = $movieData['poster_path'];
                        $moviePosterURL = "https://image.tmdb.org/t/p/w500$moviePoster";

                        echo "<img src='$moviePosterURL' alt='".htmlspecialchars($movieTitle)."' class='movie-poster'>";
                        echo "<p class='movie-title'>{$movieTitle}</p>";
                        echo "<div class='movie-details'>";
                        echo "<a href='movie_details.php?movie_id=$movieId'>Movie Details</a>";
                        echo "</div>";
                    } else {
                        echo "<p>Unable to retrieve movie details.</p>";
                    }
                ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    </div>

    <script></script>
</body>
</html>
