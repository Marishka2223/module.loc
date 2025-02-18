<?php
session_start();
?>

<?php
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

// Начинаем транзакцию
$conn->begin_transaction();

try {
    // Удаляем все записи из таблицы goods, которые ссылаются на удаляемую запись в таблице category
    $sql_delete_goods = "DELETE FROM goods WHERE category_name = (SELECT name FROM category WHERE id = ?)";
    $stmt_delete_goods = $conn->prepare($sql_delete_goods);
    $stmt_delete_goods->bind_param("i", $id_good);
    $stmt_delete_goods->execute();
    $stmt_delete_goods->close();

    // Удаляем запись из таблицы category
    $sql_delete_category = "DELETE FROM category WHERE id = ?";
    $stmt_delete_category = $conn->prepare($sql_delete_category);
    $stmt_delete_category->bind_param("i", $id_good);
    $stmt_delete_category->execute();
    $stmt_delete_category->close();

    // Фиксируем транзакцию
    $conn->commit();

    // Успешное удаление
    header("Location: admin.php?success=1"); // Перенаправляем с флагом успеха
    exit();
} catch (mysqli_sql_exception $exception) {
    // Откатываем транзакцию в случае ошибки
    $conn->rollback();

    // Ошибка при удалении
    header("Location: admin.php?error=1"); // Перенаправляем с флагом ошибки
    exit();
}

$conn->close();
?>
