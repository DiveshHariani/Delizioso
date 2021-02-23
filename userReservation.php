<?php
    require "partials/_dbconnect.php";

    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_email = $_SESSION['user_email'];
    }
    else
    {
        header("Location: Login.php");
    }

    $table_sql = "SELECT res_id, res_user_email, res_table_num, res_date, res_time FROM RESERVATION
                    WHERE res_user_email = '$user_email' AND res_date >= CURDATE()";
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
        <h2> Your Reservations </h2>
        <div class="container" style="text-align: left;">
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

                <button id="applyChangesRes" type="button" class="btn btn-success">Cancel Reservations</button>
                <?php } ?>
            </form>
        </div>
    </div>
    <!--------------------------------------------------------------------------------------------------------->
    <script>

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
                alert("Cancellation Charges = " + 50*$("input[name='Delete[]']:checked").length);
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
  </body>
</html>