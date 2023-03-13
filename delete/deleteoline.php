<!-- PHP script for deleting a row of the orderline table -->
<?php
    // Process delete operation after confirmation
    if(isset($_POST["orderid"]) && !empty($_POST["orderid"]) &&
       isset($_POST["itemid"]) && !empty($_POST["itemid"])) {
        // Include config file
        require_once "../config.php";
        // Prepare a delete statement
        $sql = "DELETE FROM orderline WHERE orderid = ? AND itemid = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_orderid, $param_itemid);
            
            // Set parameters
            $param_orderid = trim($_POST["orderid"]);
            $param_itemid = trim($_POST["itemid"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)) {
                // Records deleted successfully. Redirect to landing page
                echo '<script type="text/javascript">
                            window.location.href = ' . '"../interface.php?x=' . rand() . '#orderline";
                            </script>';
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
        if(empty(trim($_GET["orderid"]))) {
            // URL doesn't contain orderid parameter. Redirect to error page
            echo '<script type="text/javascript">
                            window.location.href = ' . '"../error.php";</script>';
            exit;
        }
        if(empty(trim($_GET["itemid"]))) {
            // URL doesn't contain itemid parameter. Redirect to error page
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
                            <input type="hidden" name="orderid" value="<?php echo trim($_GET["orderid"]); ?>"/>
                            <input type="hidden" name="itemid" value="<?php echo trim($_GET["itemid"]); ?>"/>
                            <p>Are you sure you want to delete this order line record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <?php
                                    echo '<a href="../interface.php?x=' . rand() . '#orderline" class="btn btn-secondary ml-2">Cancel</a>';
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