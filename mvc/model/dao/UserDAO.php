<?php
require_once(__DIR__ . '/../models/User.php');

class UserDAO {
    private $conn;
    private $baseUrl;
    public function __construct(PDO $conn, $baseUrl) {
        $this->conn = $conn;
        $this->baseUrl = $baseUrl;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            $data = $stmt->fetch();
            return $this->buildUser($data);
        }
        return false;
    }

    public function create(User $user, $authUser = false) {
        $stmt = $this->conn->prepare("INSERT INTO users (name, lastname, email, password, token) VALUES (:name, :lastname, :email, :password, :token)");
        $stmt->bindParam(":name", $user->name);
        $stmt->bindParam(":lastname", $user->lastname);
        $stmt->bindParam(":email", $user->email);
        $stmt->bindParam(":password", $user->password);
        $stmt->bindParam(":token", $user->token);
        $stmt->execute();
        if($authUser) {
            $_SESSION["token"] = $user->token;
        }
    }

    public function authenticateUser($email, $password) {
        $user = $this->findByEmail($email);
        if($user && password_verify($password, $user->password)) {
            $token = bin2hex(random_bytes(50));
            $_SESSION["token"] = $token;
            $user->token = $token;
            $this->updateToken($user);
            return true;
        }
        return false;
    }

    public function updateToken(User $user) {
        $stmt = $this->conn->prepare("UPDATE users SET token = :token WHERE id = :id");
        $stmt->bindParam(":token", $user->token);
        $stmt->bindParam(":id", $user->id);
        $stmt->execute();
    }

    public function buildUser($data) {
        $user = new User();
        $user->id = $data["id"] ?? null;
        $user->name = $data["name"] ?? null;
        $user->lastname = $data["lastname"] ?? null;
        $user->email = $data["email"] ?? null;
        $user->password = $data["password"] ?? null;
        $user->token = $data["token"] ?? null;
        return $user;
    }
}
