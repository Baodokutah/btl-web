<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!isset($_POST["username"]) || !isset($_POST["token"]) || !isset($_POST["address"])) {
            http_response_code(404);
            exit;
        }

        $username = $_POST["username"];
        $token = $_POST["token"];
        $address = $_POST["address"];

        $mysqli = new mysqli('localhost', 'root', '', 'btl');

        if ($mysqli->connect_error) {
            die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? AND token = ?");
        $stmt->bind_param("ss", $username, $token);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $stmt->close();

        if (!$user) {
            http_response_code(401); // Unauthorized
            exit;
        }

        $stmt = $mysqli->prepare("CALL Purchase(?, ?)");
        $stmt->bind_param("ss", $username, $address);
        $stmt->execute();

        $stmt->close();

        $mysqli->close();

        http_response_code(200);
    }
    else {
        http_response_code(405); // Method Not Allowed
    }
?>