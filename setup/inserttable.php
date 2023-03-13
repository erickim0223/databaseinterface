<!-- After pressing the 'Insert to Database' button in upload.php, this
PHP file will take the uploaded XML file and start inserting the XML 
data to the database -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Insert into Tables</title>
    <style>
        body {
            max-width: 550px;
            font-family: Arial;
        }

        .affected-row {
            background: #cae4ca;
            padding: 10px;
            margin-bottom: 20px;
            border: #bdd6bd 1px solid;
            border-radius: 2px;
            color: #6e716e;
        }

        .error-message {
            background: #eac0c0;
            padding: 10px;
            margin-bottom: 20px;
            border: #dab2b2 1px solid;
            border-radius: 2px;
            color: #5d5b5b;
        }
    </style>
</head>
<body>
    <?php
        require_once "../config.php";

        if(isset($_GET["filename"]) && !empty(trim($_GET["filename"]))) {
            // Get URL parameter
            $filename = trim($_GET["filename"]);
        } else {
            // URL doesn't contain filename parameter. Redirect to error page
            header("Location: ../error.php");
            exit;
        }

        $affectedRow = 0;
        $xml = simplexml_load_file("../xmlfile/$filename") or die("Error: Cannot create object");

        foreach ($xml->children()->children() as $row) {
            $code = $row->Customer->CustomerCode;
            $fname = $row->Customer->FirstName;
            $lname = $row->Customer->LastName;
            $phone = $row->Customer->Phone;
            $email = $row->Customer->Email;
            
            $sql = "INSERT INTO customer(code, fname, lname, phone, email) VALUES ('" . $code . "','" . $fname . "','" . $lname . "','" . $phone . "','" . $email . "')";
            
            $result = mysqli_query($conn, $sql);
            
            if (!empty($result)) {
                $affectedRow ++;
            } else {
                $error_message = mysqli_error($conn) . "\n";
            }

            $aname = $row->Address->FullName;
            $atype = $row->Address->AddressType;
            $alineone = $row->Address->AddressLine1;
            $alinetwo = $row->Address->AddressLine2;

            $sql = "INSERT INTO address(name, atype, alineone, alinetwo) VALUES ('" . $aname . "','" . $atype . "','" . $alineone . "','" . $alinetwo . "')";
            
            $result = mysqli_query($conn, $sql);
            
            if (!empty($result)) {
                $affectedRow ++;
            } else {
                $error_message = mysqli_error($conn) . "\n";
            }

            foreach ($row->OrderLines->children() as $oline) {
                $itemnum = $oline->ItemNum;
                $descrip = $oline->ItemDescription;

                $sql = "INSERT INTO itemtable(itemnum, descrip) VALUES ('" . $itemnum . "','" . $descrip . "')";
                
                $result = mysqli_query($conn, $sql);
                
                if (!empty($result)) {
                    $affectedRow ++;
                } else {
                    $error_message = mysqli_error($conn) . "\n";
                }
            }
        }

        foreach ($xml->children()->children() as $row) {
            $refnum = $row->ReferenceNum;
            $countcode = $row->CountryCode;
            $aname = $row->Address->FullName;
            $code = $row->Customer->CustomerCode;

            $sql = "INSERT INTO ordertable(refnum, countrycode, addressid, customerid) VALUES ('" . $refnum . "','" . $countcode . "','" . $aname . "','" . $code . "')";
            
            $result = mysqli_query($conn, $sql);
            
            if (!empty($result)) {
                $affectedRow ++;
            } else {
                $error_message = mysqli_error($conn) . "\n";
            }
        }

        foreach ($xml->children()->children() as $row) {
            $refnum = $row->ReferenceNum;

            foreach ($row->OrderLines->children() as $oline) {
                $itemnum = $oline->ItemNum;

                $sql = "INSERT INTO orderline(orderid, itemid) VALUES ('" . $refnum . "','" . $itemnum . "')";
                
                $result = mysqli_query($conn, $sql);
                
                if (!empty($result)) {
                    $affectedRow ++;
                } else {
                    $error_message = mysqli_error($conn) . "\n";
                }
            }
        }
    ?>
    <h2>Insert XML Data to MySql Table Output</h2>
    <?php
        if ($affectedRow > 0) {
            $message = $affectedRow . " records inserted";
        } else {
            $message = "No records inserted";
        }

    ?>
    <div class="affected-row">
        <?php  echo $message; ?>
    </div>
    <?php if(!empty($error_message)) { ?>
    <div class="error-message">
        <?php echo nl2br($error_message); ?>
    </div>
    <?php } ?>
    <?php 
        echo '<form action="../interface.php" method="post">
                  <input type="submit" value="View Database" name="submit">
              </form>';
    ?>
</body>
</html>