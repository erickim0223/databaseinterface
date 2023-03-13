<!-- PHP script to join MySQL database -->
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Config</title>
</head>
<body>
    <?php
        $servername = "localhost";
        $username = "uhpe2u34my9yz";
        $password = "@i2J@#2g#%&d";
        $database = "dbaxge54knyg1u";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    ?>
</body>
</html>