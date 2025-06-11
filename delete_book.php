<?php
require_once('database.php');

// Get the book ID from the form
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

if ($book_id != false) {
    // Step 1: Get the image name before deleting
    $query = 'SELECT imageName FROM books WHERE bookID = :book_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':book_id', $book_id);
    $statement->execute();
    $book = $statement->fetch();
    $statement->closeCursor();

    if ($book && !empty($book['imageName'])) {
        $filename = $book['imageName'];
        $dot = strrpos($filename, '.');
        $basename = substr($filename, 0, $dot);
        $ext = substr($filename, $dot);

        // If filename ends with _100, trim it
        if (str_ends_with($basename, '_100')) {
            $basename = substr($basename, 0, -4);
        }

        // File paths
        $original = "images/" . $basename . $ext;
        $image100 = "images/" . $basename . "_100" . $ext;
        $image400 = "images/" . $basename . "_400" . $ext;

        // Delete files if they exist
        if (file_exists($original)) unlink($original);
        if (file_exists($image100)) unlink($image100);
        if (file_exists($image400)) unlink($image400);
    }

    // Step 2: Now delete the book from the DB
    $query = 'DELETE FROM books WHERE bookID = :book_id';
    $statement = $db->prepare($query);
    $statement->bindValue(':book_id', $book_id);
    $statement->execute();
    $statement->closeCursor();
}

// Step 3: Redirect to index
header("Location: index.php");
exit;
?>
