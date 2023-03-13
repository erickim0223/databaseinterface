<!-- PHP script to create the inital datatables in the MySQL database -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Create Tables</title>
</head>
<body>
    <?php
        require_once "../config.php";
        
        $query="CREATE TABLE customer(code char(6) UNIQUE, fname char(10), lname char(10), phone char(12), email char(20), PRIMARY KEY (code))";
        $conn->query($query);

        $query="CREATE TABLE address(name char(10) UNIQUE, atype char(10), alineone char(40), alinetwo char(40), PRIMARY KEY (name))";
        $conn->query($query);

        $query="CREATE TABLE ordertable(refnum char(10) UNIQUE, countrycode char(10), addressid char(10), customerid char(6), PRIMARY KEY (refnum), FOREIGN KEY (addressid) REFERENCES address(name), FOREIGN KEY (customerid) REFERENCES customer(code))";
        $conn->query($query);

        $query="CREATE TABLE itemtable(itemnum char(10) UNIQUE, descrip char(20), PRIMARY KEY (itemnum))";
        $conn->query($query);

        $query="CREATE TABLE orderline(orderid char(10), itemid char(10), FOREIGN KEY (orderid) REFERENCES ordertable(refnum), FOREIGN KEY (itemid) REFERENCES itemtable(itemnum), PRIMARY KEY (orderid, itemid))";
        $conn->query($query);

        $conn->close();
    ?>
</body>
</html>