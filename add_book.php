<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    session_start();

    require_once 'image_util.php'; // the process_image function

    $image_dir = 'images';
    $image_dir_path = getcwd() . DIRECTORY_SEPARATOR . $image_dir;

    if (isset($_FILES['file1']))
    {
        $filename = $_FILES['file1']['name'];

        if (!empty($filename))
        {
            $source = $_FILES['file1']['tmp_name'];

            $target = $image_dir_path . DIRECTORY_SEPARATOR . $filename;

            move_uploaded_file($source, $target);

            // create the '400' and '100' versions of the image
            process_image($image_dir_path, $filename);
        }
    }

    // get data from the form
    $book_name = filter_input(INPUT_POST, 'book_name');
    // alternative
    //$first_name = $_POST['first_name'];
    $author = filter_input(INPUT_POST, 'author');
    $email_address = filter_input(INPUT_POST, 'email_address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');
    $status= filter_input(INPUT_POST, 'status'); // assigns the value of the selected radio button
    $published = filter_input(INPUT_POST, 'published');
    $genre = filter_input(INPUT_POST, 'genre');
    $image_name = $_FILES['file1']['name'];

    require_once('database.php');
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();

    $statement1->closeCursor();

    foreach($books as $book)
    {
      if ($email_address == $book["emailAddress"])
      {
        $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";

        $url = "error.php";
        header("Location: " . $url);
        die(); 
      }
    }

    if($book_name == null || $author == null ||
      $email_address == null || $phone_number == null || 
      $published == null || $genre == null)
      {
        $_SESSION["add_error"] = "Invalid book data, check all fields and try again.";

        $url = "error.php";
        header("Location: " . $url);
        die(); 

      }
    else
{
    require_once('database.php');

    //Add the book to the database
    $query = 'INSERT INTO books
        (bookName, author, emailAddress, phone, status, published, genre, imageName)
        VALUES
        (:bookName, :author, :emailAddress, :phone, :status, :published, :genre, :imageName)';

    $statement = $db->prepare($query);
    $statement->bindvalue(':bookName', $book_name);
    $statement->bindvalue(':author', $author);
    $statement->bindvalue(':emailAddress', $email_address); 
    $statement->bindvalue(':phone', $phone_number);
    $statement->bindvalue(':status', $status); 
    $statement->bindvalue(':published', $published);
    $statement->bindvalue(':genre', $genre);
    $statement->bindvalue(':imageName', $image_name);
    
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["fullName"] = $book_name . " " . $author;
}
    //redirect to confirmation page
    $url = "confirmation.php";
    header("Location: " . $url);
    die(); // releases add_book.php from memory


?>