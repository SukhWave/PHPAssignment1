<?php
session_start();
require_once("database.php");

// Get book ID from POST request
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
if (!$book_id) {
    header("Location: index.php");
    exit;
}

// Fetch book info with type name
$query = 'SELECT b.*, t.bookType FROM books b LEFT JOIN types t ON b.typeID = t.typeID WHERE bookID = :book_id';
$statement = $db->prepare($query);
$statement->bindValue(':book_id', $book_id);
$statement->execute();
$book = $statement->fetch();
$statement->closeCursor();

if (!$book) {
    echo "Book not found.";
    exit;
}

// Convert _100 image to _400 version
$imageName = $book['imageName'];
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);

if (str_ends_with($baseName, '_100')) {
    $baseName = substr($baseName, 0, -4);
}
$imageName_400 = $baseName . '_400' . $extension;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
</head>
<body>
    <?php include("header.php"); ?>

    <div class="container">
        <h2>Book Details</h2>

        <img class="book-image" src="<?php echo htmlspecialchars('./images/' . $imageName_400); ?>" 
             alt="<?php echo htmlspecialchars($book['bookName']); ?>" />

        <div class="book-info">
            <p><strong>Title:</strong> <?php echo htmlspecialchars($book['bookName']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($book['author']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($book['emailAddress']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($book['phone']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($book['status']); ?></p>
            <p><strong>Published Date:</strong> <?php echo htmlspecialchars($book['published']); ?></p>
            <p><strong>Book Type:</strong> <?php echo htmlspecialchars($book['bookType']); ?></p>
        </div>

        <a class="back-link" href="index.php">← Back to Book List</a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
