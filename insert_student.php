<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");

$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database Connection Failed"]));
}

// Check for the keys coming from Flutter
if (isset($_POST['name']) && isset($_POST['roll_number']) && isset($_POST['email']) && isset($_POST['cgpa'])) {
    
    $name = $_POST['name'];
    $roll_number = $_POST['roll_number'];
    $email = $_POST['email'];
    $cgpa = $_POST['cgpa'];

    if (empty($name) || empty($roll_number) || empty($email) || empty($cgpa)) {
        echo json_encode(["status" => "error", "message" => "Fields cannot be blank"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO students (name, roll_number, email, cgpa) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $roll_number, $email, $cgpa);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Data submitted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save record"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Incomplete details keys missing"]);
}

$conn->close();
?>