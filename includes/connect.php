<head>
    <link rel="stylesheet" href="/css/style.css">
    <title>BlitzBlink</title>
    <meta name="viewport" content="width=device-width, initial-scale = 1.0">
</head>
<body>
    <header>
            <a href="/"><img src="/assets/logo.svg" alt="BlitzBlink logo" width="200" height="auto" /></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="/" class="active">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="project.html">Our Projects</a></li>
                    <li><a href="contactus.html" >Contact Us</a></li>
                </ul>
            </nav>
            <div class="menu-toggle" id="menu-toggle">&#9776;</div>
    </header>
</body>

<?php
$servername = "sql100.infinityfree.com";
$username = "if0_39030642";
$password = "imaR2005";
$db = "if0_39030642_messages";
$table = "messages_data";

// Connect to MySQL server
$conn = new mysqli($servername, $username, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


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
        $stmt = $conn->prepare("INSERT INTO $table (senderName, senderEmail, toName, message) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $student, $message);
        $stmt->execute();
        $stmt->close();

        echo "<p>Message received from $name to $student. We will contact you at $email.</p>";
        echo "<a href='/'>Back to home page</a>";
        $student = $name = $email = $message = "";
    }
} else {
    echo "Invalid request method.";
}

function test_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}
?>
