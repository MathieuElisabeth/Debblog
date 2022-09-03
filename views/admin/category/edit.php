<?php
use App\Connection;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\Validators\CategoryValidator;
use App\ObjectHelper;
use App\Auth;

Auth::check();

$pdo = Connection::getPDO();
$table = new CategoryTable($pdo);
$item = $table->find($params['id']);
$success = false;
$errors = [];
$fields = ['name', 'slug'];

if (!empty($_POST)) {
  $v = new CategoryValidator($_POST, $table, $item->getID());
  ObjectHelper::hydrate($item, $_POST, $fields);
  if ($v->validate()) {
    $table->update($item);
    $success = true;
  } else  {
    $errors = $v->errors();
  }
}
$form = new Form($item, $errors);
?>

<?php if ($success) : ?>
  <div class="alert alert-success">
    La catégorie a bien été modifié.
  </div>
<?php endif ?>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    La catégorie n'a pas pu être modifié, merci de vérifier les champs.
  </div>
<?php endif ?>

<h1>Edition catégorie</h1>

<?php require('_form.php') ?>