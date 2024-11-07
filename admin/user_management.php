<?php

include("../connection.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Management Page</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="../css/add_food.css">
  <link rel="stylesheet" href="../css/admin_main.css">
</head>
<body>
  <header>
    <img src="../img/logo2.png" height="50px" alt="logo2">
    <nav>
        <a href="dashboard.php">DASHBOARD</a>
        <a href="user_management.php">USER</a>
        <a href="add_fooditem.php">FOOD</a>
        <a href="all_order.php">ORDER</a>
        <a href="../logout.php">Logout</a>
    </nav>
  </header>
  <div class="page-wrapper">
  <div class="container-fluid">
    <div style="padding-top : 24px;"></div>
    <div class="row justify-content-center">
      <div class="col-11 center">
        <div class="col-lg-12" >
          <div class="card card-outline-primary">
            <div class="card-header">
              <h4 class="m-b-0 text-black">All Users</h4>
            </div>
            <div class="table-responsive m-t-40">
              <table id="userTable" class="table table-bordered table-striped table-hover">
                <thread class="thead-dark">
                  <tr>
                    <th>Username</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Registration Date</th>
                    <th>Action</th>
                  </tr>
                </thread>
                <tbody>

                  <?php
                    $sql = "SELECT * FROM user order by id desc";
                    $query = mysqli_query($conn, $sql);

                    if(!mysqli_num_rows($query) > 0)
                    {
                      echo '<td colspan="7"><center>No Users</center></td>';
                    }
                    else
                    {
                      while($row = mysqli_fetch_assoc($query))
                      {
                        if($row['role']!="admin")
                        {
                          echo '<tr>
                                <td>'.$row['username'].'</td>
                                <td>'.$row['f_name'].'</td>
                                <td>'.$row['l_name'].'</td>
                                <td>'.$row['email'].'</td>
                                <td>'.$row['phone'].'</td>
                                <td>'.$row['address'].'</td>
                                <td>'.$row['date'].'</td>
                                <td>
                                  <a href="user_update.php?uid='.$row['id'].'" class="btn btn-primary btn-sm">Edit</a>
                                  <a href="delete_user.php?uid='.$row['id'].'" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                              </tr>';
                        }
                      }
                    }

                  ?>

                </tbody>
              </table>
                  </div>
                  </div>
                  </div>
                  </div>
                  </div>
                  </div>
                  </div>
                  
</body>
