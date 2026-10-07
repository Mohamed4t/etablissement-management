<?php ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoleLangue</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: 'Segoe UI', sans-serif; background: #F5F5F3; }

    .navbar { background: #fff; border-bottom: 0.5px solid #e5e5e5; padding: 0.85rem 1.5rem; display: flex; align-items: center; justify-content: space-between; }
    .brand { font-size: 15px; font-weight: 500; color: #111; display: flex; align-items: center; gap: 8px; text-decoration: none; }
    .brand i { font-size: 19px; color: #534AB7; }
    .nav-links { display: flex; gap: 6px; }
    .nav-btn { display: flex; align-items: center; gap: 6px; padding: 7px 14px; border-radius: 8px; font-size: 13px; text-decoration: none; border: 0.5px solid #e0e0e0; color: #555; transition: background 0.15s; }
    .nav-btn:hover { background: #f5f5f5; color: #111; }
    .nav-btn.primary { background: #534AB7; color: #fff; border-color: #534AB7; }
    .nav-btn.primary:hover { background: #3C3489; }

    .hero { max-width: 1040px; margin: 2rem auto 1.5rem; padding: 0 1.5rem; }
    .hero-panel { position: relative; overflow: hidden; display: grid; grid-template-columns: 1.4fr 0.8fr; align-items: center; gap: 2rem; padding: 2.5rem; border: 1px solid #e7e4f7; border-radius: 20px; background: linear-gradient(120deg, #fff 15%, #f1efff 100%); }
    .hero-copy { position: relative; z-index: 1; }
    .eyebrow { font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: #534AB7; font-weight: 600; margin-bottom: 12px; }
    .hero h1 { max-width: 570px; font-size: clamp(28px, 4vw, 42px); font-weight: 600; color: #17152b; line-height: 1.2; margin-bottom: 14px; }
    .hero p { max-width: 590px; font-size: 15px; color: #666; line-height: 1.75; }
    .hero-actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 1.5rem; }
    .hero-actions .nav-btn { padding: 10px 16px; }
    .hero-art { min-height: 190px; display: flex; align-items: center; justify-content: center; position: relative; }
    .hero-globe { width: 170px; height: 170px; display: grid; place-items: center; border-radius: 50%; background: #534AB7; color: #fff; font-size: 74px; box-shadow: 0 18px 45px #534ab733; }
    .hero-bubble { position: absolute; padding: 9px 13px; border: 1px solid #e7e4f7; border-radius: 12px; background: #fff; color: #3c3489; font-size: 13px; font-weight: 600; box-shadow: 0 8px 24px #241e5212; }
    .hero-bubble.one { top: 12px; left: 0; }
    .hero-bubble.two { right: 0; bottom: 12px; }
    .benefits { max-width: 1040px; margin: 0 auto; padding: 0 1.5rem; display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
    .benefit { display: flex; gap: 12px; align-items: flex-start; padding: 1.1rem; border: 1px solid #e8e8e5; border-radius: 12px; background: #fff; }
    .benefit-icon { flex: 0 0 36px; width: 36px; height: 36px; display: grid; place-items: center; border-radius: 9px; background: #EEEDFE; color: #534AB7; font-size: 18px; }
    .benefit h2 { font-size: 13px; color: #222; font-weight: 600; margin-bottom: 4px; }
    .benefit p { color: #777; font-size: 12px; line-height: 1.55; }

    .section-header { max-width: 1040px; margin: 2rem auto 0.75rem; padding: 0 1.5rem; display: flex; justify-content: space-between; align-items: center; }
    .section-header span { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: #999; font-weight: 500; }
    .section-header a { font-size: 13px; color: #534AB7; text-decoration: none; }

    .courses { max-width: 1040px; margin: 0 auto; padding: 0 1.5rem 2rem; display: flex; flex-direction: column; gap: 8px; }
    .course-card { background: #fff; border: 0.5px solid #e5e5e5; border-radius: 12px; padding: 1rem 1.1rem; display: flex; align-items: center; gap: 14px; text-decoration: none; transition: border-color 0.15s; }
    .course-card:hover { border-color: #aaa; }
    .course-icon { width: 40px; height: 40px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0; }
    .course-meta { flex: 1; min-width: 0; }
    .course-meta h3 { font-size: 14px; font-weight: 500; color: #111; margin-bottom: 3px; }
    .course-meta p { font-size: 12.5px; color: #888; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .course-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }
    .tag { font-size: 11.5px; padding: 3px 10px; border-radius: 20px; font-weight: 500; }
    .arrow { color: #aaa; font-size: 15px; }
    @media (max-width: 700px) {
      .navbar { align-items: flex-start; gap: 12px; flex-direction: column; }
      .nav-links { flex-wrap: wrap; }
      .hero { margin-top: 1rem; }
      .hero-panel { grid-template-columns: 1fr; padding: 1.5rem; gap: 1rem; }
      .hero-art { min-height: 150px; }
      .hero-globe { width: 130px; height: 130px; font-size: 58px; }
      .benefits { grid-template-columns: 1fr; }
    }
  </style>
</head>
<body>

<nav class="navbar">
  <a class="brand" href="index.php">
    <i class="ti ti-language"></i> EcoleLangue
  </a>
  <div class="nav-links">
    <a href="login.php" class="nav-btn"><i class="ti ti-user"></i> Espace étudiant</a>
    <a href="creerCompte.php" class="nav-btn">Créer un compte</a>
    <a href="loginAdmin.php" class="nav-btn primary"><i class="ti ti-lock"></i> Admin</a>
  </div>
</nav>

<main>
  <section class="hero">
    <div class="hero-panel">
      <div class="hero-copy">
        <div class="eyebrow">Bienvenue chez EcoleLangue</div>
        <h1>Ouvrez-vous au monde, une langue à la fois.</h1>
        <p>
          Découvrez nos cours de langues adaptés à plusieurs niveaux.
          Choisissez votre cours, envoyez votre demande d’inscription et suivez
          son statut depuis votre espace étudiant.
        </p>
        <div class="hero-actions">
          <a href="#cours" class="nav-btn primary"><i class="ti ti-book"></i> Découvrir les cours</a>
          <a href="creerCompte.php" class="nav-btn"><i class="ti ti-user-plus"></i> Rejoindre l’école</a>
        </div>
      </div>
      <div class="hero-art" aria-hidden="true">
        <span class="hero-bubble one">Bonjour !</span>
        <div class="hero-globe"><i class="ti ti-world"></i></div>
        <span class="hero-bubble two">Hello · Hola · مرحبا</span>
      </div>
    </div>
  </section>

  <section class="benefits" aria-label="Les avantages de l'école">
    <article class="benefit">
      <div class="benefit-icon"><i class="ti ti-world"></i></div>
      <div><h2>Plusieurs langues</h2><p>Explorez les langues proposées et trouvez le cours qui vous correspond.</p></div>
    </article>
    <article class="benefit">
      <div class="benefit-icon"><i class="ti ti-chart-bar"></i></div>
      <div><h2>Des niveaux adaptés</h2><p>Choisissez un cours selon le niveau indiqué dans son programme.</p></div>
    </article>
    <article class="benefit">
      <div class="benefit-icon"><i class="ti ti-clipboard-check"></i></div>
      <div><h2>Un suivi simple</h2><p>Consultez le statut de vos demandes depuis votre espace étudiant.</p></div>
    </article>
  </section>

  <section id="cours" aria-labelledby="courses-title">
    <div class="section-header">
      <span id="courses-title">Découvrez nos cours</span>
      <a href="login.php">Déjà inscrit ? Connectez-vous →</a>
    </div>

    <div class="courses">
      <?php include("cours.php"); ?>
    </div>
  </section>
</main>

</body>
</html>