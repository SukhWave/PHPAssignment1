<?php
    session_start();    
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Manager - Registration Confirmation</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Registration Confirmation</h2>
            <p>
                Thank you, <?php echo $_SESSION["userName"]; ?> for
                registering.
            </p>

            <p>You are logged in and may proceed to the book list by clicking below.</p>
            
            <p><a href="index.php">Book List</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>