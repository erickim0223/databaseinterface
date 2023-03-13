<!-- PHP file that displays the 5 data tables from the MySQL database. It uses
Bootstrap 4, SQL queries, and other PHP files to create a simple interface 
that has a navigation bar and CUD (Create, Update, Delete) buttons -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Interface</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
            padding-top: 62px;
        }
        #itemtable {
            height: 100vh;
        }
        table tr td:last-child {
            width: 70px;
        }
        nav {
            font-size: 20px;
        }
        .nav-item {
            padding: 0 40px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
    </script>
</head>
<body>
    <?php
        echo '<br>
              <br>
              <div class="jumbotron text-center">
                <h1>Database Interface</h1>
              </div>';
    ?>

    <nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-center fixed-top">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="#ordertable">Order</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#orderline">Order Line</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#address">Address</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#customer">Customer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#itemtable">Item</a>
            </li>
        </ul>
    </nav>
    
    <!-- Display order table -->
    <div class="wrapper" id="ordertable">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Order Details</h2>
                        <?php
                            echo '<a href="create/createorder.php?x=' . rand() .'" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Order</a>';
                        ?>
                    </div>
                    <?php
                        // Include config file
                        require_once "config.php";
                        
                        // Attempt select query execution
                        $sql = "SELECT * FROM ordertable";
                        if($result = mysqli_query($conn, $sql)) {
                            if(mysqli_num_rows($result) > 0) {
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Reference Number</th>";
                                            echo "<th>Country Code</th>";
                                            echo "<th>Address (Full Name)</th>";
                                            echo "<th>Customer (Code)</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['refnum'] . "</td>";
                                            echo "<td>" . $row['countrycode'] . "</td>";
                                            echo "<td>" . $row['addressid'] . "</td>";
                                            echo "<td>" . $row['customerid'] . "</td>";
                                            echo "<td>";
                                                echo '<a href="update/updateorder.php?x=' . rand() . '&id='. $row['refnum'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="delete/delete.php?table=ordertable&key=refnum&id='. $row['refnum'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                // Free result set
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }
                    ?>
                </div>
            </div>        
        </div>
    </div>

    <!-- Display orderline table -->
    <div class="wrapper" id="orderline">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Order Line Details</h2>
                        <?php
                            echo '<a href="create/createoline.php?x=' . rand() .'" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Order Line</a>';
                        ?>
                    </div>
                    <?php
                        $sql = "SELECT * FROM orderline";
                        if($result = mysqli_query($conn, $sql)) {
                            if(mysqli_num_rows($result) > 0){
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Reference Number</th>";
                                            echo "<th>Item Number</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['orderid'] . "</td>";
                                            echo "<td>" . $row['itemid'] . "</td>";
                                            echo "<td>";
                                                echo '<a href="update/updateoline.php?x=' . rand() . '&orderid='. $row['orderid'] .'&itemid=' . $row['itemid'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="delete/deleteoline.php?orderid='. $row['orderid'] .'&itemid=' . $row['itemid'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }
                    ?>
                </div>
            </div>        
        </div>
    </div>

    <!-- Display address table -->
    <div class="wrapper" id="address">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Address Details</h2>
                        <?php
                            echo '<a href="create/createaddress.php?x=' . rand() .'" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Address</a>';
                        ?> 
                    </div>
                    <?php
                        $sql = "SELECT * FROM address";
                        if($result = mysqli_query($conn, $sql)) {
                            if(mysqli_num_rows($result) > 0) {
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Full Name</th>";
                                            echo "<th>Address Type</th>";
                                            echo "<th>Address Line 1</th>";
                                            echo "<th>Address Line 2</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['name'] . "</td>";
                                            echo "<td>" . $row['atype'] . "</td>";
                                            echo "<td>" . $row['alineone'] . "</td>";
                                            echo "<td>" . $row['alinetwo'] . "</td>";
                                            echo "<td>";
                                                echo '<a href="update/updateaddress.php?x=' . rand(). '&id='. $row['name'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="delete/delete.php?table=address&key=name&id='. $row['name'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }
                    ?>
                </div>
            </div>        
        </div>
    </div>

    <!-- Display customer table -->
    <div class="wrapper" id="customer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Customer Details</h2>
                        <?php
                            echo '<a href="create/createcustomer.php?x=' . rand() .'" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Customer</a>';
                        ?> 
                    </div>
                    <?php
                        $sql = "SELECT * FROM customer";
                        if($result = mysqli_query($conn, $sql)) {
                            if(mysqli_num_rows($result) > 0) {
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Customer Code</th>";
                                            echo "<th>First Name</th>";
                                            echo "<th>Last Name</th>";
                                            echo "<th>Phone</th>";
                                            echo "<th>Email</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['code'] . "</td>";
                                            echo "<td>" . $row['fname'] . "</td>";
                                            echo "<td>" . $row['lname'] . "</td>";
                                            echo "<td>" . $row['phone'] . "</td>";
                                            echo "<td>" . $row['email'] . "</td>";
                                            echo "<td>";
                                                echo '<a href="update/updatecustomer.php?x=' . rand(). '&id='. $row['code'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="delete/delete.php?table=customer&key=code&id='. $row['code'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }
                    ?>
                </div>
            </div>        
        </div>
    </div>

    <!-- Display item table -->
    <div class="wrapper" id="itemtable">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="mt-5 mb-3 clearfix">
                        <h2 class="pull-left">Item Details</h2>
                        <?php
                            echo '<a href="create/createitem.php?x=' . rand() .'" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Item</a>';
                        ?>
                    </div>
                    <?php
                        $sql = "SELECT * FROM itemtable";
                        if($result = mysqli_query($conn, $sql)) {
                            if(mysqli_num_rows($result) > 0) {
                                echo '<table class="table table-bordered table-striped">';
                                    echo "<thead>";
                                        echo "<tr>";
                                            echo "<th>Item Number</th>";
                                            echo "<th>Item Description</th>";
                                            echo "<th>Action</th>";
                                        echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while($row = mysqli_fetch_array($result)) {
                                        echo "<tr>";
                                            echo "<td>" . $row['itemnum'] . "</td>";
                                            echo "<td>" . $row['descrip'] . "</td>";
                                            echo "<td>";
                                                echo '<a href="update/updateitem.php?x=' . rand(). '&id='. $row['itemnum'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                                echo '<a href="delete/delete.php?table=itemtable&key=itemnum&id='. $row['itemnum'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";                            
                                echo "</table>";
                                mysqli_free_result($result);
                            } else {
                                echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                            }
                        } else {
                            echo "Something went wrong. Please try again later.";
                        }
                        // Close connection
                        mysqli_close($conn);
                    ?>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>