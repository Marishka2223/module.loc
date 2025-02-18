<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['modulez'])) {
    header("Location: vxod.php"); // Перенаправляем на страницу входа, если пользователь не авторизован
    exit();
}

// Получаем id пользователя из сессии
$id_user = $_SESSION['modulez'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аккаунт</title>
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

<br>

<div  style="display: flex; align-items: center;  justify-content: center; flex-direction: column; text-decoration: none;">
<h1 style=" text-align: center; font-size: 3.1em; color: rgba(85, 85, 85, 0.82)">Ваши объявления</h1>
<br><br>
<?php
$conn = new mysqli("localhost", "root", "root", "modulez");
if($conn->connect_error) {
    die("Ошибка:" . $conn->connect_error);
}

// Используем подготовленные выражения для защиты от SQL-инъекций
$sql = "SELECT * FROM goods WHERE id_users = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id_user); // Используем id пользователя из сессии
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<table style='border-spacing: 20px 0px;'><tr><th>Название</th><th>Описание</th><th>Подробности</th><th>Статус</th><th>Почта</th><th></th><th></th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>". htmlspecialchars($row["name"]) . "</td>";
        echo "<td>". htmlspecialchars($row["content"]) . "</td>";
        echo "<td>". htmlspecialchars($row["rate"])."</td>";
        echo "<td>". htmlspecialchars($row["status"])."</td>";
        echo "<td>". htmlspecialchars($row["email_users"])."</td>";
        echo "<td><a href='update3.php?id=" . $row["id"]."'>Изменить</a></td>";
        echo "<td><a href='./delete.php?id=" . $row["id"] . "'>Удалить</a></td>"; // Передаем id товара для удаления
        echo "</tr>";     
       
    }
    echo "</table>";
} else {
    echo "У вас пока нет заказов.";
}

$stmt->close();
$conn->close();
?>
</div>
<br><br>
<div  style="display: flex; align-items: center;  justify-content: center; flex-direction: column; text-decoration: none;">
    <a href="./good_add.php">Добавить новое объявление</a>
</div>

</body>
</html>