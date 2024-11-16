<?php
// Set the new folder path for uploaded files
$newFolder = 'new_uploads/';

// Ensure the directory exists
if (!is_dir($newFolder)) {
    mkdir($newFolder, 0777, true); // Create the folder if it doesn't exist
}

// Check if files are uploaded
if (isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uniqueFileName = uniqid() . "_" . basename($file['name']); // Unique filename
    $targetFilePath = $newFolder . $uniqueFileName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $targetFilePath)) {
        echo json_encode([
            "status" => "success",
            "message" => "File uploaded successfully",
            "fileName" => $uniqueFileName,
            "filePath" => $targetFilePath
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            "status" => "error",
            "message" => "Failed to move uploaded file"
        ]);
    }
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "No file uploaded"
    ]);
}
?>
