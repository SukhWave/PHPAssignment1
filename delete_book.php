<?php
    require_once('database.php');
    // get the data from the form 
    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

    // code to delete book from database
    // validate inputs
    if ($book_id != false)
    {
        // delete the book from the database
    $query = 'DELETE FROM books WHERE bookID = :book_id';

    $statement = $db->prepare($query);
    $statement->bindvalue(':book_id', $book_id);

    $statement->execute();
    $statement->closeCursor();
    }

    // reload the index page
    $url = "index.php";
    header("Location: " . $url);
    die(); 
?>