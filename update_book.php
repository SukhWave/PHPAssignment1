<?php
    session_start();

    $book_id = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
    $book_name = filter_input(INPUT_POST, 'book_name');
    $author = filter_input(INPUT_POST, 'author');
    $email_address = filter_input(INPUT_POST, 'email_address');
    $phone_number = filter_input(INPUT_POST, 'phone_number');
    $status= filter_input(INPUT_POST, 'status'); // assigns the value of the selected radio button
    $published = filter_input(INPUT_POST, 'published');
    $type_id = filter_input(INPUT_POST, 'type_id', FILTER_VALIDATE_INT);
    $image = $_FILES['image'];

    require_once('database.php');

    // Check for duplicate email
    $queryBooks = 'SELECT * FROM books';
    $statement1 = $db->prepare($queryBooks);
    $statement1->execute();
    $books = $statement1->fetchAll();
    $statement1->closeCursor();

    foreach($books as $book)
    {
      if ($email_address === $book["emailAddress"] && $book_id !== $book["bookID"])
      {
        $_SESSION["add_error"] = "Invalid data, Duplicate Email Address. Try again.";
        header("Location: error.php");
        die(); 
      }
    }

    if($book_name === null || $author === null ||
      $email_address === null || $phone_number === null || 
      $published === null || $type_id === null)
      {
        $_SESSION["add_error"] = "Invalid book data, check all fields and try again.";
        header("Location: error.php");
        die(); 
      }

        require_once('image_util.php');

        // Get current image name from database
        $query = 'SELECT imageName FROM books WHERE bookID = :bookID';
        $statement = $db->prepare($query);
        $statement->bindValue(':bookID', $book_id);
        $statement->execute();
        $current = $statement->fetch();
        $current_image_name = $current['imageName'];
        $statement->closeCursor();

        $image_name = $current_image_name;

        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            // Delete old image files if they exist
            $base_dir = 'images/';
            if ($current_image_name) {
                $dot = strrpos($current_image_name, '_100.');
                if ($dot !== false) {
                    $original_name = substr($current_image_name, 0, $dot) . substr($current_image_name, $dot + 4);
                    $original = $base_dir . $original_name;
                    $img_100 = $base_dir . $current_image_name;
                    $img_400 = $base_dir . substr($current_image_name, 0, $dot) . '_400' . substr($current_image_name, $dot + 4);

                    if (file_exists($original)) unlink($original);
                    if (file_exists($img_100)) unlink($img_100);
                    if (file_exists($img_400)) unlink($img_400);
                }
            }

            // Upload and process new image
            $original_filename = basename($image['name']);
            $upload_path = $base_dir . $original_filename;
            move_uploaded_file($image['tmp_name'], $upload_path);
            process_image($base_dir, $original_filename);

            // Save new _100 filename for database
            $dot_position = strrpos($original_filename, '.');
            $name_without_ext = substr($original_filename, 0, $dot_position);
            $extension = substr($original_filename, $dot_position);
            $image_name = $name_without_ext . '_100' . $extension;
        }

        //Update the book to the database
        $query = 'UPDATE books
            SET bookName = :bookName,
            author = :author,
            emailAddress = :emailAddress,
            phone = :phone,
            status = :status,
            published = :published,
            typeID = :typeID,
            imageName = :imageName
            WHERE bookID = :bookID';

        $statement = $db->prepare($query);
        $statement->bindvalue(':bookID', $book_id);
        $statement->bindvalue(':bookName', $book_name);
        $statement->bindvalue(':author', $author);
        $statement->bindvalue(':emailAddress', $email_address); 
        $statement->bindvalue(':phone', $phone_number);
        $statement->bindvalue(':status', $status); 
        $statement->bindvalue(':published', $published);
        $statement->bindValue(':typeID', $type_id);
        $statement->bindValue(':imageName', $image_name);
        $statement->bindValue(':bookID', $book_id);
        $statement->execute();
        $statement->closeCursor();

        $_SESSION["fullName"] = $book_name . " " . $author; 
        header("Location: update_confirmation.php");
        die();


?>