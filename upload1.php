<?php
session_start();
$targetDir = "uploads/";
$uploadStatus = [];
$allowedTypes = ['jpeg', 'jpg', 'png', 'gif', 'pdf', 'doc', 'docx'];
$maxFileSize = 2 * 1024 * 1024; // 2 MB

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Loop through each file
    foreach ($_FILES['files']['name'] as $key => $name) {
        $fileType = pathinfo($name, PATHINFO_EXTENSION);
        $fileSize = $_FILES['files']['size'][$key];

        // Check if file type is allowed
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Check file size
            if ($fileSize <= $maxFileSize) {
                $filePath = $targetDir . basename($name);
                
                // Attempt to move the uploaded file
                if (move_uploaded_file($_FILES['files']['tmp_name'][$key], $filePath)) {
                    $uploadStatus[] = $name; // Store the uploaded filename
                } else {
                    $uploadStatus[] = "Error uploading $name.";
                }
            } else {
                $uploadStatus[] = "File $name exceeds the maximum size limit.";
            }
        } else {
            $uploadStatus[] = "File type not allowed for $name.";
        }
    }
}

// Return a JSON response with upload status
header('Content-Type: application/json');
echo json_encode($uploadStatus);
