<?php
$categoryList = array_map(function ($category) use ($router) {
  $url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);
  return <<<HTML
    <a href="{$url}">{$category->getName()}</a>
HTML;
}, $post->getCategories());
?>

<div class="card">
  <div class="card-body">
    <h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
    <p class="text-muted"><?= $post->getCreatedAt()->format('d/m/Y') ?></p>
    
    <div><?= implode(', ', $categoryList) ?></div>
    <p><?= $post->getExcerpt() ?></p>
    <p class="btn-absolute">
      <a href="<?= $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]) ?>" class="btn">Voir plus</a>
    </p>
  </div>
</div>