<?php
use App\Connection;
use App\Table\{PostTable, CategoryTable};
use App\HTML\Form;
use App\Validators\PostValidator;
use App\ObjectHelper;
use App\Model\Post;
use App\Auth;

Auth::check();

$errors = [];
$pdo = Connection::getPDO();
$post = new Post();
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();

if (!empty($_POST)) {
  $postTable = new PostTable($pdo);
  $v = new PostValidator($_POST, $postTable, $post->getID(), $categories);
  ObjectHelper::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);
  if ($v->validate()) {
    $postTable->create($post, $_POST['categories_ids']);
    header('Location: ' . $router->url('admin_posts') . '?created=1');
    exit();
  } else  {
    $errors = $v->errors();
  }
}
$form = new Form($post, $errors);
?>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    L'article n'a pas pu être enregistré, merci de vérifier les champs.
  </div>
<?php endif ?>

<h1>Création article</h1>

<?php require('_form.php') ?>