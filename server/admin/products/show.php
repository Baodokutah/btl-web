<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {

    header("Access-Control-Allow-Origin: *");


    header("Access-Control-Allow-Headers: Content-Type");
    
    $token = $_GET["token"];
    require '../auth.php';
    if (!auth($token)) {
        http_response_code(403); // Forbidden
        return;
    }

    require '../connect.php';
    $sql = "SELECT * FROM products";
    $result = $mysqli->query($sql);
    $products = [];

    while ($row = $result->fetch_assoc()) {
        $products[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'description' => $row['description'],
            'price' => $row['price'],
            'image' => $row['image'],
            'type' => $row['type'],
            'rating' => $row['rating']
        ];
    }

    echo json_encode($products);
    $mysqli->close();
}
else {
    http_response_code(405); // Method Not Allowed
}
?>