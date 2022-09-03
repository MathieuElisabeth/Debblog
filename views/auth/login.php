<?php
use App\Model\User;
use App\HTML\Form;
use App\Connection;
use App\Table\UserTable;
use App\Table\Exception\NotFoundException;

$user = new User();
$errors =[];
if (!empty($_POST)) {
  $user->setUsername($_POST['username']);
  if (empty($_POST['username']) || empty($_POST['password'])) {
    $errors['password'] = ['Identifiant ou mot de passe incorrect'];
  }
  $table = new UserTable(Connection::getPDO());
  try {
    $u = $table->findByUsername($_POST['username']);
    $u->getPassword();
    $_POST['password'];
    if (password_verify($_POST['password'], $u->getPassword()) === false) {
      $errors['password'] = ['identifiant ou mot de passe incorrect'];
    } else {
      session_start();
      $_SESSION['auth'] = $u->getID();
      header('Location: ' . $router->url('admin_posts'));
      exit();
    }
  } catch (NotFoundException $e) {
    $errors['password'] = ['identifiant ou mot de passe incorrect'];
  }
}
$form = new Form($user, $errors);

?>

<h1>Connexion</h1>

<?php if (isset($_GET['forbidden'])): ?>
<div class="alert alert-danger">
  Vous ne pouvez pas accéder à cette page !
</div>
<?php endif ?>

<form action="" method="POST">
  <?= $form->input('username', 'Nom d\'utilisateur'); ?>
  <?= $form->input('password', 'Mot de passe'); ?>
  <button type="submit" class="btn btn-primary">Connexion</button>
</form>