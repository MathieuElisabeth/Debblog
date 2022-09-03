<form action="" method="POST">
  <?= $form->input('name', 'Titre'); ?>  
  <?= $form->input('slug', 'URL'); ?>
  <button class="btn btn-primary">
    <?= $item->getID() !== null ? "Modifier" : "Créer" ?>
  </button>
</form>