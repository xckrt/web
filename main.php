<?php
header('Content-Type: text/html; charset=utf-8');

$db_server = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "rabota";

$connect = mysqli_connect($db_server, $db_user, $db_pass, $db_name);

if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $search_input = trim(strip_tags($_POST['book_name']));
    $search_terms = explode(' ', $search_input); // Разделяем введённый текст по пробелам

    // Создаём базовый SQL-запрос с параметрами
    $query = "SELECT * FROM data_books WHERE ";
    $conditions = [];
    $types = "";
    $params = [];

    foreach ($search_terms as $term) {
        $conditions[] = "(name LIKE ? OR janr LIKE ? OR avtor LIKE ? OR izdatel LIKE ? OR year LIKE ?)";
        $types .= "sssss";
        $like_term = "%" . $term . "%";
        array_push($params, $like_term, $like_term, $like_term, $like_term, $like_term);
    }

    $query .= implode(' OR ', $conditions);

    $stmt = $connect->prepare($query);
    if (!$stmt) {
        die("Error preparing statement: " . mysqli_error($connect));
    }

    // Проверка на корректность параметров
    if (count($params) != strlen($types)) {
        die("Error: Mismatch between number of parameters and types.");
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<div class='results' style='display: flex; flex-wrap: wrap; gap: 15px; margin-top: 20px; overflow-y: auto; max-height: calc(60vh - 120px);'>";
        while ($book = $result->fetch_assoc()) {
            echo "<div class='book-info' style='background-color: #ffffff; border-radius: 8px; padding: 15px; margin: 10px 0; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); flex: 1 1 calc(33.333% - 30px); box-sizing: border-box;'>
                <b style='font-size: 20px;'>{$book['name']}</b>
                <p><strong>Автор:</strong> {$book['avtor']}</p>
                <p><strong>Жанр:</strong> {$book['janr']}</p>
                <p><strong>Дата:</strong> {$book['year']}</p>
                <p><strong>Издательство:</strong> {$book['izdatel']}</p>
            </div>";
        }
        echo "</div>";
    } else {
        echo "<p class='error'>Книги не найдены</p>";
    }
} else {
    echo "<p class='error'>Пожалуйста, используйте форму поиска.</p>";
}

mysqli_close($connect);
?>

<button type="button" onclick="window.history.back()">Назад</button>
