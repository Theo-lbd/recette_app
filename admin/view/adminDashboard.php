<?php include "view/header.php"; ?>
<main>
    <section>
        <h1>Tableau de bord Administrateur</h1>
        <div class="admin-dashboard">
            <!-- Carte pour la gestion des utilisateurs -->
            <div class="card">
                <div class="card-icon">
                    <img src="./public/img/user.svg" alt="Icône utilisateurs">
                </div>
                <article class="card-content">
                    <h2>Gérer les utilisateurs</h2>
                    <p>Visualisez et gérez les utilisateurs inscrits sur votre site.</p>
                </article>
                <a href="index.php?action=manageUsers" class="card-btn">Voir plus</a>
            </div>

            <!-- Carte pour la gestion des recettes -->
            <div class="card">
                <div class="card-icon">
                    <img src="./public/img/food.svg" alt="Icône recettes">
                </div>
                <article class="card-content">
                    <h3>Gérer les Recettes</h3>
                    <p>Visualisez et gérez les recettes de tout le site.</p>
                </article>
                <a href="index.php?action=manageRecipes" class="card-btn">Voir plus</a>
            </div>

            <!-- Carte pour visualiser les messages -->
            <div class="card">
                <div class="card-icon">
                    <img src="./public/img/message.svg" alt="Icône messages">
                </div>
                <article class="card-content">
                    <h3>Voir les messages</h3>
                    <p>Retrouvez les messages des utilisateurs.</p>
                </article>
                <a href="index.php?action=showAllMessages" class="card-btn">Voir plus</a>
            </div>
        </div>
    </section>
</main>
<?php include "../view/footer.php";?>
