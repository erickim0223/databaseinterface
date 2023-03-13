<!-- PHP script to create a row in orderline table -->
<?php
    // Include config file
    require_once "../config.php";
    
    // Define variables and initialize with empty values
    $refnum = $itemnum = "";
    $refnum_err = $itemnum_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate refnum
        $input_refnum = trim($_POST["refnum"]);
        if(empty($input_refnum)) {
            $refnum_err = "Please select a reference number.";
        } else {
            $refnum = $input_refnum;
        }
        
        // Validate Item Number
        $input_itemnum = trim($_POST["itemnum"]);
        if(empty($input_itemnum)) {
            $itemnum_err = "Please select the item number.";     
        } else {
            $itemnum = $input_itemnum;
        }

        
        // Check input errors before inserting in database
        if(empty($refnum_err) && empty($itemnum_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO orderline (orderid, itemid) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_refnum, $param_itemnum);
                
                // Set parameters
                $param_refnum = $refnum;
                $param_itemnum = $itemnum; 
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records created successfully. Redirect to landing page
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
                    <p>Please fill this form and submit to add an order line record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Reference Number</label>
                            <select name="refnum" class="form-control <?php echo (!empty($refnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $refnum; ?>">
                                <?php 
                                $sql = "SELECT * FROM ordertable";
                                if($result = mysqli_query($conn, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option value='". $row['refnum'] . "'>" . $row['refnum'];
                                            echo "</option>";
                                        }
                                        mysqli_free_result($result);
                                    } else {
                                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                    }
                                } else {
                                    echo "Something went wrong. Please try again later.";
                                }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $refnum_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Item Number</label>
                            <select name="itemnum" class="form-control <?php echo (!empty($itemnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $itemnum; ?>">
                            <?php 
                                $sql = "SELECT * FROM itemtable";
                                if($result = mysqli_query($conn, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_array($result)){
                                            echo "<option value='". $row['itemnum'] . "'>" . $row['itemnum'];
                                            echo "</option>";
                                        }
                                        mysqli_free_result($result);
                                    } else {
                                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                    }
                                } else {
                                    echo "Something went wrong. Please try again later.";
                                }
                            ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $itemnum_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php
                            echo '<a href="../interface.php?x=' . rand() . '#orderline" class="btn btn-secondary ml-2">Cancel</a>';
                            mysqli_close($conn);
                        ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>