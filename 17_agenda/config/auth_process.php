<?php
// Arquivo de autenticação adaptado para seu projeto
require_once(__DIR__ . "/url.php");
require_once(__DIR__ . "/connection.php");
require_once(__DIR__ . "/../models/User.php");
require_once(__DIR__ . "/../dao/UserDAO.php");

// Mensagem de retorno
$message = "";
$BASE_URL = $BASE_URL ?? "/achadosPerdidos/17_agenda/";

$userDao = new UserDAO($conn, $BASE_URL);

$type = filter_input(INPUT_POST, "type");

if($type === "register") {
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

    if($name && $lastname && $email && $password) {
        if($password === $confirmpassword) {
            if($userDao->findByEmail($email) === false) {
                $user = new User();
                $userToken = bin2hex(random_bytes(50));
                $finalPassword = password_hash($password, PASSWORD_DEFAULT);
                $user->name = $name;
                $user->lastname = $lastname;
                $user->email = $email;
                $user->password = $finalPassword;
                $user->token = $userToken;
                $auth = true;
                $userDao->create($user, $auth);
                header("Location: " . $BASE_URL . "index.php");
                exit;
            } else {
                $message = "Usuário já cadastrado, tente outro e-mail.";
            }
        } else {
            $message = "As senhas não são iguais.";
        }
    } else {
        $message = "Por favor, preencha todos os campos.";
    }
} else if($type === "login") {
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    if($userDao->authenticateUser($email, $password)) {
        header("Location: " . $BASE_URL . "index.php");
        exit;
    } else {
        $message = "Usuário e/ou senha incorretos.";
    }
} else {
    $message = "Informações inválidas!";
}

if($message !== "") {
    echo "<script>alert('" . $message . "'); window.history.back();</script>";
    exit;
}
