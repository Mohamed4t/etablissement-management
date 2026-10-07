<?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">

        <a class="navbar-brand fw-bold" href="profile.php">
            EcoleLangue
        </a>


        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <?php if(isset($_SESSION['user'])) { ?>

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            Profil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="inscription.php">
                            Inscription
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="MesDemande.php">
                            Mes Demandes
                        </a>
                    </li>

                    <li class="nav-item">
                        <form action="deconnecter.php" method="post" class="m-0">
                            <button type="submit" class="nav-link text-warning fw-bold border-0 bg-transparent">
                                Déconnecter
                            </button>
                        </form>
                    </li>

                </ul>

            <?php } ?>

        </div>

    </div>
</nav>