<?php
    $servername = "localhost";
    $username = "root";
    $password = "password";
    $database = "restaurant";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if(!$conn) {
        die('Connection unsucessful');
    }
?>