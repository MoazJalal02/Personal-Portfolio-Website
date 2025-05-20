<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student = $_POST['student'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    
    echo "Message received from $name for $student. We will contact you at $email.";
} else {
    echo "Invalid request method.";
}
?>
