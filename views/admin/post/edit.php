<?php
use App\Connection;
use App\Table\{PostTable, CategoryTable};
use App\HTML\Form;
use App\Validators\PostValidator;
use App\ObjectHelper;
use App\Auth;

Auth::check();

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$categoryTable = new CategoryTable($pdo);
$categories = $categoryTable->list();
$post = $postTable->find($params['id']);
$categoryTable->hydratePosts([$post]);
$success = false;

$errors = [];

if (!empty($_POST)) {
  $v = new PostValidator($_POST, $postTable, $post->getID(), $categories);
  // $v = new Validator($_POST);
  // $v->rule('required', ['name', 'slug']);
  // $v->rule('lengthBetween', ['name', 'slug'], 3, 200);
  // if (empty($_POST['name'])) {
  //   $errors['name'] = 'Le champs titre ne peut être vide';
  // }
  // if (mb_strlen($_POST['name'] <= 3)) {
  //   $errors['name'] = 'Le champs titre doit contenir plus de 3 caractères';
  // }
  // $post->setName($_POST['name'])
  //     ->setContent($_POST['content'])
  //     ->setSlug($_POST['slug'])
  //     ->setCreatedAt($_POST['created_at']);
  ObjectHelper::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);
  if ($v->validate()) {
    $postTable->update($post, $_POST['categories_ids']);
    $categoryTable->hydratePosts([$post]);
    $success = true;
  } else  {
    $errors = $v->errors();
  }
}
$form = new Form($post, $errors);
?>

<?php if ($success) : ?>
  <div class="alert alert-success">
    L'article a bien été modifié.
  </div>
<?php endif ?>

<?php if (!empty($errors)) : ?>
  <div class="alert alert-danger">
    L'article n'a pas pu être modifié, merci de vérifier les champs.
  </div>
<?php endif ?>

<h1>Edition article</h1>

<?php require('_form.php') ?>