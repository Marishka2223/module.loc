<?php
session_start();

// Проверяем, авторизован ли пользователь
if (!isset($_SESSION['modulez'])) {
    header("Location: vxod.php"); // Перенаправляем на страницу входа, если пользователь не авторизован
    exit();
}

// Получаем id пользователя из сессии
$id_user = $_SESSION['modulez'];

// Проверяем, передан ли id объявления для удаления
if (!isset($_GET['id'])) {
    header("Location: acc.php"); // Если id не передан, перенаправляем на страницу аккаунта
    exit();
}

$id_good = $_GET['id']; // Получаем id объявления из параметра URL

// Подключаемся к базе данных
$conn = new mysqli("localhost", "root", "root", "modulez");
if ($conn->connect_error) {
    die("Ошибка подключения к базе данных: " . $conn->connect_error);
}

// Проверяем, принадлежит ли объявление текущему пользователю
$sql_check = "SELECT id_users FROM goods WHERE id = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("i", $id_good);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    $row = $result_check->fetch_assoc();
    if ($row['id_users'] == $id_user) {
        // Если объявление принадлежит пользователю, удаляем его
        $sql_delete = "DELETE FROM goods WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_good);
        if ($stmt_delete->execute()) {
            // Успешное удаление
            header("Location: acc.php?success=1"); // Перенаправляем с флагом успеха
            exit();
        } else {
            // Ошибка при удалении
            header("Location: acc.php?error=1"); // Перенаправляем с флагом ошибки
            exit();
        }
    } else {
        // Если объявление не принадлежит пользователю
        header("Location: acc.php?error=2"); // Перенаправляем с флагом ошибки
        exit();
    }
} else {
    // Если объявление не найдено
    header("Location: acc.php?error=3"); // Перенаправляем с флагом ошибки
    exit();
}

$stmt_check->close();
$stmt_delete->close();
$conn->close();
?>