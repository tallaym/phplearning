<?php require 'header.php'; ?>

<p class="text-center h6">Erreur, veuillez réessayer. Vous serez redirigé vers la page principale d'ici peu...</p>

<?php $delai = 5;
header("Refresh: $delai; url=main.php");
?>