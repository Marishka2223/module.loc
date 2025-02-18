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
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавление категории</title>
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
    <form method="post">
        <label for="name">Название:</label>
        <input type="text" id="name" name="name" required><br><br>

        <input type="submit" value="Добавить категорию">
    </form>
</body>
</html>


<?php 
// Обработка формы
if (isset($_POST['name'])) {
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);

    $sql="INSERT INTO category ( name) VALUES ( '$name')";
if($mysqli->query($sql)){
    header("Location: admin.php");
}
else{
    echo "Ошибка: ".$mysqli->error;
}

}


?>


<?php
$mysqli->close();
?>