<?php

    require "partials/_dbconnect.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $successp['result'] = false;
        if(isset($_POST)) {
            /// var_dump($_POST);
            if(count($_POST) != 0) {
                $isWarning = false;
                $order_ids = array_map('intval', $_POST['order_ids']);

                for($i=0; $i<count($order_ids); $i++)
                {
                    //echo nl2br($item_ids[$i] . "->" . $item_price[$i] . "*" . $item_qtys[$i] . "=" . $item_price[$i]*$item_qtys[$i] . "\n");
                    $ord_id = $order_ids[$i];
                    
                    $update_query = "UPDATE FOOD_ORDER SET ord_isComp = True WHERE order_id = {$ord_id}";
                    $order_result = mysqli_query($conn, $update_query);
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