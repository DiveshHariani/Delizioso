<?php
    require "partials/_dbconnect.php";

    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        if($_SESSION['user_permission'] != 2) {
            header("Location: Home.php");
            exit;
        }
        $loggedin = true;
    }
    else
    {
        header("Location: Login.php");
    }

    $sql = "SELECT o.order_id, i.item_name, o.ord_quantity, o.ord_date, o.ord_isComp, o.ord_mode, o.ord_dest 
            FROM FOOD_ORDER o
            INNER JOIN ITEM i
            ON i.item_id = o.ord_food_id
            WHERE o.ord_date = CURDATE() AND o.ord_isComp = FALSE";
    
    $query_res = mysqli_query($conn, $sql);
    if(!$query_res) {
        die("Query error " . mysqli_connect_error());
    }

    $table_sql = "SELECT res_id, res_user_email, res_table_num, res_date, res_time FROM RESERVATION";
    $table_query_res = mysqli_query($conn, $table_sql);
    if(!$table_query_res) {
        die("Query error " . mysqli_connect_error());
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
    <div class="container my-2" style="text-align: center;">
        <h2> Admin Panel </h2>
        <div class="container" style="text-align: left;">
            <h3> Remaining orders: </h3>
            <form>
                <?php
                    if(mysqli_num_rows($query_res) == 0) {
                        echo "<h4> No order found </h4>";
                    }
                    else {
                ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th scope="col"></th>
                        <th scope="col">Name</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Destination</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $record = mysqli_fetch_assoc($query_res);
                            while($record) {
                                if($record['ord_mode'] == "1") {
                                    $destination = "#Table: {$record['ord_dest']}";
                                }
                                else {
                                    $destination = $record['ord_dest'];
                                }
                                echo "
                                    <tr>
                                        <th scope=\"row\">
                                            <div class=\"form-check\">
                                                <input class=\"form-check-input checks\" type=\"checkbox\" value='{$record['order_id']}' name=\"Completed[]\" >
                                            </div>
                                        </th>
                                        <td>{$record['item_name']}</td>
                                        <td>{$record['ord_quantity']}</td>
                                        <td>{$destination}</td>
                                    </tr>";

                                $record = mysqli_fetch_assoc($query_res);
                            }
                        ?>
                    </tbody>
                </table>

                <button id="applyChanges" type="button" class="btn btn-success">Apply</button>
                <?php } ?>
            </form>
            <h3> Reservations: </h3>               
            <form>
                <?php
                    $T_num_rows = mysqli_num_rows($table_query_res);
                    if($T_num_rows == 0) {
                        echo "<h4> No reservation found </h4>";
                    }
                    else { ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th></th>
                        <th scope="col">Customer</th>
                        <th scope="col">Table #</th>
                        <th scope="col">Date</th>
                        <th scope="col">Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $T_record = mysqli_fetch_assoc($table_query_res);
                            while($T_record) {
                                echo "
                                    <tr>
                                        <th scope=\"row\">
                                            <div class=\"form-check\">
                                                <input class=\"form-check-input checks\" type=\"checkbox\" value='{$T_record['res_id']}' name=\"Delete[]\" >
                                            </div>
                                        </th>
                                        <td>{$T_record['res_user_email']}</td>
                                        <td>{$T_record['res_table_num']}</td>
                                        <td>{$T_record['res_date']}</td>
                                        <td>{$T_record['res_time']}</td>
                                    </tr>";
                                $T_record = mysqli_fetch_assoc($table_query_res);
                            }
                        
                        ?>
                    </tbody>
                </table>

                <button id="applyChangesRes" type="button" class="btn btn-success">Apply</button>
                <?php } ?>
            </form>
        </div>
    </div>

    <script>
        $("#applyChanges").click(function(event) {
            event.preventDefault();

            let completed_order_list = [];
            if($("input[name='Completed[]']:checked").length != 0) 
            {
                $("input[name='Completed[]']:checked").each(function() {
                    //console.log($(this).val());
                    // get all item details
                    completed_order_list.push($(this).val());
                    console.log(completed_order_list);
                });

                jQuery.noConflict();
                $.ajax({
                    type: "POST",
                    url: "OrderStatusUpdate.php",
                    data: {
                        'order_ids': completed_order_list
                    },
                    success: function(data) {
                        console.log(data);
                        data = JSON.parse(data)
                        console.log(typeof data);
                        console.log(data.result);
                        if(data['result']) {
                            alert("successfully Updated");
                            location.reload(true);
                        }
                    }
                });
            }
        });

        $("#applyChangesRes").click(function(event) {
            event.preventDefault();

            let del_res_list = [];
            if($("input[name='Delete[]']:checked").length != 0) 
            {
                $("input[name='Delete[]']:checked").each(function() {
                    //console.log($(this).val());
                    // get all item details
                    del_res_list.push($(this).val());
                    console.log(del_res_list);
                });

                jQuery.noConflict();
                $.ajax({
                    type: "POST",
                    url: "ResCancel.php",
                    data: {
                        'del_ids': del_res_list
                    },
                    success: function(data) {
                        console.log(data);
                        data = JSON.parse(data)
                        console.log(typeof data);
                        console.log(data.result);
                        if(data['result']) {
                            alert("successfully Updated");
                            location.reload(true);
                        }
                    }
                });
            }
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