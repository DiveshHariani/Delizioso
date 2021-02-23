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

    if($_SERVER["REQUEST_METHOD"] == "GET") {
        if(isset($_GET['table']))
        {
            $TableNumber = $_GET['table'];
            $query = "SELECT * FROM REST_TABLE WHERE table_num = $TableNumber";
            $query_res = mysqli_query($conn, $query);
            $record = mysqli_fetch_assoc($query_res);
            if(!$query_res) {
                die("Error " . mysqli_connect_error($conn));
            }

            if(isset($_GET['date'])) {
                // echo $_GET['date'];
                $fetchTableQuery = "SELECT * FROM RESERVATION WHERE res_table_num = $TableNumber AND res_date = DATE('{$_GET['date']}')";
                $fetchedTables = mysqli_query($conn, $fetchTableQuery);
                if(!$fetchedTables) {
                    die("Error " . mysqli_connect_error($conn));
                }
            }
            else 
            {
                $fetchTableQuery = "SELECT * FROM RESERVATION WHERE res_table_num = $TableNumber";
                $fetchedTables = mysqli_query($conn, $fetchTableQuery);
                if(!$fetchedTables) {
                    die("Error " . mysqli_connect_error($conn));
                }
            }
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

    <!-- Jquery -->
    <script
    src="https://code.jquery.com/jquery-3.4.1.min.js"
    integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
    crossorigin="anonymous"></script>

    <title>Delizioso!</title>
  </head>
  <body>
    <?php require "partials/_nav.php" ?>
    <div class="container my-4">
        <div class="row my-5">
            <div class="col-sm">
                <form>
                    <label for="date">Date: </label>
                    <input type="date" name="res_date" id="date" required>
                    
                    <label for="time">Time: </label>
                    <input type="time" name="res_time" id="time" required>
                    <br>
                    <button id="book" class="btn btn-success" style="width: 380px;">Book</button>
                </form>
                <br>
                    <button id="check" class="btn btn-primary" style="width: 380px;">Check for Reservation</button>
                <br>
                <br>
                <h5> Table is already booked for following schedule </h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col"><h5>Date</h5></th>
                            <th scope="col"><h5>From</h5></th>
                            <th scope="col"><h5>To</h5></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $tableReservation = mysqli_fetch_assoc($fetchedTables);
                            while($tableReservation) {
                                echo "
                                    <tr>
                                        <td scope='col'>{$tableReservation['res_date']}</td>
                                        <td scope='col'>{$tableReservation['res_time']}</td>
                                        <td scope='col'>{$tableReservation['end_time']}</td>
                                    </tr>
                                ";
                                $tableReservation = mysqli_fetch_assoc($fetchedTables);
                            }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="col-sm">
                <?php
                    echo "<div class='card' style='width: 18rem; margin: auto;'>
                        <img src='{$record['table_img']}' class='card-img-top' style='height: 300px; width: 18rem; object-fit: cover;'>
                        <div class='card-body'>
                            <h5 class='card-title'>Table# {$record['table_num']}</h5>
                            <p class='card-text'>Capacity = {$record['table_cap']}.</p>
                        </div>
                        <input type='hidden' id='table_num' value='{$record['table_num']}' />
                    </div>";
                ?>
            </div>
        </div>
    </div>

    <script>
        $("#book").click(function(e) {
            e.preventDefault();
            let table_num = $("#table_num").val();
            let date = $("#date").val();
            let time = $("#time").val();

            jQuery.noConflict();
            $.ajax({
                type: "POST",
                url: "TableBooking.php",
                data: {
                    'date': date,
                    'time': time,
                    'table_num': table_num
                },
                success: function(data) {
                    console.log(data);
                    data = JSON.parse(data);
                    if(data.res == true)
                        alert("Table Booked");
                    else
                        alert("Table is already booked for this time");
                }
            })
        });

        $("#check").click(function() {
            console.log("changed");
            let date = $("#date").val();

            let tableNumber = <?= $TableNumber ?>;
            window.location.href = "http://localhost/restaurant/TableDetail.php?table="+tableNumber+"&date="+date;
        });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script> -->

    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
    -->
  </body>
</html>