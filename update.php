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

    // Update database record
    $fileList = implode(',', $fileNames); // Create a comma-separated string
    $sql = "UPDATE registrations SET username = '$username', email = '$email', attachments = CONCAT_WS(',', attachments, '$fileList') WHERE username = '$username'";
    if ($conn->query($sql) === TRUE) {
        echo "Update successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
