<?php
require_once("connection.php");
error_reporting(0);
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST['f_name']) || 
    empty($_POST['l_name'])|| 
    empty($_POST['email']) ||  
    empty($_POST['phone'])||
    empty($_POST['password'])||
    empty($_POST['cpassword']) ||
    empty($_POST['username'])) {
    $message = "Please fill all the fields!";
  } else {
    $check_username = mysqli_query($conn, "SELECT username FROM user WHERE username = '".$_POST['username']."'");
    $check_email = mysqli_query($conn, "SELECT email FROM user WHERE email = '".$_POST['email']."'");

    if($_POST['password'] != $_POST['cpassword']) {
      $message = "Passwords do not match!";
    } elseif(strlen($_POST['password']) < 6) {
      $message = "Password must be at least 6 characters!";
    } elseif(strlen($_POST['phone']) < 10) {
      $message = "Invalid phone number!";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
      $message = "Invalid email address!";
    } elseif(mysqli_num_rows($check_username) > 0) {
      $message = "Username already exists!";
    } elseif(mysqli_num_rows($check_email) > 0) {
      $message = "Email already exists!";
    } else {
      // $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
      // $sql = "INSERT INTO user (username, f_name, l_name, email, phone, password, address) 
      //         VALUES ('".$_POST['username']."', '".$_POST['f_name']."', '".$_POST['l_name']."', '".$_POST['email']."', '".$_POST['phone']."', '".$hashed_password."', '".$_POST['address']."')";
      $password = $_POST['password'];
      $sql = "INSERT INTO user (username, f_name, l_name, email, phone, password, address) 
              VALUES ('".$_POST['username']."', '".$_POST['f_name']."', '".$_POST['l_name']."', '".$_POST['email']."', '".$_POST['phone']."', '".$password."', '".$_POST['address']."')";
              
      if(mysqli_query($conn, $sql)) {
        header("Location: login.php");
      } else {
        $message = "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    }
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
          if (!empty($_SESSION['username'])) {
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
<section class="h-100" src="img/bckground.jpg">
  <div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col">
        <div class="card card-registration my-4">
          <div class="row g-0">
            <div class="col-xl-6 d-none d-xl-block align-content-center">
              <img src="https://mdbootstrap.com/img/Photos/new-templates/bootstrap-login-form/draw1.png"
                alt="Sample photo" class="img-fluid"
                style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
            </div>
            <div class="col-xl-6">
              <div class="card-body p-md-5 text-black">
                <form action="#", method="post">
                <h3 class="mb-5 text-uppercase">Account registration</h3>

                <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="form3Example8">Username</label>
                  <input type="text" name="username" id="form3Example8" class="form-control form-control-lg" />
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="form3Example1m">First name</label>
                      <input type="text" name="f_name" id="form3Example1m" class="form-control form-control-lg" />
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="form3Example1n">Last name</label>
                      <input type="text" name="l_name" id="form3Example1n" class="form-control form-control-lg" />
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="form3Example1m1">Email</label>
                      <input type="text" name="email" id="form3Example1m1" class="form-control form-control-lg" />
                    </div>
                  </div>
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="form3Example1n1">Phone Number</label>
                      <input type="text" name="phone" id="form3Example1n1" class="form-control form-control-lg" />
                    </div>
                  </div>
                </div>

                <div data-mdb-input-init class="form-outline mb-4">
                  <label class="form-label" for="form3Example8">Address</label>
                  <input type="text" name="address" id="form3Example8" class="form-control form-control-lg" />
                </div>

                <div class="row">
                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="form3Example1m1">Password</label>
                      <input type="password" name="password" id="form3Example1m1" class="form-control form-control-lg" />
                    </div>
                  </div>

                  <div class="col-md-6 mb-4">
                    <div data-mdb-input-init class="form-outline">
                      <label class="form-label" for="form3Example1n1">Confirm Password</label>
                      <input type="password" name="cpassword" id="form3Example1n1" class="form-control form-control-lg" />
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-end pt-3">
                  <button  type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-light btn-lg">Reset all</button>
                  <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-lg ms-2">Register</button>

                </div>
                <span style="color:red;"><?php echo $message; ?></span>

                <hr class="my-4" />
                <p class="mb-5 pb-lg-2" style="color: #393f81;">Already have an account? <a href="login.php"
                      style="color: #393f81;">Login here</a></p>

              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  
</section>
</body>


