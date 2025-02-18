<?php

if(isset($_POST['login'])  && isset($_POST['name'])  && isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['email'])){


    $conn = new mysqli("localhost","root","root","modulez");

    if($conn->connect_error){
        die("Ошибка:".$conn->connect_error);
    }

$login=$conn->real_escape_string($_POST["login"]);
$name=$conn->real_escape_string($_POST["name"]);
$email=$conn->real_escape_string($_POST["email"]);
$phone=$conn->real_escape_string($_POST["phone"]);
$password=$conn->real_escape_string($_POST["password"]);
$hash = password_hash($password, PASSWORD_DEFAULT);

// Проверка, существует ли почта в базе данных
$query = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Если почта уже существует, выводим сообщение об ошибке
    $_SESSION['error'] = "Почта уже используется.";
   echo "Почта уже зарегистрирована";
   echo "<br>";
   echo "<a href ='./register.php'>Обратно</a>";
    exit();
} else {

$sql="INSERT INTO users (login, name, email, password, phone) VALUES ('$login', '$name', '$email' ,'$hash', '$phone')";
if($conn->query($sql)){
    header("Location: vxod.php");
}

else{
    echo "Ошибка: ".$conn->error;
}
}
$conn->close();
}


?>