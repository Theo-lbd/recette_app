<?php include 'header.php'; ?>

<main>
    <section>
        <h2>Inscription</h2>

        <form method="post" action="index.php?controller=user&action=register">
            <div>
                <label for="firstname">Prénom :</label>
                <input type="text" name="firstname" id="firstname" required>
            </div>
            <div>
                <label for="lastname">Nom :</label>
                <input type="text" name="lastname" id="lastname" required>
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required pattern=".{6,}" title="6 caractères minimum">
            </div>
            <div>
                <label for="confirm_password">Confirmer le mot de passe :</label>
                <input type="password" name="confirm_password" id="confirm_password" required pattern=".{6,}" title="6 caractères minimum">
            </div>
            <input type="submit" value="S'inscrire">
        </form>

        <p>Déjà un compte ? <a href="index.php?action=login">Connectez-vous ici</a>.</p>
    </section>
</main>

<?php include 'footer.php'; ?>
