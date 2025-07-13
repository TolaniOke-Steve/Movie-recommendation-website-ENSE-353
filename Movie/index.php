<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        #movie-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
            list-style-type: none;
            padding: 0;
            justify-items: center;
            margin-top: 20px;
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
        .login-header-1{
            width: 150px;
            height: 60px;
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
        .sub{
            width: 100px;
            height: 30px;
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
        a {
            color: rgb(145, 195, 27);
            text-decoration: none;
        }
    #modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6); 
    backdrop-filter: blur(4px); 
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

#modal-content {
    background: none;
    padding: 0;
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
}

#modal-close {
    position: absolute;
    top: -30px;
    right: -30px;
    font-size: 24px;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
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

    <div class="search">
        <input type="text" placeholder="Search for movies..." class="input-field" id="search-input">
        <button id="search-button"></button>
    </div>
      <div class="s-text">
        <?php if (isset($_SESSION['username'])): ?>
        <button class="login-header-1" onclick="window.location.href='my_sub.php'">Subscriptions</button>
    <?php else: ?>
        <button class="login-header-1" onclick="loadForm('login')">Subscriptions</button>
    <?php endif; ?>
    </div>

    <div class="s-text">
        <p>Welcome to MoviesJoys! Here are some of the latest movies</p>
    </div>

    <div class="movie-list">
       <ul id="movie-list">
    <li>
        <img src="images/1.jpg" alt="Movie 1" class="movie-poster">
        <div class="movie-title">Sinners</div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="subscribe.php" method="POST">
                <input type="hidden" name="movie" value="Sinners">
                <button class="sub" type="submit">Subscribe</button>
            </form>
        <?php endif; ?>
    </li>
    <li>
        <img src="images/7.jpg" alt="Movie 2" class="movie-poster">
        <div class="movie-title">Ant-man</div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="subscribe.php" method="POST">
                <input type="hidden" name="movie" value="Ant-man">
                <button class="sub"  type="submit">Subscribe</button>
            </form>
        <?php endif; ?>
    </li>
    <li>
        <img src="images/3.jpg" alt="Movie 3" class="movie-poster">
        <div class="movie-title"> The Dark Knight</div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="subscribe.php" method="POST">
                <input type="hidden" name="movie" value="The Dark Knight">
                <button class="sub"  type="submit">Subscribe</button>
            </form>
        <?php endif; ?>
    </li>
    <li>
        <img src="images/4.jpg" alt="Movie 4" class="movie-poster">
        <div class="movie-title">Interstellar</div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="subscribe.php" method="POST">
                <input type="hidden" name="movie" value="Interstellar">
                <button class="sub"  type="submit">Subscribe</button>
            </form>
        <?php endif; ?>
    </li>
    <li>
        <img src="images/5.jpg" alt="Movie 5" class="movie-poster">
        <div class="movie-title">Coming 2 America</div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="subscribe.php" method="POST">
                <input type="hidden" name="movie" value="Coming 2 America">
                <button class="sub"  type="submit">Subscribe</button>
            </form>
        <?php endif; ?>
    </li>
    <li>
        <img src="images/6.jpg" alt="Movie 6" class="movie-poster">
        <div class="movie-title">Top Gun Maverick</div>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="subscribe.php" method="POST">
                <input type="hidden" name="movie" value="Top Gun Maverick">
                <button class="sub"  type="submit">Subscribe</button>
            </form>
        <?php endif; ?>
    </li>
</ul>

    </div>

   <div id="modal-overlay">
  <div id="modal-content">
    <button id="modal-close">&times;</button>
    <div id="modal-body">
      <!-- form will be loaded here -->
    </div>
  </div>
</div>

<script>
const modalOverlay = document.getElementById('modal-overlay');
const modalBody = document.getElementById('modal-body');
const modalCloseBtn = document.getElementById('modal-close');

function loadForm(type) {
  fetch(type + '.php?ajax=1')
    .then(res => res.text())
    .then(html => {
      modalBody.innerHTML = html;
      modalOverlay.style.display = 'flex';
      attachFormHandler(type);
    })
    .catch(() => {
      modalBody.innerHTML = '<p style="color: yellow;">Failed to load form. Please try again later.</p>';
      modalOverlay.style.display = 'flex';
    });
}

modalCloseBtn.addEventListener('click', () => {
  modalOverlay.style.display = 'none';
  modalBody.innerHTML = '';
});

window.addEventListener('click', e => {
  if (e.target === modalOverlay) {
    modalOverlay.style.display = 'none';
    modalBody.innerHTML = '';
  }
});

function attachFormHandler(type) {
  const form = modalBody.querySelector('form');
  if (!form) return;
  
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);

    // Clear previous errors
    const errorMsg = modalBody.querySelector('#error-msg');
    if (errorMsg) errorMsg.remove();

    try {
      const response = await fetch(type + '.php', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const result = await response.json();

      if (result.success) {
        // Success: reload page to reflect login/signup
        window.location.href = result.redirect || 'index.php';
      } else {
        // Show error message inside modal
        const p = document.createElement('p');
        p.id = 'error-msg';
        p.textContent = result.message || 'An error occurred.';
        modalBody.prepend(p);
      }
    } catch (error) {
      const p = document.createElement('p');
      p.id = 'error-msg';
      p.textContent = 'Request failed. Please try again.';
      modalBody.prepend(p);
    }
  });
}
</script>
</body>
</html>
