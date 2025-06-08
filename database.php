<?php
    if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
    $dsn = 'mysql:host=localhost;dbname=book_list';
    $username = 'root';
    $password = '';

    try {
        $db = new PDO($dsn, $username, $password);
    }
    catch (PDOException $e)
    {
        $_SESSION["database_error"] = $e->getMessage();
        $url = "database_error.php";
        header("Location: " . $url);
        exit();
    }

?>