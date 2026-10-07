<?php
    session_start();
    include_once 'db.php';
    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = trim(post_string('login-email'));
        $password = post_string('password');
        $sql = "SELECT * FROM admin WHERE login = ? OR email = ?";
        $req = $db->prepare($sql);
        $req->execute([$login, $login]);
        $admin = $req->fetch();

        $passwordValid = $admin && (
            password_verify($password, $admin['password'])
            || hash_equals($admin['password'], $password)
        );

        if ($passwordValid) {
            unset($admin['password']);
            session_regenerate_id(true);
            unset($_SESSION['csrf_token']);
            $_SESSION['admin'] = $admin;
            header('Location: dashboard.php');
            exit();
        }

        $error = 'Login ou mot de passe incorrect';
    }
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Connexion Admin — EcoleLangue</title>
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

    .card { background: #fff; border: 0.5px solid #e5e5e5; border-radius: 16px; width: 100%; max-width: 400px; overflow: hidden; }
    .card-top { padding: 1.75rem 1.75rem 0; }
    .card-top .icon-wrap { width: 44px; height: 44px; background: #FAEEDA; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: #854F0B; margin-bottom: 1rem; }
    .card-top h1 { font-size: 17px; font-weight: 500; color: #111; margin-bottom: 4px; }
    .card-top p { font-size: 13px; color: #888; }

    .card-body { padding: 1.5rem 1.75rem; }
    .field { margin-bottom: 1rem; }
    .field label { display: block; font-size: 13px; font-weight: 500; color: #444; margin-bottom: 6px; }
    .field input { width: 100%; padding: 9px 12px; font-size: 13.5px; border: 0.5px solid #ddd; border-radius: 8px; outline: none; transition: border-color 0.15s; background: #fff; color: #111; font-family: inherit; }
    .field input:focus { border-color: #854F0B; box-shadow: 0 0 0 3px #FAEEDA; }
    .field input::placeholder { color: #bbb; }

    .badge-admin { display: inline-flex; align-items: center; gap: 5px; font-size: 11px; padding: 3px 10px; border-radius: 20px; background: #FAEEDA; color: #633806; font-weight: 500; margin-bottom: 1rem; }

    .btn-submit { width: 100%; padding: 10px; background: #854F0B; color: #fff; border: none; border-radius: 8px; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.15s; font-family: inherit; display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 0.5rem; }
    .btn-submit:hover { background: #633806; }

    .card-footer { padding: 1rem 1.75rem; border-top: 0.5px solid #f0f0f0; text-align: center; font-size: 12px; color: #bbb; }
  </style>
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php">
    <i class="ti ti-language"></i> EcoleLangue
  </a>
  <a href="index.php" class="nav-back">
    <i class="ti ti-arrow-left"></i> Retour
  </a>
</nav>

<div class="page">
  <div class="card">

    <div class="card-top">
      <div class="icon-wrap"><i class="ti ti-shield-lock"></i></div>
      <span class="badge-admin"><i class="ti ti-lock"></i> Accès administrateur</span>
      <h1>Connexion admin</h1>
      <p>Espace réservé à l'administration</p>
    </div>

    <div class="card-body">
      <?php if ($error !== ''): ?>
        <p role="alert" style="margin-bottom: 1rem; padding: 10px 12px; border-radius: 8px; background: #FCEBEB; color: #791F1F; font-size: 13px;">
          <?= escape($error) ?>
        </p>
      <?php endif; ?>
      <form action="" method="post">

        <div class="field">
          <label for="login-email">Login ou email</label>
          <input type="text" id="login-email" name="login-email" placeholder="Entrez votre login" required>
        </div>

        <div class="field">
          <label for="password">Mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Entrez votre mot de passe" required>
        </div>

        <button type="submit" class="btn-submit">
          <i class="ti ti-login"></i> Se connecter
        </button>

      </form>
    </div>

    <div class="card-footer">
      EcoleLangue © 2026
    </div>

  </div>
</div>

</body>
</html>