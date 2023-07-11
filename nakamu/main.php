<?php
require 'header.php';
require 'db.php';

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

function video_type($video)
{
    $info = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($info, $video);
    finfo_close($info);

    if ($mimeType && strpos($mimeType, 'video') === 0):
        return true;
    endif;

    return false;
}

function video_size($video, $max)
{
    $size = filesize($video);
    if ($size > $max):
        return false;
    endif;
    return true;
}


if ($_SERVER["REQUEST_METHOD"] == "POST"):
    if (isset($_POST) && !empty($_POST)):
        $erreur = array();
        $key = '/^[\p{L}\p{N}\p{S}\p{P}\p{Z}\x{1F300}-\x{1F5FF}\x{1F600}-\x{1F64F}\x{1F680}-\x{1F6FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{1F900}-\x{1F9FF}]+$/u';
        $msg_help = "Veuillez vous limiter aux caractères autorisés: alphabet (A à Z), chiffres, émojis ou certains caractères spéciaux ";

        $msg = check('msg', $key, $msg_help);

        if ($_FILES["photo"]["error"] === UPLOAD_ERR_OK):
            $img = $_FILES["photo"]["name"];
            $temp = $_FILES["photo"]["tmp_name"];
            if (!img_type($temp)):
                $erreur['photo_type'] = "type d'image non supporté";
                unlink($temp);
            endif;
            if (!img_size($temp, $max)):
                $erreur['photo_size'] = "limite de taille dépassée";
                unlink($temp);
            endif;
            $img = "media/" . $img;
            move_uploaded_file($temp, $img);
        endif;

        if ($_FILES["video"]["error"] === UPLOAD_ERR_OK):
            $video = $_FILES["video"]["name"];
            $temp = $_FILES["video"]["tmp_name"];
            if (!video_type($temp)):
                $erreur['video_type'] = "Type de vidéo non pris en charge";
                unlink($temp);
            endif;
            if (!video_size($temp, $max)):
                $erreur['video_size'] = "Limite de taille dépassée";
                unlink($temp);
            endif;
            $video = "media/" . $video;
            move_uploaded_file($temp, $video);
        endif;

    endif;


    $publisher = $_SESSION['id_user'];

    if (empty($erreur)):

        $publish = "INSERT INTO posts(msg, img, video, publisher) VALUES (?, ?, ?, ?, ?)";
        $sql = mysqli_prepare($co, $publish);

        if ($sql):
            mysqli_stmt_bind_param($sql, "sbbi", $msg, $img, $video, $publisher);

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
endif;
require 'navbar.php'; ?>


<?php
if ($_SESSION['connecte'] == true): ?>
    <div class="d-flex justify-content-center">
        <?php if (isset($_SESSION['prenom'])):
            echo "Bienvenue, " . $_SESSION['prenom'];
        endif; ?>
    </div>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto d-flex justify-content-center flex-column">
                    <h3 class="">Vous voulez partager...</h3>
                    <form role="form" method="post" autocomplete="off" accept-charset="utf-8">
                        <div class="card-body row">
                            <div class="col-sm-8 px-0 mx-0">
                                <textarea name="msg" class="form-control" id="message" rows="4"
                                    placeholder="écrivez quelque chose..."></textarea>
                                <?php if (isset($erreur) && !empty($erreur)):
                                    foreach ($erreur as $val): ?>
                                        <div class="text-danger">
                                            <?= $val; ?>
                                        </div>
                                    <?php endforeach; endif; ?>
                            </div>

                            <div class='col-sm-2'>
                                <ul class="px-0 mx-0">
                                    <li class="list-group-item">
                                        <label for="photo" class="input-icon-label">
                                            <i class="fa-regular fa-image fa-xl" title="image"></i>
                                        </label>
                                        <input type="file" id="photo" name="photo" style="display: none;" accept="image/*">
                                    </li>
                                    <li class="list-group-item">
                                        <label for="video" class="input-icon-label">
                                            <i class="fa-solid fa-film fa-xl" title="vidéo"></i>
                                        </label>
                                        <input type="file" id="video" name="video" style="display: none;" accept="video/*">
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-footer">

                            <div class="col-md-12 m-1 p-1">
                                <button type="submit" class="btn btn-secondary">Envoyer</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>


<?php else: ?>


    recuperer les donnees uploadées et afficher,
    options like ou commentaires possibles selon $_SESSION['connecte']


<?php endif;
require 'footer.php'; ?>