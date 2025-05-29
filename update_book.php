<?php
    session_start();

    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);

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

    require_once('database.php');
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();

    $statement1->closeCursor();

    foreach($books as $book)
    {
      if ($email_address == $book["emailAddress"] && $book_id != $book["bookID"])
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

        //Update the book to the database
        $query = 'UPDATE books
            SET bookName = :bookName,
            author = :author,
            emailAddress = :emailAddress,
            phone = :phone,
            status = :status,
            published = :published,
            genre = :genre
            WHERE bookID = :bookID';

        $statement = $db->prepare($query);
        $statement->bindvalue(':bookID', $book_id);
        $statement->bindvalue(':bookName', $book_name);
        $statement->bindvalue(':author', $author);
        $statement->bindvalue(':emailAddress', $email_address); 
        $statement->bindvalue(':phone', $phone_number);
        $statement->bindvalue(':status', $status); 
        $statement->bindvalue(':published', $published);
        $statement->bindvalue(':genre', $genre);
        
        $statement->execute();
        $statement->closeCursor();

        $_SESSION["FullName"] = $book_name . " " . $author;
      }
    //redirect to confirmation page
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die(); // releases add_book.php from memory


?>