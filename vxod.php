<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
</head>
<body>
<header style="display: flex; background-color: rgba(252, 149, 53, 0.82);color: white; align-items: center; flex-wrap: row; gap: 25px; padding: 20px; max-height: 30px; ">

<a href="./ads.php" style="color: white; margin-left: 30%; font-size: 1.5em">Главная</a>

<?php
if (isset($_SESSION['shooterki']) && $_SESSION['shooterki'] === '1') {
    echo '<a href="./admin.php" style="color: white; font-size: 1.5em">Админ панель</a>';
    echo '<a href="./logout.php" style="color: white; font-size: 1.5em">Выйти</a>';
}

if (isset($_SESSION['shooterki']) && $_SESSION['shooterki'] != '1') {
    echo '<a href="./acc.php" style="color: white; font-size: 1.5em">Мой аккаунт</a>';
    echo '<a href="./logout.php" style="color: white; font-size: 1.5em">Выйти</a>';

}
if (!isset($_SESSION['shooterki']) ) {
    echo '<a href="./acc.php" style="color: white; font-size: 1.5em">Войти</a>';
}
?>
</header>

<div id="regir" style="display: flex; align-items: center;  justify-content: center; flex-direction: column; text-decoration: none;">
    <form method="post" >
    <h1>Вход</h1>
        <label>Логин:<input type="text" name="login"></label>
        <label>Пароль:<input type="password" pattern="\w{#,@,$,%,^,&,*,(,),?,<,>,«,»}" name="password" rerquired></label>
        <input name="submit" type="submit" class="button" placeholder="Войти">
    </form>
    <a href="#">Забыли пароль?</a>
    <a href="./register.php">Зарегистрироваться</a>

</div>

    <?php
if(isset($_POST['submit']) && isset($_POST['login']) && isset($_POST['password'])){

    $conn = new mysqli("localhost","root","root","modulez");

    if($conn->connect_error){
        die("Ошибка:".$conn->connect_error);
    }

    $log = $conn->real_escape_string($_POST["login"]);
    $pas = $_POST["password"]; // Не нужно экранировать пароль, так как он не используется в SQL-запросе напрямую

    $sql = mysqli_query($conn, "SELECT id, login, password FROM users WHERE login='$log'");
    $data = mysqli_fetch_assoc($sql);

    if($data && password_verify($pas, $data['password'])){ // Проверяем пароль с хешем из базы данных
        session_start();
        $_SESSION['modulez'] = $data['id'];

        if($log === 'admin' && $pas === 'admin1234'){

            header("Location: admin.php");
            exit();
        }
        if($log != 'admin' && $pas != 'admin1234'){
        
            header("Location: ads.php");
            exit();
        }

        exit();
    } else {
        echo 'Ошибка: Неверный логин или пароль';
        exit();
    }

    $conn->close();
}
?>
</body>
</html>