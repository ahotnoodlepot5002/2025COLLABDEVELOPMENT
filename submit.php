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

    // Get POST data
    $name   = $_POST['name'] ?? '';
    $email  = $_POST['email'] ?? '';
    $phone  = $_POST['phone'] ?? '';
    $campus = $_POST['campus'] ?? '';

    // Validate
    if (empty($name) || empty($email) || empty($phone) || empty($campus)) {
        echo "Please fill in all required fields.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO student_registrations (name, email, phone, campus) VALUES (:name, :email, :phone, :campus)");

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
    $stmt->bindParam(':campus', $campus, PDO::PARAM_STR);

    $stmt->execute();

    echo "Registration successful!";
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo "This email is already registered.";
    } else {
        echo "Error: " . $e->getMessage();
    }
}
