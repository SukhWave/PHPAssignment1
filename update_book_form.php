<?php
    require_once('database.php');

    // get the bookID
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    // select the book from the database
    $query = 'SELECT * FROM books WHERE bookID = :book_id';
    $statement = $db->prepare($query);
    $statement->bindvalue(':book_id', $book_id);
    $statement->execute();
    $book = $statement->fetch();
    $statement->closeCursor(); 

   // Get book types
   $queryTypes = 'SELECT * FROM types';
   $statement2 = $db->prepare($queryTypes);
   $statement2->execute();
   $types = $statement2->fetchAll();
   $statement2->closeCursor();
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

            <form action="update_book.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="book_id"
                value="<?php echo $book['bookID']; ?>" />
                <div id="data">

                    <label>Book Name:</label>
                    <input type="text" name="book_name"
                       value="<?php echo $book['bookName']; ?>" /><br />

                    <label>Author:</label>
                    <input type="text" name="author"
                       value="<?php echo $book['author']; ?>" /><br />

                    <label>Email Address:</label>
                    <input type="text" name="email_address"
                       value="<?php echo $book['emailAddress']; ?>" /><br />                    

                    <label>Phone Number:</label>
                    <input type="text" name="phone_number"
                       value="<?php echo $book['phone']; ?>" /><br />

                    <label>Status:</label>
                    <input type="radio" name="status" value="member" <?php if ($book['status'] == 'member') echo 'checked'; ?> />Member
                    <input type="radio" name="status" value="nonmember" <?php if ($book['status'] == 'nonmember') echo 'checked'; ?> />Non-Member<br />

                    <label>Published Date:</label>
                    <input type="date" name="published"
                       value="<?php echo $book['published']; ?>" /><br />                    

                    <label>Book Type:</label>
                    <select name="type_id">
                        <?php foreach ($types as $type): ?>
                           <option value="<?php echo $type['typeID']; ?>" <?php if ($type['typeID'] == $book['typeID']) echo 'selected'; ?>>
                                 <?php echo htmlspecialchars($type['bookType']); ?>
                           </option>
                        <?php endforeach; ?>
                    </select><br />

                    <?php if (!empty($book['imageName'])): ?>
                        <label>Current Image:</label>
                        <img src="images/<?php echo htmlspecialchars($book['imageName']); ?>" height="100"><br />
                    <?php endif; ?>

                    <label>Update Image:</label>
                    <input type="file" name="image"><br />
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