<?php
include('../include/db.php');

session_start();
if (isset($_SESSION['user_success_id'])) {
    header('location:index.php');
}

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $new_password = $_POST['password'];

    // Update the password in the librarian table
    $sql = "UPDATE `libraian` SET `password`='$new_password' WHERE `username`='$username' AND `email`='$email'";
    $result = mysqli_query($con, $sql);

    if ($result) {
        echo "<script>alert('The Password Updated Successfully.');</script>";
    } else {
        echo "<script>alert('Error updating password. Please try again.');</script>";
    }
}
?>

<!doctype html>
<html lang="en" class="fixed accounts sign-in">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title> Forgot Password</title>
    <link rel="stylesheet" href="../assets/vendor/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/vendor/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" href="../assets/vendor/animate.css/animate.css">
    <link rel="stylesheet" href="../assets/stylesheets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .wrapper {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .wrapper h1 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .input-group {
            margin-bottom: 15px;
        }
        .input-group .form-control {
            border-left: 0;
            height: 45px;
            font-size: 16px;
        }
        .input-group .input-group-addon {
            background-color: #f8f9fa;
            border-right: 0;
            border-color: #ced4da;
            font-size: 18px;
            padding: 10px 15px;
        }
        .btn-default {
            background-color: #28a745;
            color: #fff;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            font-size: 18px;
            border: none;
        }
        .btn-default:hover {
            background-color: #218838;
        }
        .text-center a {
            color: #007bff;
            font-size: 14px;
            text-decoration: none;
            display: block;
            margin-top: 10px;
        }
        .text-center a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h1 class="text-center"> Forgot Password</h1>
        <form action="" method="post">
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>
            <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="New Password" required>
            </div>
            <button class="btn btn-default" type="submit" name="submit">Reset Password</button>
        </form>
        <div class="text-center">
            <a href="login.php">Back to Login</a>
        </div>
    </div>
    <script src="../assets/vendor/jquery/jquery-1.12.3.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/vendor/nano-scroller/nano-scroller.js"></script>
    <script src="../assets/javascripts/template-script.min.js"></script>
    <script src="../assets/javascripts/template-init.min.js"></script>
</body>
</html>
