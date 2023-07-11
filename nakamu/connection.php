<?php require_once 'header.php';
require 'db.php';
require 'navbar.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username']) && isset($_POST['mdp'])) {
        $username = $_POST['username'];
        $mdp = $_POST['mdp'];

        $sql = "SELECT * FROM users WHERE username=?";
        $stmt = mysqli_prepare($co, $sql);
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            $mdp_hash = $row['mdp'];
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['prenom'] = $row['prenom'];
            $_SESSION['pp'] = $row['profile_pic'];

            if (password_verify($mdp, $mdp_hash)) {
                $_SESSION['connecte'] = true;
                header('Location: main.php');
                exit;
            } else {
                $erreur['connexion'] = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } else {
            $erreur['connexion'] = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        $erreur['connexion'] = "Veuillez remplir tous les champs.";
    }
}
?>

<div class="container">
    <h3 class="text-center">CONNEXION</h3>
    <form method="post">
        <div class="form-group row d-flex justify-content-center">
            <div class="col-3 p-2">
                <input type="text" class="form-control input-custom" name="username" placeholder="Nom d'utilisateur"
                    pattern="^[A-Za-z0-9_]{7}$" required>
            </div>
            <div class="col-3 p-2">
                <input type="password" class="form-control input-custom" name="mdp" placeholder="Mot de passe"
                    pattern="^[a-zA-Z0-9!@#$%^&*()-_+=]{8,12}$" required>
            </div>
            <?php if (isset($erreur['connexion'])): ?>
                <div class="text-danger d-flex justify-content-center">
                    <?= $erreur['connexion']; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-center m-3">
            <button class="btn btn-primary m-2" type="submit" name="connex">Se connecter</button>
            <a class="btn btn-primary m-2" href="register.php">S'inscrire!</a>
        </div>
    </form>
</div>


<?php require 'footer.php'; ?>