<?php
  include_once("templates/header.php");
?>
  <div class="container">
    <?php include_once("templates/backbtn.html"); ?>
    <h1 id="main-title">Adicionar item</h1>
    <form id="create-form" action="<?= $BASE_URL ?>config/process.php" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="type" value="create">
      <div class="form-group">
        <label for="name">Nome:</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Digite o nome" required>
      </div>
      <div class="form-group">
        <label for="local">Onde foi encontrado:</label>
        <input type="text" class="form-control" id="local" name="local" placeholder="Digite o bloco/turma" required>
      </div>
      <div class="form-group">
        <label for="data">Dia de encontro:</label>
        <input type="date" class="form-control" id="data" name="data" placeholder="Digite a data">
      </div>
      <div class="form-group">
        <label for="foto">Foto:</label>
        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
      </div>
      <button type="submit" class="btn btn-primary">Cadastrar</button>
    </form>
  </div>
<?php
  include_once("templates/footer.php");
?>
