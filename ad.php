<?php
session_start();

// Подключение к базе данных
$conn = new mysqli("localhost", "root", "root", "modulez");

if ($conn->connect_error) {
    die("Ошибка: " . $conn->connect_error);
}

// Получение id объявления с прошлой страницы
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Запрос к базе данных для получения данных объявления
$query = "SELECT id, name, content, image, email_users, rate, datez, category_name FROM goods WHERE id = $id";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo htmlspecialchars($row['name']); ?></title>
    </head>
    <body>
      <header style="display: flex; background-color: rgba(252, 149, 53, 0.82);color: white; align-items: center; flex-wrap: row; gap: 25px; padding: 20px; max-height: 30px; ">
        <a href="./ads.php" style="color: white; margin-left: 30%; font-size: 1.5em">Главная</a>

        <?php
        if (isset($_SESSION['modulez']) && $_SESSION['modulez'] === '1') {
            echo '<a href="./admin.php" style="color: white; font-size: 1.5em">Админ панель</a>';
            echo '<a href="./logo.php" style="color: white; font-size: 1.5em">Выйти</a>';
        } elseif (isset($_SESSION['modulez']) && $_SESSION['modulez'] != '1') {
            echo '<a href="./acc.php" style="color: white; font-size: 1.5em">Мой аккаунт</a>';
            echo '<a href="./logo.php" style="color: white; font-size: 1.5em">Выйти</a>';
        } else {
            echo '<a href="./vxod.php" style="color: white; font-size: 1.5em">Войти</a>';
        }
        ?>
      </header>
      <div style="text-align: center; padding: 20px;">
          <h1><?php echo htmlspecialchars($row['name']); ?></h1>
          <img src="data:image/jpeg;base64,<?php echo base64_encode($row['image']); ?>" width="400px" height="400px" style="border-radius: 8px;"/>
          <p><?php echo htmlspecialchars($row['content']); ?></p>
          <p>Почта: <?php echo htmlspecialchars($row['email_users']); ?></p>
          <p>Подробности: <?php echo htmlspecialchars($row['rate']); ?></p>
          <p>Дата добавления объявления: <?php echo htmlspecialchars($row['datez']); ?></p>
          <p>Категория: <?php echo htmlspecialchars($row['category_name']); ?></p>
      </div>
    </body>
    </html>
    <?php
} else {
    echo '<p style="text-align: center; font-size: 1.2em; color: #555; margin: 20px;">Объявление не найдено</p>';
}

// Закрытие соединения
$conn->close();
?>