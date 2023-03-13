<!-- PHP script to update a row in address table -->
<?php
    // Include config file
    require_once "../config.php";
    
    // Define variables and initialize with empty values
    $fullname = $atype = $alineone = $alinetwo = "";
    $fullname_err = $atype_err = $alineone_err = $alinetwo_err = "";
    
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validate Full Name
        $input_fullname = trim($_POST["fullname"]);
        if(empty($input_fullname)) {
            $fullname_err = "Please enter a full name.";
        } else {
            $fullname = $input_fullname;
        }
        
        // Validate Address Type
        $input_atype = trim($_POST["atype"]);
        if(empty($input_atype)) {
            $atype_err = "Please enter the address type.";     
        } else {
            $atype = $input_atype;
        }

        // Validate Address Line One
        $input_alineone = trim($_POST["alineone"]);
        if(empty($input_alineone)) {
            $alineone_err = "Please enter an address line one.";     
        } else {
            $alineone = $input_alineone;
        }
        
        // Validate Address Line Two
        $input_alinetwo = trim($_POST["alinetwo"]);
        if(empty($input_alinetwo)) {
            $alinetwo_err = "Please enter an address line two.";     
        } else {
            $alinetwo = $input_alinetwo;
        }
        
        // Check input errors before inserting in database
        if(empty($fullname_err) && empty($atype_err) && empty($alineone_err) && empty($alinetwo_err)) {
            // Prepare an update statement
            $sql = "UPDATE address SET atype=?, alineone=?, alinetwo=? WHERE name=?";
            
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "ssss", $param_atype, $param_alineone, $param_alinetwo, $param_name);
                
                // Set parameters
                $param_atype = $atype;
                $param_alineone = $alinetwo;
                $param_alinetwo = $alineone;
                $param_name = $fullname;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    // Records updated successfully. Redirect to landing page
                    echo '<script type="text/javascript">
                            window.location.href = ' . '"../interface.php?x=' . rand() . '#address";
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
            $fullname = trim($_GET["id"]);
            
            // Prepare a select statement
            $sql = "SELECT * FROM address WHERE name = ?";
            if($stmt = mysqli_prepare($conn, $sql)) {
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "s", $param_name);
                
                // Set parameters
                $param_name = $fullname;
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)) {
                    $result = mysqli_stmt_get_result($stmt);
        
                    if(mysqli_num_rows($result) == 1) {
                        /* Fetch result row as an associative array. Since the result set
                        contains only one row, we don't need to use while loop */
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        
                        // Retrieve individual field value
                        $atype = $row["atype"];
                        $alineone = $row["alineone"];
                        $alinetwo = $row["alinetwo"];
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
                    <p>Please edit the input values and submit to update the address record.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" name="fullname" class="form-control <?php echo (!empty($fullname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fullname; ?>" readonly>
                            <span class="invalid-feedback"><?php echo $fullname_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address Type</label>
                            <input type="text" name="atype" class="form-control <?php echo (!empty($atype_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $atype; ?>">
                            <span class="invalid-feedback"><?php echo $atype_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address Line One</label>
                            <input type="text" name="alineone" class="form-control <?php echo (!empty($alineone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $alineone; ?>">
                            <span class="invalid-feedback"><?php echo $alineone_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Address Line Two</label>
                            <input type="text" name="alinetwo" class="form-control <?php echo (!empty($alinetwo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $alinetwo; ?>">
                            <span class="invalid-feedback"><?php echo $alinetwo_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <?php
                            echo '<a href="../interface.php?x=' . rand() . '#address" class="btn btn-secondary ml-2">Cancel</a>';
                            mysqli_close($conn);
                        ?>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>