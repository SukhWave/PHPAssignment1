<?php
    session_start();

    $_SESSION = []; // Clear all session data
    session_destroy(); // Clean up the session ID

    $url = "login_form.php";
    header("Location: " . $url);
    die();
?>