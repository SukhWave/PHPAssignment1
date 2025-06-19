<?php
session_start();

if (!isset($_SESSION["isLoggedIn"])) {
    header("Location: login_form.php");
    die();
}

require("database.php");

// Fetch all book types for dropdown
$typeQuery = 'SELECT * FROM types';
$typeStmt = $db->prepare($typeQuery);
$typeStmt->execute();
$types = $typeStmt->fetchAll();
$typeStmt->closeCursor();

// Handle search and filter input
$search = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_STRING);
$typeID = filter_input(INPUT_GET, 'typeID', FILTER_VALIDATE_INT);

// Build the query dynamically
$queryBooks = '
    SELECT b.*, t.bookType
    FROM books b
    LEFT JOIN types t ON b.typeID = t.typeID
    WHERE 1';

if ($search) {
    $queryBooks .= ' AND (b.bookName LIKE :search OR b.author LIKE :search)';
}
if ($typeID) {
    $queryBooks .= ' AND b.typeID = :typeID';
}

$statement1 = $db->prepare($queryBooks);

if ($search) {
    $statement1->bindValue(':search', '%' . $search . '%');
}
if ($typeID) {
    $statement1->bindValue(':typeID', $typeID);
}

$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Book List - Home</title>
    <link rel="stylesheet" type="text/css" href="css/main.css" />
    <style>
        .search-form {
            margin-bottom: 20px;
        }
        .search-form input, .search-form select {
            padding: 5px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <?php include("header.php"); ?>

    <main>
        <h2>Book List</h2>

        <!-- 🔍 Search and Filter Form -->
        <form method="get" action="index.php" class="search-form">
            <input type="text" name="search" placeholder="Search title or author"
                   value="<?php echo htmlspecialchars($search ?? ''); ?>">
            <select name="typeID">
                <option value="">All Types</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?php echo $type['typeID']; ?>"
                        <?php if ($typeID == $type['typeID']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($type['bookType']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Search</button>
            <?php if ($search || $typeID): ?>
                <a href="index.php">Clear</a>
            <?php endif; ?>
        </form>

        <table>
            <tr>
                <th>Book Name</th>
                <th>Author</th>
                <th>Email Address</th>
                <th>Phone Number</th>
                <th>Status</th>
                <th>Published</th>
                <th>Book Type</th>
                <th>Photo</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>

            <?php foreach ($books as $book): ?>
                <tr>
                    <td><?php echo $book['bookName']; ?></td>
                    <td><?php echo $book['author']; ?></td>
                    <td><?php echo $book['emailAddress']; ?></td>
                    <td><?php echo $book['phone']; ?></td>
                    <td><?php echo $book['status']; ?></td>
                    <td><?php echo $book['published']; ?></td>
                    <td><?php echo $book['bookType']; ?></td>
                    <td>
                        <img src="<?php echo htmlspecialchars('./images/' . $book['imageName']); ?>" 
                             alt="<?php echo htmlspecialchars($book['bookName'] . ' ' . $book['author']); ?>" 
                             class="book-image" />
                    </td>
                    <td>
                        <form action="update_book_form.php" method="post">
                            <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                            <input type="submit" value="Update" />
                        </form>
                    </td>
                    <td>
                        <form action="delete_book.php" method="post">
                            <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                            <input type="submit" value="Delete" />
                        </form>
                    </td>
                    <td>
                        <form action="book_details.php" method="post">
                            <input type="hidden" name="book_id" value="<?php echo $book['bookID']; ?>" />
                            <input type="submit" value="View Details" />
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><a href="add_book_form.php">Add Book</a></p>
        <p><a href="logout.php">Logout</a></p>
    </main>

    <?php include("footer.php"); ?>
</body>
</html>
