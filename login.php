<?php
session_start();
include 'db.php';

$user = $_POST['user'];
$pass = $_POST['pass'];

$res = $conn->query("SELECT * FROM doctor WHERE username='$user' AND password='$pass'");

if (!$res) {
    die("Ошибка запроса: " . $conn->error);
}

if ($row = $res->fetch_assoc()) {
    $_SESSION['doctor_id'] = $row['id'];
    $_SESSION['doctor_name'] = $row['full_name'];
    header("Location: dashboard.php");
} else {
    echo "<script>alert('Неверный логин или пароль!'); window.location.href='index.html';</script>";
}
?>