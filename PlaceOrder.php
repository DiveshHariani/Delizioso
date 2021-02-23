<?php
    // PHP routine for ajax request to place order
    require "partials/_dbconnect.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        // resume the session if one is already existing
        session_start();
        // extract data from the session
        if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
        {
            $user_name = $_SESSION['user_name'];
            $user_email = $_SESSION['user_email'];
            $ord_mode = $_SESSION['mode'];
            $ord_dest = $_SESSION['destination'];
        }

        $isWarning = true;
        if(isset($_POST)) {
            // var_dump($_POST);
            if(count($_POST) != 0) {
                $isWarning = false;
                $item_ids = array_map('intval', $_POST['checkbox']);
                $item_qtys = array_map('intval', $_POST['quantity']);
                $item_price = array_map('intval', $_POST['price']);

                for($i=0; $i<count($item_ids); $i++)
                {
                    //echo nl2br($item_ids[$i] . "->" . $item_price[$i] . "*" . $item_qtys[$i] . "=" . $item_price[$i]*$item_qtys[$i] . "\n");
                    $ord_food_id = $item_ids[$i];
                    $ord_quantity = $item_qtys[$i];
                    $ord_price = $item_price[$i]*$item_qtys[$i];
                    
                    $insert_query = "INSERT INTO FOOD_ORDER(ord_user_id, ord_food_id, ord_quantity, ord_price, ord_date, ord_mode, ord_dest)
                                     VALUES('$user_email', $ord_food_id, $ord_quantity, $ord_price, CURDATE(), $ord_mode, '$ord_dest')";
                    $order_result = mysqli_query($conn, $insert_query);
                    if(!$order_result)
                    {
                        die("Insert Error " .mysqli_error($conn));
                    }
                }
                $success['result'] = true;
                echo json_encode($success);
            }
        }
    }
?>