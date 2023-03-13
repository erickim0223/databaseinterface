<!-- PHP script to create a row in ordertable -->
<?php
    ob_start();
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
            $address_err = "Please select an address.";     
        } else {
            $address = $input_address;
        }
        
        // Validate customer
        $input_customer = trim($_POST["customer"]);
        if(empty($input_customer)) {
            $customer_err = "Please select the customer code.";     
        } else {
            $customer = $input_customer;
        }
        
        // Check input errors before inserting in database
        if(empty($refnum_err) && empty($address_err) && empty($customer_err) && empty($cc_err)) {
            // Prepare an insert statement
            $sql = "INSERT INTO ordertable (refnum, countrycode, addressid, customerid) VALUES (?, ?, ?, ?)";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_refnum, $param_cc, $param_address, $param_customer);
                
                // Set parameters
                $param_refnum = $refnum;
                $param_cc = $countrycode; 
                $param_address = $address;
                $param_customer = $customer;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records created successfully. Redirect to landing page
                    header("Location: ../interface.php?x=" . rand() . "#ordertable");
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
                    <p>Please fill this form and submit to add an order record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Reference Number</label>
                            <input type="text" name="refnum" class="form-control <?php echo (!empty($refnum_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $refnum; ?>">
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
                                            echo "<option value='". $row['name'] . "'>" . $row['name'];
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
                                            echo "<option value='". $row['code'] . "'>" . $row['code'];
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