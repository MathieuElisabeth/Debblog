<?php
namespace App\Table;

use \PDO;

abstract class Table {

  protected $pdo;
  protected $table = null;
  protected $class = null;

  public function __construct(\PDO $pdo)
  {
    if($this->table === null) {
      throw new \Exception("La class". get_class($this) ." n'a pas de propriété $table");
    }
    if($this->class === null) {
      throw new \Exception("La class". get_class($this) ." n'a pas de propriété $class");
    }
    $this->pdo = $pdo;
  }

  public function find (int $id)
  {
    $query = $this->pdo->prepare('SELECT * FROM '. $this->table .' WHERE id = :id');
    $query->execute(['id' => $id]);
    $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
    $result = $query->fetch();
    if ($result === false) {
      throw new NotFoundException($this->table, $id);
    }
    return $result;
  }

  public function exists (string $field, $value, ?int $except = null): bool 
  {
    $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
    $params = [$value];
    if ($except !== null) {
      $sql .= " AND id != $except";
      $params[] = $except;
    }
    $query = $this->pdo->prepare($sql);
    $query->execute([$value]);
    return $query->fetch(PDO::FETCH_NUM)[0] > 0;
  }

  public function all () : array
  {
    $sql = "SELECT * FROM {$this->table}";
    return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
  }

  public function delete (int $id)
  {
    $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
    $ok = $query->execute([$id]);
    if ($ok === false) {
      throw new \Exception("Impossible de supprimer l'id: $id de la table {$this->table}");
    }
  }
}