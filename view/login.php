<?php include './admin/view/header.php'; ?>

<main>
    <section>
        <h2>Connexion</h2>

        <?php if (isset($errors) && !empty($errors)): ?>
            <ul class="errors">
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <form method="post" action="index.php?action=login">
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Connexion">
        </form>

        <p>Pas encore de compte ? <a class="button-a" href="index.php?action=register">Inscrivez-vous ici</a>.</p>
    </section>
</main>

<?php include 'footer.php'; ?>
