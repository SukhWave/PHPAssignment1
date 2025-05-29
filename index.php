<?php
    session_start();
    require("database.php");
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();

    $statement1->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book List - Home</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Book List</h2>

            <table>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Genre</th>
                    <th>Photo</th>
                    <th>&nbsp;</th> <!-- for edit button -->
                    <th>&nbsp;</th> <!-- for delete button -->
                </tr>

                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo $book['bookName']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td><?php echo $book['emailAddress']; ?></td>
                        <td><?php echo $book['phone']; ?></td>
                        <td><?php echo $book['status']; ?></td>
                        <td><?php echo $book['published']; ?></td>
                        <td><?php echo $book['genre']; ?></td>                        
                        <td><img src="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" alt="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" style="width:auto; height: 100px;" /></td>
                        <td>
                            <form action="update_book_form.php" method="post">
                                <input type="hidden" name="book_id"
                                    value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Update" />   
                            </form>
                        </td> <!-- for edit button -->
                        <td>
                            <form action="delete_book.php" method="post">
                                <input type="hidden" name="book_id"
                                    value="<?php echo $book['bookID']; ?>" />
                                <input type="submit" value="Delete" />   
                            </form>
                        </td> <!-- for delete button -->
                    </tr>

                <?php endforeach; ?>

            </table>
            <p><a href="add_book_form.php">Add Book</a></p>            
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>