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
        <link rel="stylesheet" type="txt/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Book List</h2>

            <table>
                <tr>
                    <th>Book Name </th>
                    <th>Author </th>
                    <th>Email Address </th>
                    <th>Phone Number </th>
                    <th>Status </th>
                    <th>Published </th>
                </tr>

                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo $book['bookName']; ?></td>
                        <td><?php echo $book['author']; ?></td>
                        <td><?php echo $book['emailAddress']; ?></td>
                        <td><?php echo $book['phone']; ?></td>
                        <td><?php echo $book['status']; ?></td>
                        <td><?php echo $book['published']; ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>