<?php
use App\Connection;
use App\Table\PostTable;
use App\Auth;

Auth::check();

$title = 'Administration';
$pdo = Connection::getPDO();
$link = $router->url('admin_posts');
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();
?>

<?php if (isset($_GET['created'])) : ?>
  <div class="alert alert-success">
    L'article a bien été créé.
  </div>
<?php endif ?>

<?php if (isset($_GET['delete'])): ?>
<div class="alert alert-success">
  L'article a bien été supprimé.
</div>
<?php endif ?>

<div class="container py-4"><a href="<?= $router->url('admin_post_new') ?>" class="btn btn-primary mr-0">Créer un article</a></div>
<table class="table">
  <thead>
    <th>ID</th>
    <th>Titre</th>
    <th>Actions</th>
  </thead>
  <tbody>
    <?php foreach($posts as $post): ?>
    <tr>
      <td>#<?= $post->getID() ?></td>
      <td>
        <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>">
          <?= htmlentities($post->getName()) ?>
        </a> 
      </td>
      <td>
        <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>" class="btn btn-primary">
          <i class="fas fa-edit"></i>
        </a>
        <form action="<?= $router->url('admin_post_delete', ['id' => $post->getID()]) ?>" method="POST"
          onsubmit="return confirm('Voulez vous vraiment supprimez l\'article ?')" style="display: inline;">
          <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
        </form>
      </td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>

<div class="d-flex justify-content-between my-4">
  <?= $pagination->previousLink($link) ?>
  <?= $pagination->nextLink($link) ?>
</div>
