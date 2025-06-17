<?php
session_start();
require_once("database.php");

// Get book ID from POST or GET (in case of redirect after rating submission)
$book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT) ?? filter_input(INPUT_GET, 'book_id', FILTER_VALIDATE_INT);
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

// Convert image to _400 size
$imageName = $book['imageName'];
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);
if (str_ends_with($baseName, '_100')) {
    $baseName = substr($baseName, 0, -4);
}
$imageName_400 = $baseName . '_400' . $extension;

// Handle rating submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['rating']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);

    if ($rating >= 1 && $rating <= 5) {
        // Check if user has already rated
        $checkQuery = 'SELECT * FROM ratings WHERE bookID = :book_id AND userID = :user_id';
        $stmt = $db->prepare($checkQuery);
        $stmt->execute([':book_id' => $book_id, ':user_id' => $userID]);

        if ($stmt->rowCount() > 0) {
            // Update rating
            $updateQuery = 'UPDATE ratings SET rating = :rating WHERE bookID = :book_id AND userID = :user_id';
            $updateStmt = $db->prepare($updateQuery);
            $updateStmt->execute([
                ':rating' => $rating,
                ':book_id' => $book_id,
                ':user_id' => $userID
            ]);
        } else {
            // Insert new rating
            $insertQuery = 'INSERT INTO ratings (bookID, userID, rating) VALUES (:book_id, :user_id, :rating)';
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->execute([
                ':book_id' => $book_id,
                ':user_id' => $userID,
                ':rating' => $rating
            ]);
        }

        header("Location: book_details.php?book_id=$book_id");
        exit;
    }
}

// Fetch average rating
$avgQuery = 'SELECT AVG(rating) AS avgRating FROM ratings WHERE bookID = :book_id';
$avgStmt = $db->prepare($avgQuery);
$avgStmt->execute([':book_id' => $book_id]);
$avgResult = $avgStmt->fetch();
$averageRating = $avgResult['avgRating'] ? round($avgResult['avgRating'], 1) : null;

// Fetch user's previous rating if logged in
$userRating = null;
if (isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];
    $userRatingQuery = 'SELECT rating FROM ratings WHERE bookID = :book_id AND userID = :user_id';
    $userRatingStmt = $db->prepare($userRatingQuery);
    $userRatingStmt->execute([':book_id' => $book_id, ':user_id' => $userID]);
    $userRatingRow = $userRatingStmt->fetch();
    $userRating = $userRatingRow['rating'] ?? null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Details</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <style>
        .rating-form select {
            padding: 4px;
        }
        .rating-form {
            margin-top: 15px;
        }
        .rating-display {
            font-size: 1.2em;
            color: #ff9900;
        }
    </style>
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

            <p class="rating-display">
                <strong>Average Rating:</strong>
                <?php echo $averageRating ? "$averageRating / 5" : "Not rated yet"; ?>
            </p>

            <?php if (isset($_SESSION['userID'])): ?>
                <form method="post" class="rating-form">
                    <input type="hidden" name="book_id" value="<?php echo $book_id; ?>">
                    <label for="rating">Your Rating:</label>
                    <select name="rating" id="rating">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            $selected = ($userRating == $i) ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>$i</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Submit</button>
                </form>
            <?php else: ?>
                <p><em><a href="login.php">Log in</a> to rate this book.</em></p>
            <?php endif; ?>
        </div>

        <a class="back-link" href="index.php">← Back to Book List</a>
    </div>

    <?php include("footer.php"); ?>
</body>
</html>
