<?php

include("atlas/conexao.php");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    exit();
}


// Check if the GET parameter 'key' is provided
if (isset($_GET['key'])) {
    // Get the key provided in the GET parameter
    $key = $_GET['key'];

    // Check if the provided key is valid
    // You should replace 'your_secret_key' with your actual secret key
    if ($key === 'S4ZJZpoVbFJJZMnIBGwrrUtOI4JH0f') {
        // Key is valid, execute the SQL query to fetch all domains
        $sql = "SELECT dominio FROM tokens";
        $result = $conn->query($sql);

        // Check if there are any rows returned
        if ($result->num_rows > 0) {
            // Array to store domains
            $domains = array();

            // Fetch rows and store domains in the array
            while ($row = $result->fetch_assoc()) {
                $domains[] = $row['dominio'];
            }

            // Output domains as JSON
            echo json_encode($domains);
        } else {
            // No domains found
            echo "No domains found.";
        }
    } else {
        // Invalid key
        echo "Invalid key.";
    }
} else {
    // Key not provided
    echo "Key not provided.";
}

// Close MySQL connection
$conn->close();

?>