<!-- PHP script to update a row in itemtable -->
<?php
    // Include config file
    require_once "../config.php";
    
    // Define variables and initialize with empty values
    $itemnum = $descrip = "";
    $itemnum_err = $descrip_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate customer code
        $input_itemnum = trim($_POST["itemnum"]);
        if(empty($input_itemnum)) {
            $itemnum_err = "Please enter a item number.";
        } else {
            $itemnum = $input_itemnum;
        }
        
        // Validate first name
        $input_descrip = trim($_POST["descrip"]);
        if(empty($input_descrip)) {
            $descrip_err = "Please enter a description.";     
        } else {
            $descrip = $input_descrip;
        }

        // Check input errors before inserting in database
        if(empty($itemnum_err) && empty($descrip_err)) {
            // Prepare an update statement
            $sql = "UPDATE itemtable SET descrip=? WHERE itemnum=?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_descrip, $param_itemnum);
                
                // Set parameters
                $param_descrip = $descrip;
                $param_itemnum = $itemnum;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records updated successfully. Redirect to landing page
                    echo '<script type="text/javascript">
                            window.location.href = ' . '"../interface.php?x=' . rand() . '#itemtable";
                            </script>';
                    exit;
                } else {
                    $error = mysqli_stmt_error($stmt);
                    echo "Error : ".$error;
                }
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // Check existence of id parameter before processing further
        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
            // Get URL parameter
            $itemnum = trim($_GET["id"]);
            
            // Prepare a select statement
            $sql = "SELECT * FROM itemtable WHERE itemnum = ?";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_itemnum);
                
                // Set parameters
                $param_itemnum = $itemnum;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        /* Fetch result row as an associative array. Since the result set
                        contains only one row, we don't need to use while loop */
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        // Retrieve individual field value
                        $descrip = $row["descrip"];
                    } else {
                        // URL doesn't contain valid id. Redirect to error page
                        echo '<script type="text/javascript">
                                window.location.href = ' . '"../error.php";</script>';
                        exit;
                    }
                } else {
                    echo "Something went wrong. Please try again later.";
                }
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            // URL doesn't contain id parameter. Redirect to error page
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
    <title>Update Record</title>
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
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the item record.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Item Number</label>
                            <input type="text" name="itemnum" class="form-control <?php echo (!empty($itemnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $itemnum; ?>" readonly>
                            <span class="invalid-feedback"><?php echo $itemnum_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Item Description</label>
                            <input type="text" name="descrip" class="form-control <?php echo (!empty($descrip_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $descrip; ?>">
                            <span class="invalid-feedback"><?php echo $descrip_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php
                            echo '<a href="../interface.php?x=' . rand() . '#itemtable" class="btn btn-secondary ml-2">Cancel</a>';
                            mysqli_close($conn);
                        ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>