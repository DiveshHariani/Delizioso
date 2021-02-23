<?php

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        //connect to database
        require 'partials/_dbconnect.php';
        // accept values
        $username = $_POST['username'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $c_pwd = $_POST['c_pwd'];

        // helper values
        $exists = false;
        $showMessage = "";
        $isWarning = false;
        
        // Check whether same email address exists or not:
        $sql = "SELECT * FROM USER WHERE user_email = '$email'";
        $result = mysqli_query($conn, $sql);
        $num_rows = mysqli_num_rows($result);
        
        if($num_rows >= 1) 
        {
            $exists = true;
            $showMessage = "This Email address is already taken";
            $isWarning = true;
        }
        
        // validate password
        if($pwd == $c_pwd) 
        {
            if(!$exists) 
            {
                $insert_sql = "INSERT INTO USER VALUES('$email', SHA1('$pwd'), '$username', 0)";
                // execute
                $res = mysqli_query($conn, $insert_sql);
                if($res) 
                {
                    $showMessage = "Account created successfully. Click here to login";
                    // starting session: 
                    session_start();
                    $_SESSION['loggedIn'] = true;
                    $_SESSION['user_name'] = $username;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_permission'] = 0;

                    if($_SESSION['user_permission'] == "2") {
                        header("Location: AdminPanel.php");
                        exit;
                    }
                    header("Location: Home.php");
                }
                else
                {
                    $showMessage = "Error ".mysqli_connect_error($conn);
                }   
            }
        } 
        else 
        {
            $showMessage = "Password Mismatch";
            $isWarning = true;
        }
    }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Sign up</title>
  </head>
  <body>
  <!-- Conditional Rendering -->
    <?php
    if(isset($showMessage)) {
        $class = "alert-success";
        // echo "$isWarning <br> $showMessage <br>";
        if($isWarning) {
            $class = "alert-warning";
        }
        // echo "$class";

        echo "
        <div class='alert $class alert-dismissible fade show' role='alert'>
            <strong>Message!</strong> $showMessage
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
            </button>
        </div>";
    }
    ?>
    
    <div class="container my-4" style="width: 400px;">
    <h3> Signup </h3>
    <form action="/restaurant/Signup.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="pwd">Password</label>
            <input type="password" class="form-control" id="pwd" name="pwd" required>
        </div>
        <div class="form-group">
            <label for="c_pwd">Confirm password</label>
            <input type="password" class="form-control" id="c_pwd" name="c_pwd" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
    </form>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>