<?php require_once 'header.php';
require 'db.php';

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
        $prenom = check('prenom', $str_key, $prenom_help);
        $nom = check('nom', $str_key, $nom_help);
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

require 'navbar.php'; ?>

<div class="container justify-content-center">
    <form method="POST" enctype="multipart/form-data">
        <div class=row>

            <div class="col-10">
                <h3 class="h3">INFOS GENERALES</h3>
                <div class="form-group">

                    <div class="col-6 p-2">
                        <input type="email" class="form-control input-custom" name="mail"
                            placeholder="Adresse électronique" pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                            required>
                        <?php if (isset($erreur['mail'])): ?>
                            <div class="text-danger">
                                <?= $erreur['mail']; ?>
                            </div>
                            <?php
                        elseif (isset($erreur['doublon'])): ?>
                            <div class="text-danger">
                                <?= $erreur['doublon']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 p-2">
                        <input type="text" class="form-control input-custom" name="prenom" autocapitalize="words"
                            placeholder="Prénom(s)" pattern="^[A-Za-z\s]+$" required>
                        <?php if (isset($erreur['prenom'])): ?>
                            <div class="text-danger">
                                <?= $erreur['prenom']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 p-2">
                        <input type="text" class="form-control input-custom" name="nom" placeholder="Nom"
                            autocapitalize="words" pattern="^[A-Za-z\s]+$" required>
                        <?php if (isset($erreur['nom'])): ?>
                            <div class="text-danger">
                                <?= $erreur['nom']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 p-2">
                        <input type="text" class="form-control input-custom" name="username"
                            placeholder="Pseudo. (07 caractères)" pattern="^[A-Za-z0-9_]{7}$" required>
                        <?php if (isset($erreur['username'])): ?>
                            <div class="text-danger">
                                <?= $erreur['username']; ?>
                            </div>
                        <?php elseif (isset($erreur['doublon'])): ?>
                            <div class="text-danger">
                                <?= $erreur['doublon']; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 p-2">
                        <input type="password" class="form-control input-custom" name="mdp"
                            pattern="^[a-zA-Z0-9!@#$%^&*()-_+=]{8,12}$" placeholder="Mot de passe. (8-12 caractères)"
                            required>
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
                                placeholder="réponse?" pattern="^[A-Za-z0-9_]+$" required>
                            <?php if (isset($erreur['reponse'])): ?>
                                <div class="text-danger">
                                    <?= $erreur['reponse']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-2">
                <h3 class="text-center col-">PHOTO</h3>
                <div class="d-flex align-items-center justify-content-center">
                    <label for="pp">
                        <img src="files/profil4.png" alt="photo de profil" width="300" height="300">
                    </label>
                    <input type="file" id="pp" name="pp" style="display: none;" accept="image/*">
                </div>
                <?php if (isset($erreur['img_type'])): ?>
                    <div class="text-danger">
                        <?= $erreur['img_type']; ?>
                    </div>
                <?php elseif (isset($erreur['img_type'])): ?>
                    <div class="text-danger">
                        <?= $erreur['img_size']; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <br><br>
        <div class="form-check ">
            <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
            <label class="form-check-label" for="invalidCheck">
                Accepter les conditions d'utilisation
            </label>
        </div>

        <div class="form-group p-3">
            <button class="btn btn-primary " type=submit name="suscribe">S'inscrire</button>
        </div>
    </form>

    <?php
    if (empty($erreur)):

        $insert = "INSERT INTO users(mail, prenom, nom, username, mdp, question, reponse, profile_pic) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $sql = mysqli_prepare($co, $insert);

        if ($sql):
            mysqli_stmt_bind_param($sql, "sssssssb", $mail, $prenom, $nom, $username, $mdp, $question, $reponse, $profile_pic);

            if (mysqli_stmt_execute($sql)):
                header('location:success.php');
            else:
                header('location:failure.php');
                echo "Erreur lors de l'insertion des données : " . mysqli_stmt_error($sql);
            endif;

            mysqli_stmt_close($sql);

        else:
            echo "Erreur lors de la préparation de la requête : " . mysqli_error($co);
        endif;
    endif;
    require 'footer.php'; ?>