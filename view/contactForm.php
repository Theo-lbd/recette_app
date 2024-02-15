<?php include './admin/view/header.php'; ?>

<main>
    <section>
        <h2>Contactez-nous</h2>

        <form action="index.php?action=submitContactForm" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <div>
                <label for="name">Nom :</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="subject">Sujet :</label>
                <select id="subject" name="subject">
                    <option value="report">Signaler un problème</option>
                    <option value="question">Question</option>
                </select>
            </div>
            <div>
                <label for="message">Message :</label>
                <textarea id="message" name="message" required></textarea>
            </div>
            <input type="submit" value="Envoyer">
        </form>
        <a href="index.php?action=showMessages">Mes messages</a>
    </section>
</main>

<?php include 'footer.php'; ?>
