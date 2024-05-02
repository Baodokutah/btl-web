<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!isset($_GET["id"])) {
        http_response_code(404);
        exit;
    }

    $id = intval($_GET["id"]);

    $mysqli = new mysqli('localhost', 'root', '', 'btl');

    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    $stmt->close();

    $stmt = $mysqli->prepare("CALL GetProductComments(?)");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $comments = array();
    while($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $product['comments'] = $comments;

    $stmt->close();

    $mysqli->close();

    http_response_code(200);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($product);

    exit(0);
}
?>