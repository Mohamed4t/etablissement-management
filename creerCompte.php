<?php
    session_start();
    include_once 'db.php';

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cin = trim(post_string('cin'));
        $nom = trim(post_string('nom'));
        $email = trim(post_string('email'));
        $tel = trim(post_string('tel'));
        $login = trim(post_string('login'));
        $password = post_string('password');
        $confirmPassword = post_string('confirm_password');

        if ($cin === '' || $nom === '' || $email === '' || $tel === '' || $login === '' || $password === '') {
            $error = 'Veuillez remplir tous les champs obligatoires.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Veuillez saisir une adresse email valide.';
        } elseif ($password !== $confirmPassword) {
            $error = 'Les mots de passe ne correspondent pas.';
        } else {
            $check = $db->prepare("SELECT idEtudiant FROM etudiant WHERE login = ? OR email = ? OR cin = ?");
            $check->execute([$login, $email, $cin]);

            if ($check->fetch()) {
                $error = 'Un compte existe déjà avec ce login, email ou CIN.';
            } else {
                $insert = $db->prepare("INSERT INTO etudiant (cin, nom, email, tel, login, pass) VALUES (?, ?, ?, ?, ?, ?)");
                $insert->execute([$cin, $nom, $email, $tel, $login, password_hash($password, PASSWORD_DEFAULT)]);
                $_SESSION['account_created'] = true;
                header('Location: login.php');
                exit();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Créer un compte — EcoleLangue</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #F5F5F3; min-height: 100vh; display: flex; flex-direction: column; }
    .navbar { background: #fff; border-bottom: 0.5px solid #e5e5e5; padding: 0.85rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .brand { font-size: 15px; font-weight: 500; color: #111; display: flex; align-items: center; gap: 8px; text-decoration: none; }
    .brand i { font-size: 19px; color: #534AB7; }
    .nav-back { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 13px; text-decoration: none; border: 0.5px solid #e0e0e0; color: #555; transition: background 0.15s; }
    .nav-back:hover { background: #f5f5f5; color: #111; }
    .page { flex: 1; display: flex; align-items: center; justify-content: center; padding: 2rem 1.5rem; }
    .card { background: #fff; border: 0.5px solid #e5e5e5; border-radius: 16px; width: 100%; max-width: 460px; overflow: hidden; }
    .card-top { padding: 1.75rem 1.75rem 0; }
    .card-top .icon-wrap { width: 44px; height: 44px; background: #EEEDFE; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #534AB7; margin-bottom: 1rem; }
    .card-top h1 { font-size: 17px; font-weight: 500; color: #111; margin-bottom: 4px; }
    .card-top p { font-size: 13px; color: #888; }
    .card-body { padding: 1.5rem 1.75rem; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 6px; }
    .field input { width: 100%; padding: 9px 12px; font-size: 13.5px; border: 0.5px solid #ddd; border-radius: 8px; outline: none; transition: border-color 0.15s; background: #fff; color: #111; font-family: inherit; }
    .field input:focus { border-color: #534AB7; box-shadow: 0 0 0 3px #EEEDFE; }
    .field input::placeholder { color: #bbb; }
    .row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    .alert { padding: 10px 12px; border-radius: 8px; font-size: 13px; margin-bottom: 1rem; }
    .alert-error { background: #FCEBEB; color: #791F1F; border: 0.5px solid #F4CACA; }
    .alert-success { background: #E1F5EE; color: #085041; border: 0.5px solid #B8E4D2; }
    .btn-submit { width: 100%; padding: 10px; background: #534AB7; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.15s; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .btn-submit:hover { background: #3C3489; }
    .helper { margin-top: 1rem; text-align: center; font-size: 12.5px; color: #666; }
    .helper a { color: #534AB7; text-decoration: none; font-weight: 500; }
    .card-footer { padding: 1rem 1.75rem; border-top: 0.5px solid #f0f0f0; text-align: center; font-size: 12px; color: #bbb; }
  </style>
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php">
    <i class="ti ti-language"></i> EcoleLangue
  </a>
  <a href="login.php" class="nav-back">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
</nav>

<div class="page">
  <div class="card">
    <div class="card-top">
      <div class="icon-wrap"><i class="ti ti-user-plus"></i></div>
      <h1>Créer un compte</h1>
      <p>Inscrivez-vous pour accéder à votre espace étudiant</p>
    </div>

    <div class="card-body">
      <?php if ($error !== ''): ?>
        <div class="alert alert-error"><?= escape($error) ?></div>
      <?php endif; ?>

      <form action="" method="post">
        <div class="row">
          <div class="field">
            <label for="cin">CIN</label>
            <input type="text" id="cin" name="cin" placeholder="Ex: AB123456" required>
          </div>
          <div class="field">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" placeholder="Votre nom" required>
          </div>
        </div>

        <div class="field">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="exemple@email.com" required>
        </div>

        <div class="field">
          <label for="tel">Téléphone</label>
          <input type="text" id="tel" name="tel" placeholder="Téléphone" required>
        </div>

        <div class="field">
          <label for="login">Login</label>
          <input type="text" id="login" name="login" placeholder="Choisissez votre login" required>
        </div>

        <div class="row">
          <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" placeholder="Mot de passe" required>
          </div>
          <div class="field">
            <label for="confirm_password">Confirmer</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirmer" required>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <i class="ti ti-user-plus"></i> Créer mon compte
        </button>
      </form>

      <p class="helper">Vous avez déjà un compte ? <a href="login.php">Se connecter</a></p>
    </div>

    <div class="card-footer">
      EcoleLangue © 2026
    </div>
  </div>
</div>

</body>
</html>
