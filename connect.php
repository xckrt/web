<?php
session_start();

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "rabota";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

// Функция для проверки корректности email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Функция для проверки корректности пароля
function isValidPassword($password) {
    return preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password);
}

// Функция для проверки корректности логина
function isValidLogin($login) {
    return preg_match('/^[a-zA-Z]+$/', $login);
}
?>
