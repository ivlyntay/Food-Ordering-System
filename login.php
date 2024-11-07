<?php
require_once("connection.php");
error_reporting(0);  
session_start(); 

if($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  
  //avoid SQL injection
  $sanitized_username = mysqli_real_escape_string($conn, $username); 
  $sanitized_password = mysqli_real_escape_string($conn, $password); 

  $sql = "SELECT * FROM user WHERE username = '$sanitized_username' AND password = '$sanitized_password'";
  // $sql = "SELECT * FROM user WHERE username = '$username' AND password = '$password'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  if($row) {
    $_SESSION['username'] = $row['username'];
    $_SESSION['role'] = $row['role']; // Store the role in the session
    $_SESSION['user_id'] = $row['id'];

    if($row['role'] == 'admin') {
      header("Location: admin/dashboard.php");
      exit(); // Make sure to exit after the header redirection
    } elseif($row['role'] == 'user'){
      header("Location: foodmenu.php");
      exit(); // Make sure to exit after the header redirection
    }
  } else {
    $message = "Invalid username or password!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<style>
    body {
      background-image: url('img/bckground.jpg');
      background-size: cover;
      background-position: center;
    }
    .container-fluid {
      background-color: rgb(253,251,122);
    }
  </style>
<body>
<header>
  <div class="container-fluid">
    <nav class="navbar">
      <img src="img/logo2.png" alt="Logo" height="50px" alt="logo2">
      <button 
        class="navbar-toggler"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#toggleMobileMenu"
        aria-controls="toggleMobileMenu"
        aria-expanded="false"
        aria-label="Toggle navigation"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse " id="toggleMobileMenu">
        <ul class="navbar-nav ms-auto ">
          <li class="nav-item">
            <a href="index.php" class="nav-link">Home</a>
          </li>
          
          <?php
          if (!empty($_SESSION['u_id'])) {
            echo '<li class="nav-item">
                    <a href="#" class="nav-link">Cart</a>
                  </li>
                  <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                  </li>';
          } else {
            echo '<li class="nav-item">
                    <a href="login.php" class="nav-link">Login</a>
                  </li>';
          }
          ?>
          </li>

        </ul>
      </div>
    </nav>
  </div>
</header>
<section class="v-100">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col col-xl-10">
        <div class="card" style="border-radius: 1rem;">
          <div class="row g-0">
            <div class="col-md-6 col-lg-5 d-none d-md-block align-content-center">
              <img src="https://mdbootstrap.com/img/Photos/new-templates/bootstrap-login-form/draw1.png"
                alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
            </div>
            <div class="col-md-6 col-lg-7 d-flex align-items-center">
              <div class="card-body p-4 p-lg-5 text-black">

                <form action="#" method="POST">

                  <div class="d-flex align-items-center mb-3 pb-1">
                    <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                    <img class="h1 fw-bold mb-0">

                  </div>

                  <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;"><b>Sign into your account</b></h5>

                  <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="form2Example17">Username</label>
                    <input type="text" name="username" id="form2Example17" class="form-control form-control-lg" required>
                  </div>

                  <div data-mdb-input-init class="form-outline mb-4">
                    <label class="form-label" for="form2Example27">Password</label>
                    <input type="password" name="password" id="form2Example27" class="form-control form-control-lg" requried>
                  </div>

                  <div class="pt-1 mb-4">
                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg btn-block" type="submit">Login</button>
                    <span style="color:red;"><?php echo $message; ?></span>
                  </div>

                  <hr class="my-4" />
                  <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a href="register.php"
                      style="color: #393f81;">Register here</a></p>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
</body>
