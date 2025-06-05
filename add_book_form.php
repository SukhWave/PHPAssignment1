<?php
    require_once('database.php');
    $queryTypes = 'SELECT * FROM types';
    $statement = $db->prepare($queryTypes);
    $statement->execute();
    $types = $statement->fetchAll();
    $statement->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book Manager - Add Book</title>
        <link rel="stylesheet" type="text/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Add Book</h2>

            <form action="add_book.php" method="post" id="add_book_form"
                enctype="multipart/form-data">
            
                <div id="data">

                    <label>Book Name:</label>
                    <input type="text" name="book_name" /><br />

                    <label>Author:</label>
                    <input type="text" name="author" /><br />

                    <label>Email Address:</label>
                    <input type="text" name="email_address" /><br />                    

                    <label>Phone Number:</label>
                    <input type="text" name="phone_number" /><br />

                    <label>Status:</label>
                    <input type="radio" name="status" value="member" />Member<br />                    
                    <input type="radio" name="status" value="nonmember" />Non-Member<br />

                    <label>Published Date:</label>
                    <input type="date" name="published" /><br /> 

                    <label>Book Type:</label>
                    <select name="type_id">
                        <?php foreach ($types as $type): ?>
                            <option value="<?php echo $type['typeID']; ?>">
                                <?php echo $type['bookType']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select><br />                    

                    <label>Upload Image</label>
                    <input type="file" name="file1" /><br /> 

                  </div>

                  <div id="buttons">

                    <label>&nbsp;</label>
                    <input type="submit" value="Save Book" /><br />                      

                  </div>

            </form>

            <p><a href="index.php">View Book List</a></p>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>