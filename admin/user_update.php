<?php
session_start();
error_reporting(0);
include("../connection.php");

if($_SERVER["REQUEST_METHOD"] == "POST")
{
    if(empty($_POST['username']) ||
    empty($_POST['f_name'])|| 
    empty($_POST['l_name']) ||  
    empty($_POST['email'])||
    empty($_POST['phone'])||
    empty($_POST['address']))
    {
        $error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                      <strong>All fields Required!</strong>
                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  </div>';
    }
    else
    {
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) // Validate email address
        {
            $error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <strong>Invalid email!</strong>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
        elseif(strlen($_POST['phone']) < 10)
        {
            $error = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                          <strong>Invalid phone!</strong>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      </div>';
        }
        else
        {
            $mql = "UPDATE user SET username='$_POST[username]', f_name='$_POST[f_name]', l_name='$_POST[l_name]', email='$_POST[email]', phone='$_POST[phone]', address='$_POST[address]' where id='$_GET[uid]' ";
            mysqli_query($conn, $mql);
            $success = '<div class="alert alert-success alert-dismissible fade show" role="alert">
                          <strong>User Updated!</strong>
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
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
  <title>Edit User Information Page</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../css/add_food.css">
  <link rel="stylesheet" href="../css/admin_main.css">
  <link rel="stylesheet" href="../css/edit_user.css">
</head>
<body>
  <header>
    <img src="../img/logo2.png" height="50px" alt="logo2">
    <nav>
        <a href="">DASHBOARD</a>
        <a href="user_management.php">USER</a>
        <a href="add_fooditem.php">FOOD</a>
        <a href="all_order.php">ORDER</a>
        <a href="../logout.php">Logout</a>
    </nav>
  </header>
  <div class="container-fluid">
    <?php  
      echo $error;
      echo $success; 
    ?>
    <div style="padding-top : 24px;"></div>
    <div class="row justify-content-center">
      <div class="col-11 center">
        <div class="col-lg-12" >
          <div class="card card-outline-primary">
            <div class="card-header">
              <h4 class="m-b-0 text-black">All Users</h4>
            </div>
            <div class="card-body">
              <?php 
              $ssql = "SELECT * FROM user WHERE id = '$_GET[uid]'";
              $res = mysqli_query($conn, $ssql);
              $row = mysqli_fetch_array($res);
              ?>
              <form action='' method='POST'>
                <div class="form-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Username</label>
                        <input type="text" id="Username" name="username" class="form-control" value="<?php echo $row['username']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Email</label>
                        <input type="text" id="Email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">First Name</label>
                        <input type="text" id="firstName" name="f_name" class="form-control" value="<?php echo $row['f_name']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Last Name</label>
                        <input type="text" id="lastName" name="l_name" class="form-control" value="<?php echo $row['l_name']; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Phone Number</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $row['phone']; ?>" required>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="control-label">Address</label>
                        <input type="text" id="address" name="address" class="form-control" value="<?php echo $row['address']; ?>" required>
                      </div>
                    </div>
                  </div>
                  <div class="form-actions">
                    <input type="submit" name="update" class="btn btn-primary" value="Save">
                    <a href="user_management.php" class="btn btn-inverse">Cancel</a>
                  </div>
                </div>    
              </form>
            </div>
          </div>
        </div>
       </div>
      </div>
    </div>
</body>
</html>
