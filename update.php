<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
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

<?php  
            $conn = new mysqli("localhost", "root", "root", "modulez");
if($conn->connect_error) {
die("Ошибка:" . $conn->connect_error);
}


        if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["id"]))
        {

            

            $id=$conn->real_escape_string($_GET["id"]);
            $sql="SELECT * FROM users WHERE id = '$id'";
            if($result=$conn->query($sql)){
                if($result->num_rows > 0){
                    foreach($result as $row){
                        $login= $row["login"];
                        $name= $row["name"];
                        $email= $row["email"];
                        $phone= $row["phone"];
                        $password= $row["password"];
                        
                    }
                    echo "<h3>Изменение заказа</h3>
                    <form method='post'>
                    <input type='hidden' name='id' value='$id' />
                    <p>Логин:
                    <input type='text' name='login' value='$login' />
                    <p>Имя:
                    <input type='text' name='name' value='$name' /></p>
                       <p>Почта:
                    <input type='email' name='email' value='$email' /></p>
                       <p>Телефон:
                    <input type='text' name='phone'  pattern='\+7\(\d{3}\)-\d{3}-\d{2}-\d{2}' value='$phone' /></p>
                       <p>Пароль:
                    <input type='password' name='password' value='$password' /></p>
                    <input type='submit'  value='Сохранить'>
                    </form>";
                }
                else{
                    echo "<div>Не найдено</div>";
                }
                $result->free();
            }else{
                echo "Ошибка:".$conn->error;
            }
        }

    elseif (isset ($_POST ["id"])&& isset ($_POST ["login"]) && isset ($_POST["name"]) && isset ($_POST["email"])  && isset ($_POST["phone"])  && isset ($_POST["password"])){
        $id = $conn-> real_escape_string($_POST["id"]);
        $login = $conn->real_escape_string($_POST["login"]);
        $name = $conn->real_escape_string($_POST["name"]);
        $email = $conn->real_escape_string($_POST["email"]);
        $phone = $conn->real_escape_string($_POST["phone"]);
        $password = $conn->real_escape_string($_POST["password"]);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
    
        $sql = "UPDATE users SET login='$login', name='$name', email='$email', phone='$phone', password='$hash' WHERE id ='$id'";
        if($result=$conn->query($sql)){
            header("Location: admin.php");
        }else{ 
            echo "Ошибка:".$conn->error;
        }
}
    else{
        echo "Некорректные данные";
    }

    $conn->close();
?>
</body>
</html>