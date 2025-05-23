<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "messages";

$conn = new mysqli($servername, $username, $password);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS $db";
$conn->query($sql);

$conn->close();
$conn = new mysqli($servername, $username, $password, $db);

$sql = "CREATE TABLE IF NOT EXISTS $db(
senderName VARCHAR(50),
senderEmail VARCHAR(50),
toName VARCHAR(50),
message TEXT
)";
$conn->query($sql);

$studentErr = $nameErr = $emailErr = $messageErr = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $student = $_POST['student'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    
    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if(empty($student)) {
        $studentErr = "Student name is required!";
    } else {
        $student = test_input($student);
        $studentErr = "";
    }

    if(empty($name)) {
        $nameErr = "Your name is required!";
    } else {
        $name = test_input($name);
        $nameErr = "";
    }

    if(empty($email)) {
        $emailErr = "Your Email is required!";
    } 
    else if(!preg_match($pattern, $email)) {
        $emailErr = "Please enter a valid email!";   
    }
    else {
        $email = test_input($email);
        $emailErr = "";
    }

    if(empty($message)) {
        $messageErr = "You cannot send an empty message!";
    } 
    else {
        $message = test_input($message);
        $messageErr = "";
    }

    if(empty($studentErr) && empty($nameErr) && empty($emailErr) && empty($messageErr)) {
        $insertQuery = "INSERT INTO $db (senderName, senderEmail, toName, message) VALUES 
        ('$name', '$email', '$student', '$message')";
        $conn->query($insertQuery);
        echo "<script>alert('Message received from $name to $student. We will contact you at $email.')</script>";
    }
    $student = $name = $email = $message = "";
    
} else {
    echo "Invalid request method.";
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>
