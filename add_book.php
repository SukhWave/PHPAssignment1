<?php
session_start();

$book_name = filter_input(INPUT_POST, 'book_name');
$author = filter_input(INPUT_POST, 'author');
$email_address = filter_input(INPUT_POST, 'email_address');
$phone_number = filter_input(INPUT_POST, 'phone_number');
$status = filter_input(INPUT_POST, 'status');
$published = filter_input(INPUT_POST, 'published');
$type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
$image = $_FILES['file1'];

require_once('database.php');
require_once('image_util.php');

$base_dir = 'images/';

// Check for duplicate email
$queryBooks = 'SELECT * FROM books';
$statement1 = $db->prepare($queryBooks);
$statement1->execute();
$books = $statement1->fetchAll();
$statement1->closeCursor();

foreach ($books as $book) {
    if ($email_address === $book["emailAddress"]) {
        $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
        header("Location: error.php");
        die();
    }
}

// Validate input
if ($book_name === null || $author === null || $email_address === null || 
    $phone_number === null || $published === null || $type_id === null) {
    $_SESSION["add_error"] = "Invalid book data, Check all fields and try again.";
    header("Location: error.php");
    die();
}

// Handle image upload or assign placeholder
$image_name = '';  // default empty

if ($image && $image['error'] === UPLOAD_ERR_OK) {
    // Process new image
    $original_filename = basename($image['name']);
    $upload_path = $base_dir . $original_filename;
    move_uploaded_file($image['tmp_name'], $upload_path);
    process_image($base_dir, $original_filename);

    // Save _100 version in DB
    $dot_pos = strrpos($original_filename, '.');
    $name_100 = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
    $image_name = $name_100;
} else {
    // Use placeholder
    $placeholder = 'placeholder.jpg';
    $placeholder_100 = 'placeholder_100.jpg';
    $placeholder_400 = 'placeholder_400.jpg';

    if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
        process_image($base_dir, $placeholder);
    }

    $image_name = $placeholder_100;
}

// Add the book to the database
$query = 'INSERT INTO books
    (bookName, author, emailAddress, phone, status, published, imageName, typeID)
    VALUES
    (:bookName, :author, :emailAddress, :phone, :status, :published, :imageName, :typeID)';

$statement = $db->prepare($query);
$statement->bindValue(':bookName', $book_name);
$statement->bindValue(':author', $author);
$statement->bindValue(':emailAddress', $email_address);
$statement->bindValue(':phone', $phone_number);
$statement->bindValue(':status', $status);
$statement->bindValue(':published', $published);
$statement->bindValue(':imageName', $image_name);
$statement->bindValue(':typeID', $type_id);
$statement->execute();
$statement->closeCursor();

$_SESSION["fullName"] = $book_name . " " . $author;

// Redirect to confirmation page
header("Location: confirmation.php");
die();
?>
