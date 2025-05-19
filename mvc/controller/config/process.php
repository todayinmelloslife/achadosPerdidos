<?php

  session_start();

  include_once("connection.php");
  include_once("url.php");

  $data = $_POST;

  // MODIFICAÇÕES NO BANCO
  if(!empty($data)) {

    // Criar item
    if($data["type"] === "create") {

      $nome = $data["name"];
      $local = $data["local"];
      $data_encontro = $data["data"];
      $foto = "";
      if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto_nome = uniqid() . "_" . $_FILES["foto"]["name"];
        $foto_destino = "../uploads/" . $foto_nome;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_destino);
        $foto = $foto_nome;
      }

      $query = "INSERT INTO itens (nome, local, data_encontro, foto) VALUES (:nome, :local, :data_encontro, :foto)";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":nome", $nome);
      $stmt->bindParam(":local", $local);
      $stmt->bindParam(":data_encontro", $data_encontro);
      $stmt->bindParam(":foto", $foto);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Item criado com sucesso!";
    
      } catch(PDOException $e) {
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    } else if($data["type"] === "edit") {

      $nome = $data["name"];
      $local = $data["local"];
      $data_encontro = $data["data"];
      $id = $data["id"];

      // Buscar foto atual do item
      $foto = "";
      $queryFoto = "SELECT foto FROM itens WHERE id = :id";
      $stmtFoto = $conn->prepare($queryFoto);
      $stmtFoto->bindParam(":id", $id);
      $stmtFoto->execute();
      $resultFoto = $stmtFoto->fetch(PDO::FETCH_ASSOC);
      if ($resultFoto && !empty($resultFoto['foto'])) {
        $foto = $resultFoto['foto'];
      }

      // Se o usuário enviou uma nova foto, faz upload e atualiza
      if(isset($_FILES["foto"]) && $_FILES["foto"]["error"] == 0) {
        $foto_nome = uniqid() . "_" . $_FILES["foto"]["name"];
        $foto_destino = "../uploads/" . $foto_nome;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_destino);
        $foto = $foto_nome;
      }

      $query = "UPDATE itens 
                SET nome = :nome, local = :local, data_encontro = :data_encontro, foto = :foto 
                WHERE id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":nome", $nome);
      $stmt->bindParam(":local", $local);
      $stmt->bindParam(":data_encontro", $data_encontro);
      $stmt->bindParam(":foto", $foto);
      $stmt->bindParam(":id", $id);

      try {

        $stmt->execute();
        $_SESSION["msg"] = "Item atualizado com sucesso!";
    
      } catch(PDOException $e) {
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    } else if($data["type"] === "delete") {

      $id = $data["id"];

      $query = "DELETE FROM itens WHERE id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":id", $id);
      
      try {

        $stmt->execute();
        $_SESSION["msg"] = "Item removido com sucesso!";
    
      } catch(PDOException $e) {
        $error = $e->getMessage();
        echo "Erro: $error";
      }

    }

    // Redirect HOME
    header("Location:" . $BASE_URL . "../index.php");

  // SELEÇÃO DE DADOS
  } else {
    
    $id;

    if(!empty($_GET)) {
      $id = $_GET["id"];
    }

    // Retorna o dado de um item
    if(!empty($id)) {

      $query = "SELECT * FROM itens WHERE id = :id";

      $stmt = $conn->prepare($query);

      $stmt->bindParam(":id", $id);

      $stmt->execute();

      $item = $stmt->fetch();

    } else {

      // Retorna todos os itens
      $itens = [];

      $query = "SELECT * FROM itens";

      $stmt = $conn->prepare($query);

      $stmt->execute();
      
      $itens = $stmt->fetchAll();

    }

  }

  // FECHAR CONEXÃO
  $conn = null;
