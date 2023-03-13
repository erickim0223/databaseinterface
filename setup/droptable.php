<!-- PHP script to drop tables -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Drop Tables</title>
</head>
<body>
    <?php
        require_once "../config.php";
        
        $query = "DROP TABLE customer";
        $conn->query($query);

        $query = "DROP TABLE address";
        $conn->query($query);

        $query = "DROP TABLE ordertable";
        $conn->query($query);

        $query = "DROP TABLE itemtable";
        $conn->query($query);

        $query = "DROP TABLE orderline";
        $conn->query($query);

        $conn->close();
    ?>
</body>
</html>