<?php
include 'connect.php';

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$sql = "INSERT INTO student_registrations (name, email, phone) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $phone);

if ($stmt->execute()) {
    echo "Registration successful!";
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>