<?php include "view/header.php"; ?>
<main>
    <section>
        <h1>Tous les utilisateurs</h1>
        <a class="button-a" href="index.php?action=addUser">Ajouter un utilisateur</a>
        <!-- Boucle pour afficher chaque utilisateur -->
        <div class="admin-dashboard">
            <?php foreach ($users as $user): ?>
                <article class="card user-card">
                    <header class="card-icon">
                        <img src="./public/img/user.svg" alt="Icône utilisateur">
                    </header>
                    <div class="card-content">
                        <h2><?php echo htmlspecialchars($user['firstname']) . ' ' . htmlspecialchars($user['lastname']); ?></h2>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <footer class="card-footer user-card-footer">
                        <a href="index.php?action=editUser&id=<?php echo $user['id']; ?>" class="card-btn">Modifier</a>
                        <a href="index.php?action=deleteUser&id=<?php echo $user['id']; ?>" class="card-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php include "view/footer.php"; ?>
