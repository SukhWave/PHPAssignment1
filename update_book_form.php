<?php
    require_once('database.php');
    // get the data from the form 
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    // select the book from the database
    $query = 'SELECT * FROM books WHERE bookID = :book_id';

    $statement = $db->prepare($query);
    $statement->bindvalue(':book_id', $book_id);

    $statement->execute();
    $book = $statement->fetch();
    $statement->closeCursor(); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Manager - Update book</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Update book</h2>

            <form action="update_book.php" method="post" id="update_book_form"
                enctype="multipart/form-data">
            
                <div id="data">

                    <input type="hidden" name="book_id"
                       value="<?php echo $book['bookID']; ?>" />

                    <label>Book Name:</label>
                    <input type="text" name="book_name"
                       value="<?php echo $book['firstName']; ?>" /><br />

                    <label>Author:</label>
                    <input type="text" name="Author"
                       value="<?php echo $book['author']; ?>" /><br />

                    <label>Email Address:</label>
                    <input type="text" name="email_address"
                       value="<?php echo $book['emailAddress']; ?>" /><br />                    

                    <label>Phone Number:</label>
                    <input type="text" name="phone_number"
                       value="<?php echo $book['phone']; ?>" /><br />

                    <label>Status:</label>
                    <input type="radio" name="status" value="member"
                       <?php echo ($book['status'] == 'member') ? 'checked' : ''; ?> />Member<br />                    
                    <input type="radio" name="status" value="nonmember"
                       <?php echo ($book['status'] == 'nonmember') ? 'checked' : ''; ?> />Non-Member<br />

                    <label>Published Date:</label>
                    <input type="date" name="published"
                       value="<?php echo $book['published']; ?>" /><br />                    

                  </div>

                  <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Update book" /><br />                      

                  </div>

            </form>

            <p><a href="index.php">View book List</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>