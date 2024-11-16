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
    $filename = $_POST['filename'];

    // Remove file from server
    if (file_exists('uploads/' . $filename)) {
        unlink('uploads/' . $filename);
    }

    // Remove file from database
    $sql = "UPDATE registrations SET attachments = REPLACE(attachments, '$filename,', '') WHERE attachments LIKE '%$filename%'";
    $conn->query($sql);

    echo "File $filename removed successfully.";
}
$conn->close();
?>
