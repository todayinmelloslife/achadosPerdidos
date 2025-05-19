<?php
  include("templates/header.php");
  // Carregar os itens do banco
  include_once("config/connection.php");
  $items = [];
  $query = "SELECT * FROM itens";
  try {
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Corrigir nomes das chaves para o template
    foreach ($items as &$item) {
      if (isset($item['nome'])) $item['name'] = $item['nome'];
      if (isset($item['data_encontro'])) $item['data'] = $item['data_encontro'];
      // Corrigir caminho da foto para exibição
      if (!empty($item['foto'])) {
        $item['foto_path'] = $BASE_URL . 'uploads/' . rawurlencode($item['foto']);
      } else {
        $item['foto_path'] = '';
      }
    }
    unset($item);
  } catch(PDOException $e) {
    $printMsg = "Erro ao buscar itens: " . $e->getMessage();
  }
?>
  <div class="container">
    <?php if(isset($printMsg) && $printMsg != ''): ?>
      <p id="msg"><?= $printMsg ?></p>
    <?php endif; ?>
    <h1 id="main-title">Itens Encontrados</h1>
    <?php if(count($items) > 0): ?>
      <table class="table" id="items-table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Nome</th>
            <th scope="col">Local</th>
            <th scope="col">Data</th>
            <th scope="col">Foto</th>
            <th scope="col"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($items as $item): ?>
            <tr>
              <td scope="row" class="col-id"><?= $item["id"] ?></td>
              <td scope="row"><?= $item["name"] ?></td>
              <td scope="row"><?= $item["local"] ?></td>
              <td scope="row"><?= $item["data"] ?></td>
              <td scope="row">
                <?php if(!empty($item["foto_path"])): ?>
                  <img src="<?= $item["foto_path"] ?>" alt="Foto do item" style="max-width: 60px; height: auto;">
                <?php else: ?>
                  Sem foto
                <?php endif; ?>
              </td>
              <td class="actions">
                <a href="<?= $BASE_URL ?>show.php?id=<?= $item["id"] ?>"><i class="fas fa-eye check-icon"></i></a>
                <a href="<?= $BASE_URL ?>edit.php?id=<?= $item["id"] ?>"><i class="far fa-edit edit-icon"></i></a>
                <form class="delete-form" action="<?= $BASE_URL ?>/config/process.php" method="POST">
                  <input type="hidden" name="type" value="delete">
                  <input type="hidden" name="id" value="<?= $item["id"] ?>">
                  <button type="submit" class="delete-btn"><i class="fas fa-times delete-icon"></i></button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>  
      <p id="empty-list-text">Ainda não há itens cadastrados, <a href="<?= $BASE_URL ?>create.php">clique aqui para adicionar</a>.</p>
    <?php endif; ?>
  </div>
<?php
  include_once("templates/footer.php");
?>
