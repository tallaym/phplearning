<?php
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

function look($co, $username, $mdp)
{
    $sql = "SELECT username, mdp FROM users WHERE username=? AND mdp=?";
    $req = mysqli_prepare($co, $sql);

    if ($req):
        mysqli_stmt_bind_param($req, "ss", $username, $mdp);
        mysqli_stmt_execute($req);
        mysqli_stmt_store_result($req);

        if (mysqli_stmt_num_rows($req) > 0):
            return true;
        else:
            return false;
        endif;

    else:
        echo "Erreur lors de la préparation de la requête : " . mysqli_error($co);
    endif;
}




?>