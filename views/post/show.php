<?php
use App\Connection;
use App\Model\{Post, Category};
use App\Table\{CategoryTable, PostTable};

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if($post->getSlug() !== $slug) {
  $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $id]);
  http_response_code(301);
  header('Location: ' . $url);
}
?>

<h1 class="card-title"><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><i class="far fa-clock"></i> - <?= $post->getCreatedAt()->format('d/m/Y') ?></p>
<?php foreach($post->getCategories() as $k => $category) : ?>
  <?php if ($k > 0) : ?>
    ,
  <?php endif ?>
  <a href="<?= $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]) ?>"><?= htmlentities($category->getName()) ?></a>
<?php endforeach ?>
<p class="post-content"><?= $post->getFormattedContent() ?></p>
