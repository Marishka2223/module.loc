<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['modulez'])) {
    header("Location: vxod.php"); // Перенаправляем на страницу входа, если пользователь не авторизован
    exit();
}

$id_good = $_GET['id']; // Получаем id объявления из параметра URL

// Подключаемся к базе данных
$conn = new mysqli("localhost", "root", "root", "modulez");
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}


        // Если объявление принадлежит пользователю, удаляем его
        $sql_delete = "DELETE FROM goods WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_good);
        if ($stmt_delete->execute()) {
            // Успешное удаление
            header("Location: admin.php?success=1"); // Перенаправляем с флагом успеха
            exit();
        } else {
            // Ошибка при удалении
            header("Location: admin.php?error=1"); // Перенаправляем с флагом ошибки
            exit();
        }
   

$stmt_check->close();
$stmt_delete->close();
$conn->close();
?>