<?php
// Interface para o DAO de itens
interface ItemDAOInterface {
    public function getAll();
    public function getById($id);
    public function create($data);
    public function update($id, $data);
    public function delete($id);
}
