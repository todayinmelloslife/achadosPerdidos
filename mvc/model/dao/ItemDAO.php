<?php
// DAO para a tabela 'itens'
class ItemDAO {
    private $conn;
    public function __construct(PDO $conn) {
        $this->conn = $conn;
    }

    // Retorna todos os itens
    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM itens");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca item por ID
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM itens WHERE id = :id");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cria novo item
    public function create($data) {
        $stmt = $this->conn->prepare("INSERT INTO itens (nome, local, data_encontro, foto) VALUES (:nome, :local, :data_encontro, :foto)");
        $stmt->bindParam(":nome", $data['nome']);
        $stmt->bindParam(":local", $data['local']);
        $stmt->bindParam(":data_encontro", $data['data_encontro']);
        $stmt->bindParam(":foto", $data['foto']);
        return $stmt->execute();
    }

    // Atualiza item
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE itens SET nome = :nome, local = :local, data_encontro = :data_encontro, foto = :foto WHERE id = :id");
        $stmt->bindParam(":nome", $data['nome']);
        $stmt->bindParam(":local", $data['local']);
        $stmt->bindParam(":data_encontro", $data['data_encontro']);
        $stmt->bindParam(":foto", $data['foto']);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Deleta item
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM itens WHERE id = :id");
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
