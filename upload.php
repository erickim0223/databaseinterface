<!-- PHP script to verify uploaded file and uploads file to server if 
everything is okay. It also prints a button that will redirect to 
inserttable.php, which will insert the XML data to the actual database -->
<?php
    $target_dir = "xmlfile/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists. Try renaming your file. ";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large. ";
        $uploadOk = 0;
    }

    // Allow xml files only
    if($imageFileType != "xml") {
        echo "Sorry, only XML files are allowed. ";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded. ";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
            echo '<form action="setup/inserttable.php?filename=' . htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])) . '" method="post">
                    <input type="submit" value="Insert to Database" name="submit">
                </form>';
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
?>