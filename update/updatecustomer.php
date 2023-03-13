<!-- PHP script to update a row in customer table -->
<?php
    // Include config file
    require_once "../config.php";
    
    // Define variables and initialize with empty values
    $cc = $fname = $lname = $phone = $email = "";
    $cc_err = $fname_err = $lname_err = $phone_err = $email_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate customer code
        $input_cc = trim($_POST["cc"]);
        if(empty($input_cc)) {
            $cc_err = "Please enter a customer code.";
        } else {
            $cc = $input_cc;
        }
        
        // Validate first name
        $input_fname = trim($_POST["fname"]);
        if(empty($input_fname)) {
            $fname_err = "Please enter a first name.";     
        } else {
            $fname = $input_fname;
        }

        // Validate last name
        $input_lname = trim($_POST["lname"]);
        if(empty($input_lname)) {
            $lname_err = "Please enter a last name.";     
        } else {
            $lname = $input_lname;
        }
        
        // Validate phone
        $input_phone = trim($_POST["phone"]);
        if(empty($input_phone)) {
            $phone_err = "Please enter a phone number.";     
        } else {
            $phone = $input_phone;
        }

        // Validate email
        $input_email = trim($_POST["email"]);
        if(empty($input_email)) {
            $email_err = "Please enter an email.";     
        } else {
            $email = $input_email;
        }
        
        // Check input errors before inserting in database
        if(empty($cc_err) && empty($fname_err) && empty($lname_err) && empty($phone_err) && empty($email_err)) {
            // Prepare an update statement
            $sql = "UPDATE customer SET fname=?, lname=?, phone=?, email=? WHERE code=?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss", $param_fname, $param_lname, $param_phone, $param_email, $param_cc);
                
                // Set parameters
                $param_fname = $fname;
                $param_lname = $lname;
                $param_phone = $phone;
                $param_email = $email;
                $param_cc = $cc;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records updated successfully. Redirect to landing page
                    echo '<script type="text/javascript">
                            window.location.href = ' . '"../interface.php?x=' . rand() . '#customer";
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
            $cc = trim($_GET["id"]);
            
            // Prepare a select statement
            $sql = "SELECT * FROM customer WHERE code = ?";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_cc);
                
                // Set parameters
                $param_cc = $cc;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        /* Fetch result row as an associative array. Since the result set
                        contains only one row, we don't need to use while loop */
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        // Retrieve individual field value
                        $fname = $row["fname"];
                        $lname = $row["lname"];
                        $phone = $row["phone"];
                        $email = $row["email"];
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
                    <p>Please edit the input values and submit to update the customer record.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Customer Code</label>
                            <input type="text" name="cc" class="form-control <?php echo (!empty($cc_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cc; ?>" readonly>
                            <span class="invalid-feedback"><?php echo $cc_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="fname" class="form-control <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fname; ?>">
                            <span class="invalid-feedback"><?php echo $fname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="lname" class="form-control <?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lname; ?>">
                            <span class="invalid-feedback"><?php echo $lname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                            <span class="invalid-feedback"><?php echo $phone_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                            <span class="invalid-feedback"><?php echo $email_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php
                            echo '<a href="../interface.php?x=' . rand() . '#customer" class="btn btn-secondary ml-2">Cancel</a>';
                            mysqli_close($conn);
                        ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>