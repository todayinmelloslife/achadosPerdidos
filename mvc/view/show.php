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
  <div class="container" id="view-contact-container"> 
    <?php include_once("templates/backbtn.html"); ?>
    <?php if($item): ?>
      <h1 id="main-title"><?= htmlspecialchars($item["name"]) ?></h1>
      <p class="bold">Onde foi encontrado:</p>
      <p><?= htmlspecialchars($item["local"]) ?></p>
      <p class="bold">Dia de encontro:</p>
      <p><?= htmlspecialchars($item["data"]) ?></p>
      <p class="bold">Foto:</p>
      <?php if(!empty($item["foto"])): ?>
        <?php $fotoPath = $BASE_URL . 'uploads/' . rawurlencode($item["foto"]); ?>
        <img src="<?= $fotoPath ?>" alt="Foto do item" style="max-width:300px; height:auto;">
      <?php else: ?>
        <p>Sem foto.</p>
      <?php endif; ?>
    <?php else: ?>
      <p>Item não encontrado.</p>
    <?php endif; ?>
  </div>
<?php
  include_once("templates/footer.php");
?>
