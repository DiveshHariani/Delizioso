<?php
    /*
    This accepts the ajax request to save feedbacks
    */

    require 'partials/_dbconnect.php';

    session_start();
    if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"]) {
        $user_email = $_SESSION['user_email'];
    }

    if($_SERVER['REQUEST_METHOD'] == "POST") {
        $data['result'] = false;

        if(isset($_POST)) {
            $rating = $_POST['rating'];
            $review = $_POST['review'];

            $sql_query = "INSERT INTO FEEDBACK(rating, feed_date, review, cust_email) VALUES($rating, CURDATE(), '$review', '$user_email')";
            $result = mysqli_query($conn, $sql_query);
            if($result) {
                $data['result'] = true;
            } else {
                echo "Error " . mysqli_connect_error($conn);
            }
        }
        echo json_encode($data);
    }
?>