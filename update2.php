<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
</head>
<body>

<header style="display: flex; background-color: rgba(252, 149, 53, 0.82);color: white; align-items: center; flex-wrap: row; gap: 25px; padding: 20px; max-height: 30px; ">

    <a href="./ads.php" style="color: white; margin-left: 30%; font-size: 1.5em">Главная</a>

    <?php
    if (isset($_SESSION['modulez']) && $_SESSION['modulez'] === '1') {
        echo '<a href="./admin.php" style="color: white; font-size: 1.5em">Админ панель</a>';
        echo '<a href="./logo.php" style="color: white; font-size: 1.5em">Выйти</a>';
    }

    if (isset($_SESSION['modulez']) && $_SESSION['modulez'] != '1') {
        echo '<a href="./acc.php" style="color: white; font-size: 1.5em">Мой аккаунт</a>';
        echo '<a href="./logo.php" style="color: white; font-size: 1.5em">Выйти</a>';

    }
    if (!isset($_SESSION['modulez']) ) {
        echo '<a href="./vxod.php" style="color: white; font-size: 1.5em">Войти</a>';
    }
    ?>
</header>

<?php  
$conn = new mysqli("localhost", "root", "root", "modulez");
if ($conn->connect_error) {
    die("Ошибка: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"])) {
    $id = $conn->real_escape_string($_GET["id"]);
    $sql = "SELECT * FROM goods WHERE id = '$id'";
    
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $name = $row["name"];
            $desc = $row["content"];
            $price = $row["rate"];
            $status = $row["status"];
            $email = $row["email_users"];
            $category = $row["category_name"];
            
            // Запрос для получения списка категорий
            $categories_result = $conn->query("SELECT name FROM category");
            $categories = [];
            while ($cat_row = $categories_result->fetch_assoc()) {
                $categories[] = $cat_row['name'];
            }
            
            echo "<h3>Изменение заказа</h3>
            <form method='post' enctype='multipart/form-data'>
                <input type='hidden' name='id' value='$id' />
                <p>Название: <input type='text' name='stamp' value='$name' /></p>
                <p>Описание: <input type='text' name='model' value='$desc' /></p>
                <p>Подробности: <input type='text' name='price' value='$price' /></p>
                <label for='image'>Изображение:</label>
                <input type='file' id='image' name='image'><br><br>
                <p>Почта: <input type='text' name='email' value='$email' /></p>
                <p>Категория: 
                    <select name='category'>";
                    foreach ($categories as $cat) {
                        $selected = ($cat == $category) ? 'selected' : '';
                        echo "<option value='$cat' $selected>$cat</option>";
                    }
            echo "</select>
                </p>
                <p>Статус: <select name='status' value='$status'>
                <option>Активно</option>
                <option>Неактивно</option>
                </p>
                <input type='submit' value='Сохранить'>
            </form>";
        } else {
            echo "<div>Не найдено</div>";
        }
        $result->free();
    } else {
        echo "Ошибка: " . $conn->error;
    }
} elseif (isset($_POST["id"]) && isset($_POST["stamp"]) && isset($_POST["model"]) && isset($_POST["email"]) && isset($_POST["price"]) && isset($_POST["category"]) && isset($_POST["status"])) {
    $id = $conn->real_escape_string($_POST["id"]);
    $name = $conn->real_escape_string($_POST["stamp"]);
    $desc = $conn->real_escape_string($_POST["model"]);
    $price = $conn->real_escape_string($_POST["price"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $category = $conn->real_escape_string($_POST["category"]);
    $status = $conn->real_escape_string($_POST["status"]);

    // Обработка загрузки изображения
    $image_data = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_size = $image['size'];

        if ($image_size > 2 * 1024 * 1024) {
            die("Ошибка: Размер изображения не должен превышать 2 МБ.");
        }

        // Проверка, что файл является изображением
        $image_info = getimagesize($image['tmp_name']);
        if ($image_info === false) {
            die("Ошибка: Загруженный файл не является изображением.");
        }

        // Чтение содержимого изображения
        $image_data = $conn->real_escape_string(file_get_contents($image['tmp_name']));
    }

    // Обновление данных в базе данных
    $sql = "UPDATE goods SET name='$name', content='$desc', rate='$price', email_users='$email', category_name='$category', status='$status'";
    if ($image_data !== null) {
        $sql .= ", image='$image_data'";
    }
    $sql .= " WHERE id='$id'";

    if ($conn->query($sql)) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Ошибка: " . $conn->error;
    }
} else {
    echo "Некорректные данные";
}

$conn->close();
?>
</body>
</html>