<!-- PHP script to update a row in ordertable -->
<?php
    // Include config file
    require_once "../config.php";
    
    // Define variables and initialize with empty values
    $refnum = $address = $customer = $countrycode = "";
    $refnum_err = $address_err = $customer_err = $cc_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate refnum
        $input_refnum = trim($_POST["refnum"]);
        if(empty($input_refnum)) {
            $refnum_err = "Please enter a reference number.";
        } else {
            $refnum = $input_refnum;
        }
        
        // Validate Country Code
        $input_cc = trim($_POST["cc"]);
        if(empty($input_cc)) {
            $cc_err = "Please enter the country code.";     
        } else {
            $countrycode = $input_cc;
        }

        // Validate address
        $input_address = trim($_POST["address"]);
        if(empty($input_address)) {
            $address_err = "Please enter an address.";     
        } else {
            $address = $input_address;
        }
        
        // Validate customer
        $input_customer = trim($_POST["customer"]);
        if(empty($input_customer)) {
            $customer_err = "Please enter the customer code.";     
        } else {
            $customer = $input_customer;
        }
        
        // Check input errors before inserting in database
        if(empty($refnum_err) && empty($address_err) && empty($customer_err) && empty($cc_err)) {
            // Prepare an update statement
            $sql = "UPDATE ordertable SET countrycode=?, addressid=?, customerid=? WHERE refnum=?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_cc, $param_address, $param_customer, $param_refnum);
                
                // Set parameters
                $param_cc = $countrycode;
                $param_address = $address;
                $param_customer = $customer;
                $param_refnum = $refnum;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records updated successfully. Redirect to landing page
                    echo '<script type="text/javascript">
                            window.location.href = ' . '"../interface.php?x=' . rand() . '#ordertable";
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
    } else{
        // Check existence of id parameter before processing further
        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
            // Get URL parameter
            $refnum =  trim($_GET["id"]);
            
            // Prepare a select statement
            $sql = "SELECT * FROM ordertable WHERE refnum = ?";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_refnum);
                
                // Set parameters
                $param_refnum = $refnum;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        /* Fetch result row as an associative array. Since the result set
                        contains only one row, we don't need to use while loop */
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        // Retrieve individual field value
                        $countrycode = $row["countrycode"];
                        $address = $row["addressid"];
                        $customer = $row["customerid"];
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
        .wrapper{
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
                    <p>Please edit the input values and submit to update the order record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Reference Number</label>
                            <input type="text" name="refnum" class="form-control <?php echo (!empty($refnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $refnum; ?>" readonly>
                            <span class="invalid-feedback"><?php echo $refnum_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Country Code</label>
                            <input type="text" name="cc" class="form-control <?php echo (!empty($cc_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $countrycode; ?>">
                            <span class="invalid-feedback"><?php echo $cc_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <select name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                                <?php 
                                $sql = "SELECT * FROM address";
                                if($result = mysqli_query($conn, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_array($result)){
                                            if ($row['name'] == $address) {
                                                echo "<option value='". $row['name'] . "' selected>" . $row['name'];
                                                echo "</option>";
                                            } else {
                                                echo "<option value='". $row['name'] . "'>" . $row['name'];
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
                            <span class="invalid-feedback"><?php echo $address_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Customer</label>
                            <select name="customer" class="form-control <?php echo (!empty($customer_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $customer; ?>">
                            <?php 
                                $sql = "SELECT * FROM customer";
                                if($result = mysqli_query($conn, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        while($row = mysqli_fetch_array($result)){
                                            if ($row['code'] == $customer) {
                                                echo "<option value='". $row['code'] . "' selected>" . $row['code'];
                                                echo "</option>";
                                            } else {
                                                echo "<option value='". $row['code'] . "'>" . $row['code'];
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
                            <span class="invalid-feedback"><?php echo $customer_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php
                            echo '<a href="../interface.php?x=' . rand() . '#ordertable" class="btn btn-secondary ml-2">Cancel</a>';
                            mysqli_close($conn);
                        ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>