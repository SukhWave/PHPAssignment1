<?php
    require_once("database.php");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Manager - Register</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Register</h2>

            <form action="register_book.php" method="post" id="register_book_form">

                <div id="data">

                    <label>Username:</label>
                    <input type="text" name="user_name" /><br />

                    <label>Password:</label>
                    <input type="password" name="password" /><br />                    

                </div>

                <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Register" /><br />

                </div>

            </form>            
            
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>