<?php
use App\Connection;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\Validators\CategoryValidator;
use App\ObjectHelper;
use App\Model\Category;
use App\Auth;

Auth::check();

$errors = [];
$item = new Category();
$fields = ['name', 'slug'];

if (!empty($_POST)) {
  $pdo = Connection::getPDO();
  $table = new CategoryTable($pdo);
  $v = new CategoryValidator($_POST, $table);
  ObjectHelper::hydrate($item, $_POST, $fields);
  if ($v->validate()) {
    $table->create($item);
    header('Location: ' . $router->url('admin_categories') . '?created=1');
    exit();
  } else  {
    $errors = $v->errors();
  }
}
$form = new Form($item, $errors);
?>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    La catégorie n'a pas pu être enregistré, merci de vérifier les champs.
  </div>
<?php endif ?>

<h1>Création catégorie</h1>

<?php require('_form.php') ?>