<?php
session_start();
$host = 'localhost';
$db = 'test';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);

    // Handle file uploads
    $fileNames = [];
    if (!empty($_FILES['files']['name'][0])) {
        foreach ($_FILES['files']['name'] as $key => $name) {
            $tmp_name = $_FILES['files']['tmp_name'][$key];
            $filePath = 'uploads/' . basename($name);
            if (move_uploaded_file($tmp_name, $filePath)) {
                $fileNames[] = $name; // Add file name to array
            }
        }
    }

    $fileList = implode(',', $fileNames); // Create a comma-separated string

    $sql = "INSERT INTO registrations (username, email, attachments) VALUES ('$username', '$email', '$fileList')";
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
        $_SESSION['success_message'] = "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $_SESSION['error_message'] = "Registration failed!";
    }

    $conn->close();
}
?>
