<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
    <style>
        .dropzone {
            border: 2px dashed #007bff;
            border-radius: 5px;
            padding: 20px;
            background: #f9f9f9;
            margin-top: 20px; /* Add some space above the dropzone */
        }
        .error {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <div id="titleError" class="error"></div> <!-- Error message placeholder -->
        <br>

        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <div id="descriptionError" class="error"></div> <!-- Error message placeholder -->
        <br>

        <div id="myDropzone" class="dropzone">
            <div class="dz-message">Drop files here or click to upload.</div>
        </div>
        
        <button id="submit-btn" type="button">Submit</button>
    </form>

    <div id="response"></div>

    <script>
        $(document).ready(function() {
            // Initialize Dropzone
            var myDropzone = new Dropzone("#myDropzone", {
                url: 'upload.php', // Change to your PHP upload file
                paramName: 'attachments[]',
                maxFilesize: 2, // MB
                acceptedFiles: '.jpeg,.jpg,.png,.gif,.pdf', // Set accepted file types
                addRemoveLinks: true,
                init: function() {
                    var dropzoneInstance = this;

                    $('#submit-btn').on('click', function(e) {
                        e.preventDefault();
                        // Clear previous error messages
                        $('#titleError').text('');
                        $('#descriptionError').text('');

                        // Gather title and description
                        var title = $('#title').val();
                        var description = $('#description').val();
                        var hasError = false;

                        if (!title) {
                            $('#titleError').text("Title is required.");
                            hasError = true;
                        }
                        if (!description) {
                            $('#descriptionError').text("Description is required.");
                            hasError = true;
                        }

                        if (!hasError) {
                            var formData = new FormData();
                            formData.append("title", title);
                            formData.append("description", description);
                            
                            // Append all files from Dropzone
                            $.each(dropzoneInstance.files, function(index, file) {
                                formData.append("attachments[]", file);
                            });

                            // Make the AJAX request
                            $.ajax({
                                url: 'upload.php', // Change to your PHP upload file
                                type: 'POST',
                                data: formData,
                                processData: false, // Important!
                                contentType: false, // Important!
                                success: function(response) {
                                    $('#response').html(response); // Display the response
                                    dropzoneInstance.removeAllFiles(true); // Clear files from Dropzone
                                },
                                error: function(xhr, status, error) {
                                    $('#response').html("Error: " + error);
                                }
                            });
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
