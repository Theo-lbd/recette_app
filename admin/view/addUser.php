<?php include "view/header.php";?>

<main>
    <section>
        <h1>Ajouter un nouvel utilisateur</h1>
        <!-- Formulaire pour ajouter un nouvel utilisateur -->
        <form action="index.php?action=addUser" method="post">
            <div>
                <label for="firstname">Prénom :</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div>
                <label for="lastname">Nom :</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit">Ajouter</button>
        </form>
    </section>
</main>

<?php include "view/footer.php";?>
