<?php

namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;

final class PostTable extends Table {

  protected $table = "post";
  protected $class = Post::class;

  public function update(Post $post, array $categories): void
  {
    $this->pdo->beginTransaction();
    $query = $this->pdo->prepare("UPDATE {$this->table} SET name = :name, slug = :slug, created_at = :created, content = :content WHERE id = :id");
    $ok = $query->execute([
      'id' => $post->getID(),
      'name' => $post->getName(),
      'slug' => $post->getSlug(),
      'content' => $post->getContent(),
      'created' => $post->getCreatedAt()->format('Y-m-d H:i:s')
    ]);
    if ($ok === false) {
      throw new \Exception("Impossible de mettre à jour l'article");
    }
    $this->pdo->exec('DELETE FROM post_category WHERE post_id = ' . $post->getID());
    $query = $this->pdo->prepare('INSERT INTO post_category SET post_id = ?, category_id = ?');
    foreach($categories as $category) {
      $query->execute([$post->getID(), $category]);
    }
    $this->pdo->commit();
  }

  public function create(Post $post, array $categories): void
  {
    $this->pdo->beginTransaction();
    $query = $this->pdo->prepare("INSERT INTO {$this->table} SET name = :name, slug = :slug, created_at = :created, content = :content");
    $ok = $query->execute([
      'name' => $post->getName(),
      'slug' => $post->getSlug(),
      'content' => $post->getContent(),
      'created' => $post->getCreatedAt()->format('Y-m-d H:i:s')
    ]);
    if ($ok === false) {
      throw new \Exception("Impossible de créer l'article");
    }
    $post->setID($this->pdo->lastInsertId());
    $this->pdo->exec('DELETE FROM post_category WHERE post_id = ' . $post->getID());
    $query = $this->pdo->prepare('INSERT INTO post_category SET post_id = ?, category_id = ?');
    foreach($categories as $category) {
      $query->execute([$post->getID(), $category]);
    }
    $this->pdo->commit();
  }

  public function findPaginated()
  {
    $paginatedQuery = new PaginatedQuery(
      "SELECT * FROM post ORDER BY created_at DESC",
      "SELECT COUNT(id) FROM {$this->table}",
      $this->pdo
    );
    $posts = $paginatedQuery->getItems(Post::class);
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
  }

  public function findPaginatedForCategory(int $categoryID)
  {
    $paginatedQuery = new PaginatedQuery(
      "SELECT p.* 
      FROM post as p 
      JOIN post_category as pc ON pc.post_id = p.id 
      WHERE pc.category_id = {$categoryID}
      ORDER BY created_at DESC",
      "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryID}"
    );
    $posts = $paginatedQuery->getItems(Post::class);
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
  }
}
