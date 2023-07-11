<?php require 'header.php'; ?>

<div class="container">
    <form method="post">
        <div class="form-group">
            <input type="email" class="form-control" name="email" placeholder="Adresse électronique"
                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}" required>
        </div>
        <button class="btn btn-primary" name="toc" type="submit">Soumettre</button>
    </form>

    si le mail existe, afficher la question de recuperation
    puis un champ pour la réponse
    <form method="post">

        <div class="form-group">
            <input type="text-area" class="form-control" name="reponse" pattern="[A-Za-z\s]+" required>
        </div>
        <button class="btn btn-primary" name="portier" type="submit">Soumettre</button>
    </form>

</div>






<?php require 'footer.php'; ?>