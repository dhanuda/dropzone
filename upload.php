<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $host = 'localhost'; // Your database host
    $db = 'test'; // Your database name
    $user = 'root'; // Your database username
    $pass = ''; // Your database password

    $conn = new mysqli($host, $user, $pass, $db);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate title and description
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if (empty($title) || empty($description)) {
        echo "Title and Description are required.";
        exit;
    }

    // Insert data into the database (adjust your table name and columns)
    $stmt = $conn->prepare("INSERT INTO question (title, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $description);
    
    if ($stmt->execute()) {
        $postId = $stmt->insert_id; // Get the inserted ID for attachments

        // Handle file uploads
        $uploadsDir = 'uploads/'; // Ensure this directory exists and is writable
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        foreach ($_FILES['attachments']['tmp_name'] as $key => $tmpName) {
            $fileName = basename($_FILES['attachments']['name'][$key]);
            $filePath = $uploadsDir . $fileName;

            // Move uploaded file to the target directory
            if (move_uploaded_file($tmpName, $filePath)) {
                // Optionally, insert attachment info into database
                $conn->query("INSERT INTO attachments (post_id, file_path) VALUES ($postId, '$filePath')");
            } 
			else 
			{
                echo "Failed to upload file: " . $fileName;
                exit;
            }
        }

        echo "Data submitted successfully.";
    } else {
        echo "Failed to insert data.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
