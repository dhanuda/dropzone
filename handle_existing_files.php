<?php
// Set the new folder path for existing files
$newFolder = 'new_uploads/';

// Ensure the directory exists
if (!is_dir($newFolder)) {
    mkdir($newFolder, 0777, true); // Create folder if it doesn't exist
}

// Check if existing files are sent in the POST request
if (isset($_POST['existing_files'])) {
    $existingFiles = $_POST['existing_files']; // Array of existing file paths
    $movedFiles = [];

    // Loop through each existing file and move it to the new folder
    foreach ($existingFiles as $filePath) {
        $fileName = basename($filePath); // Get file name from the path
        $newFilePath = $newFolder . $fileName; // New file path in the new folder

        // Check if the file exists and copy it to the new location
        if (file_exists($filePath) && copy($filePath, $newFilePath)) {
            $movedFiles[] = $newFilePath; // Store moved file path
        } else {
            http_response_code(500);
            echo json_encode([
                "status" => "error",
                "message" => "Failed to copy file: $filePath"
            ]);
            exit;
        }
    }

    // Return success with moved files
    echo json_encode([
        "status" => "success",
        "message" => "Existing files moved successfully",
        "moved_files" => $movedFiles
    ]);
} else {
    http_response_code(400);
    echo json_encode([
        "status" => "error",
        "message" => "No existing files provided"
    ]);
}
?>
