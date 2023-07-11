<?php require 'header.php';
require 'navbar.php';

if (isset($_SESSION['id_user'])):

    $sql = "SELECT * FROM users WHERE id_user = ?";
    $req = mysqli_prepare($co, $sql);

    if ($result) {
        mysqli_stmt_bind_param($stmt, "i", $_SESSION['id_user']);
        mysqli_stmt_execute($req);
        $result = mysqli_stmt_get_result($req);

        while ($row = mysqli_fetch_assoc($result)) {
            ?>

            <div class="container">
                <form method="POST">
                    <div class="row">
                        <div class="col-10">
                            <div class="col-6 p-2">
                                <input type="email" class="form-control input-custom" name="mail" placeholder="Adresse électronique"
                                    pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" required><?php echo $row['mail']; ?></input>
                                <?php if (isset($erreur['mail'])): ?>
                                    <div class="text-danger">
                                        <?= $erreur['mail']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-6 p-2">
                                <input type="text" class="form-control input-custom" name="username" placeholder="Nom d'utilisateur"
                                    pattern="^[A-Za-z0-9_]{7}$" required><?php echo $row['username']; ?></input>
                                <?php if (isset($erreur['username'])): ?>
                                    <div class="text-danger">
                                        <?= $erreur['username']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-6 p-2">
                                <input type="password" class="form-control input-custom" name="mdp"
                                    pattern="^[a-zA-Z0-9!@#$%^&*()-_+=]{8,12}$" placeholder="Mot de passe" required>
                                <?php if (isset($erreur['mdp'])): ?>
                                    <div class="text-danger">
                                        <?= $erreur['mdp']; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="row gx-3">
                                <div><i>Options de récupération</i></div>
                                <div class="col-4">
                                    <select class="form-select input-custom p-1" name="question">
                                        <option selected>Choisir une question...</option>
                                        <option value="perso">Votre personnage de fiction préféré(e)?</option>
                                        <option value="phobie">Votre pire phobie?</option>
                                        <option value="hobbie">Votre passe-temps préféré?</option>
                                    </select>
                                    <?php if (isset($erreur['question'])): ?>
                                        <div class="text-danger">
                                            <?= $erreur['question']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-4">
                                    <input type="text-area" class="form-control input-custom p-1" name="reponse"
                                        placeholder="réponse?" pattern="^[A-Za-z0-9_]+$" required><?php echo $row['reponse']; ?></input>
                                    <?php if (isset($erreur['reponse'])): ?>
                                        <div class="text-danger">
                                            <?= $erreur['reponse']; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-4"></div>
                    </div>
                    <div class="form-group row p-3 m-3">
                        <button class="btn btn-primary col-2" type=submit name="suscribe">Modifier</button>
                        <button class="btn btn-primary col-2" type=submit name="suscribe">Désactiver</button>
                    </div>


                </form>
            </div>


        <?php }
    }
endif;


$str_key = '/^[A-Za-z\s]+$/';
$spe_key = '/^[A-Za-z0-9_]{7}$/';
$mdp_key = '/^[a-zA-Z0-9!@#$%^&*()-_+=]{8,12}$/';
$mail_key = '/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/';
$choice = ['perso', 'hobbie', 'phobie'];

$mail_help = 'Veuillez saisir une adresse valide. Exemple: votrenom@domaine.com';
$prenom_help = "Veuillez saisir un prénom valide. Pour rappel, vous ne pouvez utiliser que les caractères de l'alphabet (A à Z)";
$nom_help = "Veuillez saisir un nom valide. Pour rappel, vous ne pouvez utiliser que les caractères de l'alphabet (A à Z)";
$username_help = "Veuillez saisir un pseudo valide. Pour rappel, vous ne pouvez utiliser que les caractères de l'alphabet (A à Z), des chiffres ou un underscore (_) ";
$mdp_help = "Veuillez saisir un mot de passe valide. Pour rappel, il doit compter entre 8 et 12 caractères et sont autorisés: l'alphabet (A à Z), les chiffres ou certains caractères spéciaux";
$question_help = 'Veuillez vous limiter aux questions posées';
$reponse_help = 'Veuillez saisir une réponse valide';

function check($name, $key, $help)
{
    if (preg_match($key, $_POST[$name])):
        return $_POST[$name];
    else:
        $erreur[$name] = $help;
        return null;
    endif;
}

function img_type($img)
{
    $info = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($info, $img);
    finfo_close($info);

    if ($mimeType && strpos($mimeType, 'image') === 0):
        return true;
    endif;

    return false;
}

function img_size($img, $max)
{
    $size = filesize($img);
    if ($size > $max):
        return false;
    endif;
    return true;
}

function exist($co, $sql)
{
    $result = mysqli_query($co, $sql);

    if (!$result):
        die("Erreur dans la requête : " . mysqli_error($co));
    endif;

    $row = mysqli_fetch_array($result);
    $count = $row[0];

    if ($count > 0):
        return true;
    endif;

    return false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"):
    $erreur = array();
    if (isset($_POST) && !empty($_POST)):

        $mail = check('mail', $mail_key, $mail_help);
        $username = check('username', $spe_key, $username_help);
        $mdp_init = check('mdp', $mdp_key, $mdp_help);
        $mdp = ($mdp_init !== null) ? password_hash($mdp_init, PASSWORD_DEFAULT) : null;
        $question = (in_array($_POST['question'], $choice)) ? $_POST['question'] : null;
        $reponse = check('reponse', $str_key, 'Veuillez saisir une réponse valide');

        $doublon = "SELECT COUNT(*) FROM users WHERE mail = '$mail' OR username = '$username'";
        if (exist($co, $doublon)):
            $erreur['doublon'] = "l'email et/ou l'utilisateur entré(s) existe(nt) déjà utilisé(s)!";
        endif;


        if ($_FILES["pp"]["error"] === UPLOAD_ERR_OK) {
            $img = $_FILES["pp"]["name"];
            $temp = $_FILES["pp"]["tmp_name"];
            if (!img_type($temp)):
                $erreur['img_type'] = "type d'image non supporté";
                unlink($temp);
            endif;
            if (!img_size($temp, $max)):
                $erreur['img_size'] = "limite de taille dépassée";
                unlink($temp);
            endif;
            $profile_pic = "media/" . $img;
            move_uploaded_file($temp, $profile_pic);

        } elseif ($_FILES["pp"]["error"] === UPLOAD_ERR_NO_FILE) {
            $profile_pic = "media/profil4.png";
        }

    endif;

endif;
if (empty($erreur)):

$sql = "UPDATE users SET username = ?, mail = ?, mdp = ?, question = ?, reponse = ?  WHERE id_user = ?";
$stmt = mysqli_prepare($co, $sql);

endif;
require 'footer.php'; ?>