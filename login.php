<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = trim(strip_tags($_POST['login']));
    $password = trim(strip_tags($_POST['password']));

    if (empty($login) || empty($password)) {
        header("Location: login.html?error=" . urlencode("Пожалуйста, заполните все поля."));
        exit;
    }

    $stmt = $connect->prepare("SELECT id, password FROM data_user WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($password === $row['password']) {
            $_SESSION['login'] = $login;
            header("Location: main.html");
            exit();
        } else {
            header("Location: login.html?error=" . urlencode("Неверный пароль"));
            exit();
        }
    } else {
        header("Location: login.html?error=" . urlencode("Пользователь не найден"));
        exit();
    }

    $stmt->close();
} else {
    header("Location: login.html");
    exit();
}

mysqli_close($connect);
?>
