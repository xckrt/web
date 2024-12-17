<?php
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $surname = $_POST['surname'];
    $name = $_POST['name'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (!isValidLogin($login)) {
        $error = "Логин должен содержать только латинские символы.";
    } elseif (strlen($password) < 8) {
        $error = "Пароль должен быть не менее 8 символов.";
    } elseif (!isValidPassword($password)) {
        $error = "Пароль должен содержать буквы и цифры.";
    } elseif (!isValidEmail($email)) {
        $error = "Некорректный email.";
    } else {
        // Проверка на существование логина
        $check_login_sql = "SELECT id FROM data_user WHERE login = '$login'";
        $result = mysqli_query($connect, $check_login_sql);

        if (mysqli_num_rows($result) > 0) {
            $error = "Пользователь с таким логином уже существует.";
        } else {
            // Добавление нового пользователя
            $sql = "INSERT INTO data_user (surname, name, birthdate, email, login, password)
                    VALUES ('$surname', '$name', '$birthdate', '$email', '$login', '$password')";

            if (mysqli_query($connect, $sql)) {
                header("Location: login.html");
                exit();
            } else {
                $error = "Ошибка при регистрации пользователя: " . mysqli_error($connect);
            }
        }
    }

    if (isset($error)) {
        header("Location: register.html?error=" . urlencode($error));
        exit();
    }
} else {
    header("Location: register.html");
    exit();
}
?>
