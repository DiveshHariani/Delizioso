<?php

    require "partials/_dbconnect.php";

    if($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $success['result'] = false;
        if(isset($_POST)) {
            /// var_dump($_POST);
            if(count($_POST) != 0) {
                $del_ids = array_map('intval', $_POST['del_ids']);

                for($i=0; $i<count($del_ids); $i++)
                {
                    //echo nl2br($item_ids[$i] . "->" . $item_price[$i] . "*" . $item_qtys[$i] . "=" . $item_price[$i]*$item_qtys[$i] . "\n");
                    $res_id = $del_ids[$i];
                    
                    $update_query = "DELETE FROM RESERVATION WHERE res_id = {$res_id}";
                    $update_result = mysqli_query($conn, $update_query);
                    if(!$update_result)
                    {
                        die("Insert Error " .mysqli_error($conn));
                        $success['result'] = false;
                        echo json_encode($success);
                        exit;
                    }
                }
                $success['result'] = true;
                echo json_encode($success);
            }
        }
    }