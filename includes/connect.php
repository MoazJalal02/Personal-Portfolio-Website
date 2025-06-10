<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "messages";
$table = "messages_data";

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$conn->query("CREATE DATABASE IF NOT EXISTS $db");
$conn->close();

// Connect to the new database
$conn = new mysqli($servername, $username, $password, $db);

// Create table if not exists
$sql = "CREATE TABLE IF NOT EXISTS $table (
    senderName VARCHAR(50),
    senderEmail VARCHAR(50),
    toName VARCHAR(50),
    message TEXT
)";
$conn->query($sql);

// Form validation and data processing
$studentErr = $nameErr = $emailErr = $messageErr = "";
$student = $name = $email = $message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $student = test_input($_POST['student']);
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $message = test_input($_POST['message']);

    $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

    if (empty($student)) $studentErr = "Student name is required!";
    if (empty($name)) $nameErr = "Your name is required!";
    if (empty($email)) $emailErr = "Your Email is required!";
    else if (!preg_match($pattern, $email)) $emailErr = "Please enter a valid email!";
    if (empty($message)) $messageErr = "You cannot send an empty message!";

    if (empty($studentErr) && empty($nameErr) && empty($emailErr) && empty($messageErr)) {
        // Safely insert into DB
        $stmt = $conn->prepare("INSERT INTO $table (senderName, senderEmail, toName, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $student, $message);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Message received from $name to $student. We will contact you at $email.')</script>";

        // Clear form values
        $student = $name = $email = $message = "";
    }
} else {
    echo "Invalid request method.";
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
