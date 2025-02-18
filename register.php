<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../styles/stiles_for_Main.css">
    <script src="../javaScript/functions.js" defer></script>
</head>
<body>
<header style="display: flex; background-color: rgba(252, 149, 53, 0.82);color: white; align-items: center; flex-wrap: row; gap: 25px; padding: 20px; max-height: 30px; ">

<a href="./ads.php" style="color: white; margin-left: 30%; font-size: 1.5em; ">Главная</a>

<?php
if (isset($_SESSION['modulez']) && $_SESSION['modulez'] === 'admin') {
    echo '<a href="./admin.php" style="color: white; font-size: 1.5em">Админ панель</a>';
    echo '<a href="./logout.php" style="color: white; font-size: 1.5em">Выйти</a>';
}

if (isset($_SESSION['modulez']) && $_SESSION['modulez'] != 'admin') {
    echo '<a href="./acc.php" style="color: white; font-size: 1.5em">Мой аккаунт</a>';
    echo '<a href="./logout.php" style="color: white; font-size: 1.5em">Выйти</a>';

}
if (!isset($_SESSION['modulez']) ) {
    echo '<a href="./vxod.php" style="color: white; font-size: 1.5em">Войти</a>';
}
?>
</header>
    <header class="header" style="display: flex; align-items: center; justify-content: center">
    </header>
    <main style="display: flex; align-items: center; justify-content: center; flex-direction: column; text-decoration: none;">
        <h1 style=" color: rgba(85, 85, 85, 0.82)">Регистрация</h1>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div style="color: red;">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div style="color: green;">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>
        <form action="./register_func.php" method="post" id="form" onsubmit="return checkPassword(this)">
            <div class="contBlur">
                <input type="text" name="login" id="login" placeholder="Логин" pattern="^[A-Za-zА-Яа-яЁё\s]{3,}" required>
            </div>
            <br>
            <div>
                <input type="tel" name="name" id="name" pattern="^[A-Za-zА-Яа-яЁё\s]{2,}" placeholder="ФИО" required>
            </div>
            <br>
            <div>
                <input type="email" name="email" id="email" placeholder="Почта" required>
            </div>
            <br>
            <div>
                <input type="tel" name="phone" id="phone" pattern="\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}" placeholder="+7(XXX)-XXX-XX-XX" required>
            </div>
            <br>
            <div>
                <input type="password" name="password" id="password" placeholder="Пароль" pattern="^(?=.*[A-Za-z])(?=.*\d)(?=.*[#@$%^&*()?<>«»])[A-Za-z\d#@$%^&*()?<>«»]{8,}$" required>
            </div>
            <br>
            <div>
                <input type="password" name="password_d" id="password_d" placeholder="Подтвердите пароль" required>
            </div>
            <br>
            <div>
                <input type="submit" value="Зарегистрироваться">
                <div class="blur"></div>
            </div>
        </form>

        <script>
            function checkPassword(form) {
                const password1 = form.password.value;
                const password2 = form.password_d.value;

                if (password1 === '') {
                    alert("Введите пароль");
                    return false;
                } else if (password2 === '') {
                    alert("Подтвердите пароль");
                    return false;
                } else if (password1 !== password2) {
                    alert("Пароли не совпадают.");
                    return false;
                }
                return true;
            }
        </script>

        <section id="links">
            <a class="reg" href="./index.php"><h2 class="text t2">Войти</h2></a>
        </section>

        <section id="err">
        </section>
    </main>
    <footer></footer>
</body>
</html>