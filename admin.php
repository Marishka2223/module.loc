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
    <title>Аккаунт Админ</title>
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
    <h1>Админ-панель</h1>
<h2>Объявления</h2>
<?php
$conn = new mysqli("localhost", "root", "root", "modulez");
if($conn->connect_error) {
die("Ошибка:" . $conn->connect_error);
}
$sql="SELECT * FROM  goods";
if($result =$conn->query($sql)){
echo "<table style='border-spacing: 20px 0px;'><tr ><th>Название</th><th>Описание</th><th>Подробности</th><th>Статус</th>Дата<th>Почта</th><th>Категория</th><th></th></tr>";
foreach($result as $row){
    echo "<tr>";
    echo "<td>". $row["name"] . "</td>";
    echo "<td>". $row["content"] . "</td>";
    echo "<td>". $row["rate"]."</td>";
    echo "<td>". $row["datez"] . "</td>";
    echo "<td>". $row["email_users"] . "</td>";
    echo "<td>". $row["category_name"] . "</td>";

    echo "<td><a href='update2.php?id=" . $row["id"]."'>Изменить</a></td>";
    echo "</tr>";
}
echo "</table>";
$result->free();
}else{
echo " Ошибка:" .$conn->error;
}
$conn->close();
?>
<br><br>
<h2>Пользователи</h2>
<?php
$conn = new mysqli("localhost", "root", "root", "modulez");
if($conn->connect_error) {
die("Ошибка:" . $conn->connect_error);
}
/*
$id= $_SESSION['shooterki'];
$sql="SELECT * FROM  users WHERE id = '$id'";
*/

$sql="SELECT * FROM  users";
if($result =$conn->query($sql)){
echo "<table style='border-spacing: 20px 0px;'><tr><th>Логин</th><th>Имя</th<th>Почта</th><th>Телефон</th><th></th><th></th><th></th></tr>";
foreach($result as $row){
    echo "<tr>";
    echo "<td>". $row["login"] . "</td>";
    echo "<td>". $row["name"] . "</td>";
    echo "<td>". $row["email"] . "</td>";
    echo "<td>". $row["phone"] . "</td>";
    echo "<td style='display: none;'>". $row["password"] . "</td>";

    echo "<td><a href='update2.php?id=" . $row["id"]."'>Изменить</a></td>";
    echo "</tr>";
}
echo "</table>";
$result->free();
}else{
echo " Ошибка:" .$conn->error;
}
$conn->close();
?>
<br><br>
<h2>Категории</h2>
<?php

$conn = new mysqli("localhost", "root", "root", "modulez");
if($conn->connect_error) {
die("Ошибка:" . $conn->connect_error);
}
$sql="SELECT * FROM  category";
if($result =$conn->query($sql)){
echo "<table style='border-spacing: 20px 0px;'><tr ><th>Название</th><th></th></tr>";
foreach($result as $row){
    echo "<tr>";
    echo "<td>". $row["name"] . "</td>";

    echo "<td><a href='delete3.php?id=" . $row["id"]."'>Удалить</a></td>";
    echo "</tr>";
}
echo "</table>";

echo "<a href='new_categ.php'>Новая категория</a>";

$result->free();
}else{
echo " Ошибка:" .$conn->error;
}
$conn->close();
?>
</div>

</body>