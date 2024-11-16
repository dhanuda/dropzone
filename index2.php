<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Existing and New Files</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet">
    <style>
        #my-dropzone {
            border: 2px dashed #0087F7;
            padding: 20px;
        }
    </style>
</head>
<body>

<h2>Upload Existing and New Files</h2>

<!-- Dropzone for File Uploads -->
<div id="my-dropzone" class="dropzone"></div>

<!-- Submit Button -->
<button id="submit-button">Submit</button>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
<script>
    // Mock existing files (these would be fetched from the server normally)
    const existingFiles = [
        { name: "file1.pdf", size: 790610, path: "uploads/photo-1533450718592-29d45635f0a9.jpg" },
        { name: "image1.jpg", size: 126976, path: "uploads/website.jpg" }
    ];

    // Initialize Dropzone
    const dropzone = new Dropzone("#my-dropzone", {
        url: "upload_new_files.php", // Backend URL for new uploads
        autoProcessQueue: false,    // Prevent auto-upload
        addRemoveLinks: true        // Allow file removal
    });

    // Add existing files to Dropzone
    existingFiles.forEach(file => {
        const mockFile = { name: file.name, size: file.size };

        // Emit Dropzone events to simulate adding existing files
        dropzone.emit("addedfile", mockFile);
        dropzone.emit("thumbnail", mockFile, file.path); // Add thumbnail for images
        dropzone.emit("complete", mockFile);

        // Mark as an existing file (custom property)
        mockFile.existing = true;
        mockFile.status = Dropzone.SUCCESS;
        mockFile.path = file.path; // Add the path of the existing file to be used later
        dropzone.files.push(mockFile);
    });

    // Handle the submit button
    document.getElementById("submit-button").addEventListener("click", () => {
        // Collect existing file paths
        const existingFilePaths = dropzone.files
            .filter(file => file.existing) // Only include existing files
            .map(file => file.path); // Get file paths

        // Create FormData to send existing file paths to the server
        const formData = new FormData();
        existingFilePaths.forEach((path, index) => {
            formData.append(`existing_files[${index}]`, path);
        });

        // Upload new files (if any)
        const newFiles = dropzone.getQueuedFiles();
        if (newFiles.length > 0) {
            dropzone.processQueue(); // Upload new files to the backend
        }

        // Send existing file data to the backend
        fetch("handle_existing_files.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Existing files handled:", data);
        })
        .catch(error => {
            console.error("Error handling existing files:", error);
        });
    });
</script>

</body>
</html>
