<?php
    // resume the session if one is already existing
    session_start();
    // extract data from the session
    $loggedin = false;
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        $user_name = $_SESSION['user_name'];
        $user_email = $_SESSION['user_email'];
        $loggedin = true;
    }
    else
    {
        header("Location: Login.php");
    }

    require "partials/_dbconnect.php";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(isset($_POST)) {
            $data['res'] = false;
            $date = $_POST['date'];
            $time = $_POST['time'];
            // echo $time . " " . $date;
            $table_num = (int)$_POST['table_num'];

            $find = "SELECT * FROM RESERVATION WHERE res_table_num = $table_num AND DATE_SUB(res_time, INTERVAL 30 MINUTE) < TIME('$time') AND end_time > TIME('$time') AND res_date = DATE('$date')";
            $find_res = mysqli_query($conn, $find);
            if(!$find_res) {
                echo "Find Error " . mysqli_connect_error($conn);
            }
            $num_rows = mysqli_num_rows($find_res);
            if($num_rows == 0) {
                $insert = "INSERT INTO RESERVATION(res_user_email, res_table_num, res_date, res_time, end_time) VALUES('$user_email', $table_num, DATE('$date'), TIME('$time'), DATE_ADD(TIME('$time'), INTERVAL 2 HOUR))";
                $insert_res = mysqli_query($conn, $insert);
                if(!$insert_res) {
                    echo "Insert Error " . mysqli_connect_error($conn);
                }
                else {
                    $data['res'] = true;

                    // Php Mailer:
                            require 'PHPMailerAutoload.php';
                            require 'credentials.php';

                            $mail = new PHPMailer;

                            //$mail->SMTPDebug = 4;                               // Enable verbose debug output

                            $mail->isSMTP();                                      // Set mailer to use SMTP
                            $mail->Host = 'smtp.gmail.com;';  // Specify main and backup SMTP servers
                            $mail->SMTPAuth = true;                               // Enable SMTP authentication
                            $mail->Username = EMAIL;                 // SMTP username
                            $mail->Password = PASS;                           // SMTP password
                            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                            $mail->Port = 587;                                    // TCP port to connect to

                            $mail->setFrom(EMAIL, 'Delizioso Restaurant');
                            $mail->addAddress('diveshharyani92@gmail.com');     // Add a recipient   // Name is optional
                            $mail->addReplyTo(EMAIL);

                            // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                            $mail->isHTML(true);                                  // Set email format to HTML

                            $mail->Subject = 'Table is Booked';
                            $mail->Body    = 'Table Number ' . $table_num . ' has been booked for you on ' . $date . ' at ' . $time;
                            $mail->AltBody = 'Table Number ' . $table_num . ' has been booked for you on ' . $date . ' at ' . $time;

                            if(!$mail->send()) {
                                echo 'Message could not be sent.';
                                echo 'Mailer Error: ' . $mail->ErrorInfo;
                            } else {
                                //echo 'Message has been sent';
                            }
                }
            }
            echo json_encode($data);
        }
    }
?>