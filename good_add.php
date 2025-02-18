<?php
session_start();

// Подключение к базе данных
$mysqli = new mysqli("localhost", "root", "root", "modulez");

// Проверка соединения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['modulez'])) {
    die("Пользователь не авторизован.");
}

// Получаем идентификатор пользователя из сессии
$user_id = $_SESSION['modulez']; // Предположим, что в сессии хранится id пользователя

// Получаем email пользователя из таблицы users
$query = "SELECT email FROM users WHERE id = '$user_id'";
$result = $mysqli->query($query);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'];
} else {
    die("Ошибка: Пользователь не найден.");
}

// Обработка формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $content = mysqli_real_escape_string($mysqli, $_POST['content']);
    $rate = mysqli_real_escape_string($mysqli, $_POST['rate']);
    $status = 'Активно';
    $category_name = mysqli_real_escape_string($mysqli, $_POST['category_name']);

    // Обработка загрузки изображения
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $image_size = $image['size'];

        if ($image_size > 2 * 1024 * 1024) {
            die("Ошибка: Размер изображения не должен превышать 2 МБ.");
        }

        // Чтение содержимого изображения
        $image_data = mysqli_real_escape_string($mysqli, file_get_contents($image['tmp_name']));
    } else {
        die("Ошибка: Изображение не загружено.");
    }

    // Формирование SQL-запроса
    $query = "INSERT INTO goods (name, content, image, rate, status, datez, id_users, email_users, category_name) 
              VALUES ('$name', '$content', '$image_data', '$rate', '$status', NOW(), '$user_id', '$email', '$category_name')";

    if ($mysqli->query($query)) {
        echo "Данные успешно добавлены.";
    } else {
        echo "Ошибка: " . $mysqli->error;
    }
}

// Получение списка категорий
$categories = $mysqli->query("SELECT name FROM category");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление Объявления</title>
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

    <h1>Добавление объявления</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="name">Название:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="content">Описание:</label>
        <textarea id="content" name="content" required></textarea><br><br>

        <label for="image">Изображение:</label>
        <input type="file" id="image" name="image" required><br><br>

        <label for="rate">Подробности:</label>
        <input type="text" id="rate" name="rate" required><br><br>


        <label for="category_name">Категория:</label>
        <select id="category_name" name="category_name" required>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select><br><br>

        <input type="submit" value="Добавить товар">
    </form>
</body>
</html>

<?php
$mysqli->close();
?>