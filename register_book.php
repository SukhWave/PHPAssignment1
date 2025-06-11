<?php
session_start();
require_once('message.php');

// Get data from the form
$user_name = filter_input(INPUT_POST, 'user_name');
$password = filter_input(INPUT_POST, 'password');
$email_address = filter_input(INPUT_POST, 'email_address');

$hash = password_hash($password, PASSWORD_DEFAULT);

// Validate input
if ($user_name === null || $password === null || $email_address === null) {
    $_SESSION["add_error"] = "Invalid registration data. Check all fields and try again.";
    header("Location: error.php");
    die();
}

require_once('database.php');

// Check for duplicate usernames
$queryRegistrations = 'SELECT * FROM registrations';
$statement = $db->prepare($queryRegistrations);
$statement->execute();
$registrations = $statement->fetchAll();
$statement->closeCursor();

foreach ($registrations as $registration) {
    if ($user_name === $registration["userName"]) {
        $_SESSION["add_error"] = "Invalid data, duplicate username. Try again.";
        header("Location: error.php");
        die();
    }
}

// Add new registration
$query = 'INSERT INTO registrations (userName, password, emailAddress)
          VALUES (:userName, :password, :emailAddress)';
$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->bindValue(':password', $hash);
$statement->bindValue(':emailAddress', $email_address);
$statement->execute();
$statement->closeCursor();

// Log in user
$_SESSION["isLoggedIn"] = 1;
$_SESSION["userName"] = $user_name;

// Set up email variables
$to_address = $email_address;
$to_name = $user_name;
$from_address = 'username@gmail.com';
$from_name = 'Book List';
$subject = 'Book List - Registration Complete';
$body = '<p>Thanks for registering with <strong>Book List</strong>.</p>' .
        '<p>Sincerely,</p>' .
        '<p>The Book List Team</p>';
$is_body_html = true;

// Send confirmation email
try {
    send_email($to_address, $to_name, $from_address, $from_name, $subject, $body, $is_body_html);
} catch (Exception $ex) {
    $_SESSION["add_error"] = $ex->getMessage();
    header("Location: error.php");
    die();
}

// Redirect to confirmation
header("Location: register_confirmation.php");
die();
?>
