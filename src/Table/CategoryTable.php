<?php
namespace App\Table;

use App\Model\Category;
use \PDO;

class CategoryTable extends Table {

 protected $table = "category";
 protected $class = Category::class;
 
  public function hydratePosts (array $posts): void
  {
    $postsByID = [];
    foreach($posts as $post) {
      $post->setCategories([]);
      $postsByID[$post->getID()] = $post;
    }
    $categories = $this->pdo->query('
    SELECT c.*, pc.post_id
    FROM post_category as pc
    JOIN category as c ON c.id = pc.category_id
    WHERE pc.post_id IN ('. implode(',', array_keys($postsByID)) .') ')
    ->fetchAll(PDO::FETCH_CLASS, Category::class);

    foreach($categories as $category) {
      $postsByID[$category->getPostID()]->addCategory($category);
    }
  }

  public function update(Category $category): void
  {
    $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug WHERE id = :id");
    $ok = $query->execute([
      'id' => $category->getID(),
      'name' => $category->getName(),
      'slug' => $category->getSlug(),
    ]);
    if ($ok === false) {
      throw new \Exception("Impossible de mettre à jour la catégorie");
    }
  }

  public function create(Category $category): void
  {
    $query = $this->pdo->prepare("INSERT INTO {$this->table} SET name = :name, slug = :slug");
    $ok = $query->execute([
      'name' => $category->getName(),
      'slug' => $category->getSlug(),
    ]);
    if ($ok === false) {
      throw new \Exception("Impossible de créer l'article");
    }
    $post->setID($this->pdo->lastInsertId());
  }

  public function list (): array 
  {
    $categories = $this->all();
    $result = [];
    foreach($categories as $category) {
      $result[$category->getID()] = $category->getName();
    }
    return $result;
  }

}