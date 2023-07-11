<?php
require_once 'header.php';

$page = $_SERVER['PHP_SELF'];

if (isset($_GET['key'])) {
    $key = $_GET['key'];
    $key = filter_var($key, FILTER_VALIDATE_BOOLEAN);

    if ($nouvelleValeur === "true"):
        $proprio = $key;
    endif;
}

?>

<nav class="navbar bg-body-tertiary m-0 p-0 fixed">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <img src="files/logo.svg" alt="NAKAMU" width="100" height="100" class="d-inline-block align-text-top">
        </a>


        <ul class="nav justify-content-end">
            <?php
            if ($_SESSION['connecte'] == false) {
                if ($page == 'main.php' || $page = 'profile.php') { ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="register.php">
                            <button type="button" class="btn btn-outline-dark">Créer un compte</button>
                        </a>
                    </li>
                    <?php
                } elseif ($page == 'register.php') { ?>
                    <li class="nav-item">
                        <p>inscription</p>
                    </li>
                <?php }
            } else {
                if ($page == 'main.php') { ?>
                    <li>
                        <a class="nav-link active" aria-current="page" href="profile.php">
                            <button type="button" class="btn btn-outline-dark">acces profile perso</button>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="exit.php">
                            <button type="button" class="btn btn-outline-dark">Deconnexion</button>
                        </a>
                    </li>
                    <?php
                } elseif ($page == 'profile.php') {
                    if ($proprio == true): ?>
                        <li>
                            <a class="nav-link active" aria-current="page" href="settings.php?key=true">
                                <button type="button" class="btn btn-outline-dark">Paramètres</button>
                            </a>
                        </li>

                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="exit.php">
                            <button type="button" class="btn btn-outline-dark">Deconnexion</button>
                        </a>
                    </li>
                    <?php
                } elseif ($page == 'settings.php') { ?>
                    <li>
                        <a class="nav-link active" aria-current="page" href="profile.php">
                            <button type="button" class="btn btn-outline-dark">Profile</button>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="exit.php">
                            <button type="button" class="btn btn-outline-dark">Deconnexion</button>
                        </a>
                    </li>
                    <?php
                }
            } ?>
        </ul>
    </div>
</nav>

<?php require 'footer.php'; ?>