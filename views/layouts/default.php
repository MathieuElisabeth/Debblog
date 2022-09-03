<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($title) ? htmlentities($title) : 'Debblog' ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <style>
    <?php require('../style/style.css') ?>
  </style>
</head>
<body class="d-flex flex-column">
  <nav class="navbar navbar-expand-lg navbar-dark">
    <a href="/" class="navbar-brand">Debblog</a>
  </nav>

  <div class="container mt-4">
    <?= $content ?>
  </div>
  
  <footer class="py-4 footer mt-auto">
    <div class="container">
      Page générée en <?= round(1000 * (microtime(true) - DEBUG_TIME)) ?>ms
    </div>
  </footer> 
</body>
</html>