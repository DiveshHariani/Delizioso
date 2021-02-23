<?php
    session_start();
    if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'])
    {
        // logout procedure
        session_unset();
        session_destroy();
    }
    header("Location: Login.php");
?>