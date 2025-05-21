<?php
    session_start();
    require("database.php");
    $queryContacts = 'SELECT * FROM contacts';
    $statement1 = $db->prepare($queryContacts);
    $statement1->execute();
    $contacts = $statement1->fetchAll();

    $statement1->closeCursor();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Book List - Home</title>
        <link rel="stylesheet" type="txt/css" href="css/main.css" />
    </head>
    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Contact List</h2>

            <table>
                <tr>
                    <th>Book Name</th>
                    <th>Author</th>
                    <th>Email Address</th>
                    <th>Phone Number</th>
                    <th>Status</th>
                    <th>Publishes</th>
                </tr>

                <?php foreach ($contacts as $contact): ?>
                    <tr>
                        <td><?php echo $contact['firstName']; ?></td>
                        <td><?php echo $contact['lastName']; ?></td>
                        <td><?php echo $contact['emailAddress']; ?></td>
                        <td><?php echo $contact['phone']; ?></td>
                        <td><?php echo $contact['status']; ?></td>
                        <td><?php echo $contact['dob']; ?></td>
                    </tr>
                <?php endforeach; ?>

            </table>
        </main>

        <?php include("footer.php"); ?>
    </body>
</html>