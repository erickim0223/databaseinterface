<!-- PHP script to update a row in orderline table -->
<?php
    ob_start();
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
            // Prepare an update statement
            $sql = "UPDATE orderline SET itemid = ? WHERE orderid = ? AND itemid = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sss", $param_itemnum, $param_refnum, $param_itemid);
                
                // Set parameters
                $param_itemnum = $itemnum;
                $param_refnum = $refnum;
                $param_itemid = trim($_POST["itemid"]);

                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records updated successfully. Redirect to landing page
                    header("Location: ../interface.php?x=" . rand() . "#orderline");
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
        if(isset($_GET["orderid"]) && !empty(trim($_GET["orderid"])) &&
        isset($_GET["itemid"]) && !empty(trim($_GET["itemid"]))) {
            // Get URL parameter
            $refnum =  trim($_GET["orderid"]);
            $itemnum =  trim($_GET["itemid"]);
            
            // Prepare a select statement
            $sql = "SELECT * FROM orderline WHERE orderid = ? AND itemid = ?";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ss", $param_refnum, $param_itemnum);
                
                // Set parameters
                $param_refnum = $refnum;
                $param_itemnum = $itemnum;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        /* Fetch result row as an associative array. Since the result set
                        contains only one row, we don't need to use while loop */
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        // Retrieve individual field value
                        $refnum = $row["orderid"];
                        $itemnum = $row["itemid"];
                    } else {
                        // URL doesn't contain valid id. Redirect to error page
                        header("Location: error.php");
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
            header("Location: error.php");
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
                    <p>Please edit the input values and submit to update the order line record.</p>                    
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <input type="hidden" name="itemid" value="<?php echo trim($_GET["itemid"]); ?>"/>
                        <div class="form-group">
                            <label>Reference Number</label>
                            <input type="text" name="refnum" class="form-control <?php echo (!empty($refnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $refnum; ?>" readonly>
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
                                            if ($row['itemnum'] == $itemnum) {
                                                echo "<option value='". $row['itemnum'] . "' selected>" . $row['itemnum'];
                                                echo "</option>";
                                            } else {
                                                echo "<option value='". $row['itemnum'] . "'>" . $row['itemnum'];
                                                echo "</option>";
                                            }
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