<?php
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$servername = $_ENV['DB_SERVERNAME'];
$username   = $_ENV['DB_USERNAME'];
$password   = $_ENV['DB_PASSWORD'];
$dbname     = $_ENV['DB_NAME'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $name   = $_POST['name'] ?? '';
    $email  = $_POST['email'] ?? '';
    $phone  = $_POST['phone'] ?? '';
    $campus = $_POST['campus'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($campus)) {
        echo "Please fill in all required fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    $stmt = $pdo->prepare("SELECT campus_id FROM campuses WHERE campus_name = :campus");
    $stmt->bindParam(':campus', $campus, PDO::PARAM_STR);
    $stmt->execute();
    $campus_id = $stmt->fetchColumn();

    if (!$campus_id) {
        echo "Invalid campus selected.";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO student_registrations (name, email, phone, campus_id) VALUES (:name, :email, :phone, :campus_id)");
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':campus_id', $campus_id, PDO::PARAM_INT);
    $stmt->execute();

    $student_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("SELECT event_id FROM events WHERE campus_id = :campus_id AND event_name = 'Open Day'");
    $stmt->bindParam(':campus_id', $campus_id, PDO::PARAM_INT);
    $stmt->execute();
    $event_id = $stmt->fetchColumn();

    if (!$event_id) {
        echo "Could not find a matching event for this campus.";
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO student_event_registrations (student_id, event_id) VALUES (:student_id, :event_id)");
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();

    echo "Registration successful!";
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "This email is already registered.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
