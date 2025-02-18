<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доска объявлений</title>
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

<h1 style=" text-align: center; font-size: 3.1em; color: rgba(85, 85, 85, 0.82)">Объявления</h1>

<!-- Добавляем input для поиска по имени и содержанию -->
<form method="get" action="">
    <div style=" text-align: center; font-size: 3.1em; color: rgba(85, 85, 85, 0.82)">
    <input  style="height: 20px;  width: 1000px;  box-shadow: 0 0 10px rgba(0, 0, 0, 0.23); border-radius: 20px; padding: 15px; " type="text" placeholder="введите название или описание..." name="search" id="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
    <input type="submit" value="Найти" style="margin-top: 10px; height: 50px; width: 100px;  box-shadow: 0 0 10px rgba(0, 0, 0, 0.23); border-radius: 20px; padding: 15px; ext-align: center;  ">
    </div>
</form>

<?php
// Подключение к базе данных
$conn = new mysqli("localhost", "root", "root", "modulez");

if ($conn->connect_error) {
    die("Ошибка: " . $conn->connect_error);
}

// Запрос к базе данных с учетом фильтрации по имени и содержанию
$query = "SELECT id, name, content, image, email_users, rate FROM goods WHERE id > 0 AND status ='Активно'";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $_GET['search'];
    $query .= " AND (name LIKE '%$search%' OR content LIKE '%$search%')"; // Используем LIKE для поиска по части имени или содержания
}

$result = $conn->query($query);

// Проверка, есть ли записи
if ($result->num_rows > 0) {
    // Вывод записей в ряд по 3
    echo '<div style="display: flex; flex-wrap: wrap; gap: 20px; padding: 20px;">';
    $count = 0;
    while ($row = $result->fetch_assoc()) {
        
        echo '<div style="flex: 1 1 50px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.47); padding: 15px; text-align: center; width: 40px; flex-direction: row;">';
        echo '<form method="post" action="./ad.php">';
        echo '<input type="hidden" name="action" value="add">';
        echo '<input type="hidden" name="id" value="' . $row['id'] . '">';
        echo '<input type="hidden" name="name" value="' . htmlspecialchars($row['name']) . '">';
        echo '<input type="hidden" name="described" value="' . htmlspecialchars($row['content']) . '">';
        echo '<input type="hidden" name="image" value="' . base64_encode($row['image']) . '">';
        echo '<input type="hidden" name="email" value="' . htmlspecialchars($row['email_users']) . '">';
        echo '<input type="hidden" name="rate" value="' . htmlspecialchars($row['rate']) . '">';
        
        echo '<img src="data:image/png/jpg/jpeg/gif;base64,' . base64_encode($row['image']) . '" width="200px" height="200px" style="border-radius: 8px;"/>';
        echo '<h2 style="font-size: 1.5em; margin: 10px 0;">' . htmlspecialchars($row['name']) . '</h2>';
        echo '<p style="font-size: 1em; color: #555; margin: 10px 0;">' . htmlspecialchars($row['content']) . '</p>';
        echo '<p style="font-size: 1em; color: #555; margin: 10px 0;">' . htmlspecialchars($row['email_users']) . '</p>';
        echo '<br>';
        echo '<input type="submit" value="Отозваться" style="background-color: rgba(255, 165, 62, 0.88); color: white; font-size: 1.6em; border: 1px solid rgba(255, 165, 62, 0.88);">';
        echo '</form>';
        echo '</div>';
        $count++;
        if ($count % 3 == 0) {
            echo '</div><div style="display: flex; flex-wrap: wrap; gap: 20px; padding: 20px;">';
        }
    }
    echo '</div>';
} else {
    // Если записей нет
    echo '<p style="text-align: center; font-size: 1.2em; color: #555; margin: 20px;">Объявления не найдены</p>';
}

// Закрытие соединения
$conn->close();
?> 
</body>
</html>