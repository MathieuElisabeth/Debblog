<?php
use App\Connection;
use App\Table\CategoryTable;
use App\Auth;

Auth::check();

$title = 'Gestion des catégories';
$pdo = Connection::getPDO();
$link = $router->url('admin_categories');
$items = (new CategoryTable($pdo))->all();
?>

<?php if (isset($_GET['created'])) : ?>
  <div class="alert alert-success">
    La catégorie a bien été créé.
  </div>
<?php endif ?>

<?php if (isset($_GET['delete'])): ?>
<div class="alert alert-success">
  La catégorie a bien été supprimé.
</div>
<?php endif ?>

<div class="container py-4"><a href="<?= $router->url('admin_category_new') ?>" class="btn btn-primary mr-0">Créer une catégorie</a></div>
<table class="table">
  <thead>
    <th>ID</th>
    <th>Titre</th>
    <th>Slug</th>
    <th>Actions</th>
  </thead>
  <tbody>
    <?php foreach($items as $item): ?>
    <tr>
      <td>#<?= $item->getID() ?></td>
      <td>
        <a href="<?= $router->url('admin_category', ['id' => $item->getID()]) ?>">
          <?= htmlentities($item->getName()) ?>
        </a> 
      </td>
      <td><?= $item->getSlug() ?></td>
      <td>
        <a href="<?= $router->url('admin_category', ['id' => $item->getID()]) ?>" class="btn btn-primary">
          <i class="fas fa-edit"></i>
        </a>
        <form action="<?= $router->url('admin_category_delete', ['id' => $item->getID()]) ?>" method="POST"
          onsubmit="return confirm('Voulez vous vraiment supprimez la catégorie ?')" style="display: inline;">
          <button type="submit" class="btn btn-danger"><i class="fas fa-trash-alt"></i></button>
        </form>
      </td>
    </tr>
    <?php endforeach ?>
  </tbody>
</table>
