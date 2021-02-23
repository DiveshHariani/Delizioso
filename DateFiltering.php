<?php
    //echo "Date Filtering";
    require "partials/_dbconnect.php";

    $date = $_POST['date'];
    $query = "SELECT * FROM RESERVATION WHERE res_date = $date";
    $res = mysqli_query($conn, $query);
    if(!res) {
        echo "Error " . mysqli_connect_error($conn);
    } else {
        $data
    }
?>