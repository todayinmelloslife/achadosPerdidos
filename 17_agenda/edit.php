<?php
  include_once("templates/header.php");
  // Carregar o item do banco
  include_once("config/connection.php");
  $item = null;
  if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM itens WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($item) {
      if (isset($item['nome'])) $item['name'] = $item['nome'];
      if (isset($item['data_encontro'])) $item['data'] = $item['data_encontro'];
    }
  }
?>
  <div class="container">
    <?php include_once("templates/backbtn.html"); ?>
    <h1 id="main-title">Editar item</h1>
    <?php if($item): ?>
    <form id="create-form" action="<?= $BASE_URL ?>config/process.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="type" value="edit">
      <input type="hidden" name="id" value="<?= $item['id'] ?>">
      <div class="form-group">
        <label for="name">Nome:</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome" value="<?= htmlspecialchars($item['name']) ?>" required>
      </div>
      <div class="form-group">
        <label for="local">Onde foi encontrado:</label>
        <input type="text" class="form-control" id="local" name="local" placeholder="Digite o bloco/turma" value="<?= htmlspecialchars($item['local']) ?>" required>
      </div>
      <div class="form-group">
        <label for="data">Dia de encontro:</label>
        <input type="date" class="form-control" id="data" name="data" placeholder="Digite a data" value="<?= htmlspecialchars($item['data']) ?>">
      </div>
      <div class="form-group">
        <label for="foto">Foto:</label>
        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
        <?php if(!empty($item['foto'])): ?>
          <?php $fotoPath = $BASE_URL . 'uploads/' . rawurlencode($item['foto']); ?>
          <p>Foto atual:</p>
          <img src="<?= $fotoPath ?>" alt="Foto do item" style="max-width: 200px; height: auto;">
        <?php endif; ?>
      </div>
      <button type="submit" class="btn btn-primary">Atualizar</button>
    </form>
    <?php else: ?>
      <p>Item não encontrado.</p>
    <?php endif; ?>
  </div>
<?php
  include_once("templates/footer.php");
?>
