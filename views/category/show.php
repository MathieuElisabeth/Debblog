<?php
use App\Connection;
use App\URL;
use App\Model\{Post, Category};
use App\PaginatedQuery;
use App\Table\{CategoryTable, PostTable};

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$category = (new CategoryTable($pdo))->find($id);

if($category->getSlug() !== $slug) {
  $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $id]);
  http_response_code(301);
  header('Location: ' . $url);
}

[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginatedForCategory($category->getID());

$link = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);

$title = "Catégorie";
?>

<h1><?= htmlentities($category->getName()) ?></h1>

<div class="card-grid">
  <?php foreach($posts as $post): ?>
  <div> 
    <?php require dirname(__DIR__) . '/post/card.php' ?>
  </div>
  <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
  <?= $paginatedQuery->previousLink($link) ?>
  <?= $paginatedQuery->nextLink($link) ?>
</div>
