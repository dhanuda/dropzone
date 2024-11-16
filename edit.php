<?php
// Database connection parameters
$host = 'localhost'; // Database host
$db = 'test'; // Database name
$user = 'root'; // Database username
$pass = ''; // Database password

try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Assuming you have a user ID from the session or request
    $userId = $_REQUEST['user_id']; // Get user ID from URL parameters

    // Fetch user data
    $stmt = $pdo->prepare("SELECT username, email, attachments FROM registrations WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user data exists
    if (!$user) {
        echo "User not found!";
        exit;
    }

    // Handle attachments
    $attachments = !empty($user['attachments']) ? explode(',', $user['attachments']) : [];
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Close the connection
$pdo = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Registration</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/basic.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>
    <style>
        .dropzone {
            border: 2px dashed #007bff;
            border-radius: 5px;
            background: #f8f9fa;
            padding: 20px;
            margin-bottom: 15px;
        }
        .dz-message {
            text-align: center;
            font-size: 18px;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div id="message"></div>

        <form id="registrationForm" enctype="multipart/form-data">
            <h2 class="mb-4">Edit Registration</h2>

            <div class="form-group row">
                <label for="username" class="col-sm-2 col-form-label">Username</label>
                <div class="col-sm-10">
                    <input type="text" name="username" id="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" name="email" id="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <label class="col-sm-2 col-form-label">Attachments</label>
                <div class="col-sm-10">
                    <div class="dropzone" id="myDropzone" aria-label="File upload area"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script>
        Dropzone.options.myDropzone = {
            url: 'upload1.php',
            paramName: "files[]",
            maxFilesize: 2, // MB
            maxFiles: 10,
            acceptedFiles: ".jpeg,.jpg,.png,.gif,.pdf,.doc,.docx",
            addRemoveLinks: true,
            dictRemoveFile: "Remove",
            init: function() {
                // Load existing files
                var existingFiles = <?php echo json_encode($attachments); ?>; // Fetch existing files from PHP

                existingFiles.forEach(function(file) {
                    var mockFile = { name: file, size: 12345 }; // Mock file for Dropzone
                    this.emit("addedfile", mockFile);
                    
                    // Check if the file is an image
                    if (/\.(jpeg|jpg|png|gif)$/i.test(file)) {
                        this.emit("thumbnail", mockFile, "uploads/" + file); // Path to your uploads directory
                    } else {
                        // For non-image files, you can show a default thumbnail or just set a placeholder
                        this.emit("thumbnail", mockFile, "path/to/default-thumbnail.png"); // Default thumbnail for non-image files
                    }
                    
                    this.emit("complete");

                    // Remove file listener
                    mockFile.previewElement.querySelector("[data-dz-remove]").addEventListener("click", function() {
                        this.removeFile(mockFile);
                        // Call PHP to remove file from the server and database
                        $.ajax({
                            url: 'remove.php', // PHP file to handle removal
                            type: 'POST',
                            data: { filename: file },
                            success: function(response) {
                                console.log("File removed: " + response);
                            }
                        });
                    }.bind(this));
                }.bind(this));

                this.on("success", function(file, response) {
                    console.log(response);
                });
                this.on("error", function(file, response) {
                    console.error(response);
                });
            }
        };

        $('#registrationForm').on('submit', function(e) {
            e.preventDefault();

            var dropzoneFiles = Dropzone.forElement("#myDropzone").files;
            var formData = new FormData(this);

            dropzoneFiles.forEach(function(file) {
                formData.append("files[]", file);
            });

            $.ajax({
                url: 'update.php', // PHP file to handle updating registration
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $('#message').html('<div class="alert alert-success">' + response + '</div>');
                    // Clear the dropzone files after a successful update
                    Dropzone.forElement("#myDropzone").removeAllFiles(true);
                },
                error: function() {
                    $('#message').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
            });
        });
    </script>
</body>
</html>



