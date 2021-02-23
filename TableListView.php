<?php
    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        $loggedin = true;
    }
    else
    {
        header("Location: Login.php");
    }

    require "partials/_dbconnect.php";
    $select_list = "SELECT * FROM REST_TABLE";
    $query_res = mysqli_query($conn, $select_list);
    if(!$query_res) {
        die("Query error ".mysqli_connect_error());
    }
    $number = mysqli_num_rows($query_res);
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

    <title>Delizioso</title>
  </head>
  <body>
    <?php include "partials/_nav.php" ?>
    <div class="container my-3">
        <h1>Tables</h1>
        <h6 class="mr-sm-2"><a href="userReservation.php">My Reservations</a></h6>
        <?php
        for($i=1; $i <= intdiv($number, 3)+1; $i++) { ?>
            <div class="row my-1">
                <?php
                    $record = mysqli_fetch_assoc($query_res);
                    while($record) {
                        echo "<div class='card' style='width: 18rem; margin: auto;'>
                            <img src='{$record['table_img']}' class='card-img-top' style='height: 300px; width: 18rem; object-fit: cover;'>
                            <div class='card-body'>
                                <h5 class='card-title'>Table# {$record['table_num']}</h5>
                                <p class='card-text'>Capacity = {$record['table_cap']}.</p>
                                <a href='TableDetail.php?table={$record['table_num']}' class='btn btn-primary'>Book</a>
                            </div>
                        </div>";
                        $record = mysqli_fetch_assoc($query_res);
                    }
                ?>
            </div>
        <?php } ?>
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