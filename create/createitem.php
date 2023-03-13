<!-- PHP script to create a row in itemtable -->
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
        if(empty($input_itemnum)){
            $itemnum_err = "Please enter a item number.";
        } else{
            $itemnum = $input_itemnum;
        }
        
        // Validate first name
        $input_descrip = trim($_POST["descrip"]);
        if(empty($input_descrip)){
            $descrip_err = "Please enter a description.";     
        } else {
            $descrip = $input_descrip;
        }

        // Check input errors before inserting in database
        if(empty($itemnum_err) && empty($descrip_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO itemtable (itemnum, descrip) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_itemnum, $param_descrip);
                
                // Set parameters
                $param_itemnum = $itemnum;
                $param_descrip = $descrip; 
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records created successfully. Redirect to landing page
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
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add an item record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Item Number</label>
                            <input type="text" name="itemnum" class="form-control <?php echo (!empty($itemnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $itemnum; ?>">
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