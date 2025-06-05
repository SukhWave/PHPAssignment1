<?php
    session_start();

    // get data from the form
    $user_name = filter_input(INPUT_POST, 'user_name');    
    $password = filter_input(INPUT_POST, 'password');

    $_SESSION["pass"] = $password;  
    
    require_once('database.php');

    $query = 'SELECT password FROM registrations
                WHERE userName = :userName';
    $statement1 = $db->prepare($query);

    $statement1->bindValue(':userName', $user_name);

    $statement1->execute();
    $row = $statement1->fetch();    

    $statement1->closeCursor();

    $hash = $row['password'];

    $_SESSION["isLoggedIn"] = password_verify($_SESSION["pass"], $hash);

    if ($_SESSION["isLoggedIn"] == TRUE)
    {
        $_SESSION["userName"] = $user_name;
        $_SESSION["password"] = $password;
        $_SESSION["hash"] = $hash;

        $url = "login_confirmation.php";
        header("Location: " . $url);
        die();
    }
    elseif ($_SESSION["isLoggedIn"] == FALSE)
    {
        $_SESSION = [];
        session_destroy();

        $url = "login_form.php";
        header("Location: " . $url);
        die();
    }
    else
    {
        $_SESSION = [];
        session_destroy();

        $url = "login_form.php";
        header("Location: " . $url);
        die();
    }    

?>