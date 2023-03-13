<!-- PHP script for deleting rows of tables other than the orderline 
table -->
<?php
    // Process delete operation after confirmation
    if(isset($_POST["id"]) && !empty($_POST["id"]) &&
       isset($_POST["table"]) && !empty($_POST["table"]) &&
       isset($_POST["key"]) && !empty($_POST["key"])) {
        // Include config file
        require_once "../config.php";
        // Prepare a delete statement

        $sql = "DELETE FROM " . trim($_POST["table"]) . " WHERE " . trim($_POST["key"]) . " = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);
            
            // Set parameters
            $param_id = trim($_POST["id"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                // Records deleted successfully. Redirect to landing page
                echo '<script type="text/javascript">
                            window.location.href = ' . '"../interface.php?x=' . rand() . '#' . trim($_POST["table"]) .
                            '";</script>';
                exit;
            } else {
                $error = mysqli_stmt_error($stmt);
                echo "Error : ".$error;
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($conn);

    } else {
        // Check existence of parameters
        if(empty(trim($_GET["id"]))) {
            // URL doesn't contain id parameter. Redirect to error page
            echo '<script type="text/javascript">
                            window.location.href = ' . '"../error.php";</script>';
            exit;
        }
        if(empty(trim($_GET["table"]))) {
            // URL doesn't contain table parameter. Redirect to error page
            echo '<script type="text/javascript">
                            window.location.href = ' . '"../error.php";</script>';
            exit;
        }
        if(empty(trim($_GET["key"]))) {
            // URL doesn't contain key parameter. Redirect to error page
            echo '<script type="text/javascript">
                            window.location.href = ' . '"../error.php";</script>';
            exit;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5 mb-3">Delete Record</h2>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="alert alert-danger">
                            <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                            <input type="hidden" name="table" value="<?php echo trim($_GET["table"]); ?>"/>
                            <input type="hidden" name="key" value="<?php echo trim($_GET["key"]); ?>"/>
                            <p>Are you sure you want to delete this record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <?php
                                    echo '<a href="../interface.php?x=' . rand() . '#'. trim($_GET["table"]) . '"' . 'class="btn btn-secondary ml-2">Cancel</a>';
                                ?>
                            </p>
                        </div>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>