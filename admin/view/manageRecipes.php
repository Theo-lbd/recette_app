<?php include "view/header.php"; ?>
<main>
    <section>
        <h1>Gérer les recettes</h1>
        <?php include_once '../functions/functions.php';?>
        <div class="admin-dashboard">
            <!-- Boucle pour afficher chaque recette -->
            <?php foreach ($recipes as $recette): ?>
                <article class="recette-card" data-category="<?php echo htmlspecialchars($recette['category']); ?>">
                    <header class="card-header">
                        <h2><?php echo htmlspecialchars($recette['nom']); ?></h2>
                    </header>
                    <div class="card-body">
                        <img src="/public/uploads/<?php echo basename(htmlspecialchars($recette['image_path'])); ?>" alt="Image de <?php echo htmlspecialchars($recette['nom']); ?>" class="card-avatar">
                        <div class="card-time">
                            <time><?php echo htmlspecialchars((string) $recette['prep_time']); ?> minutes</time>
                            <span><?php echo htmlspecialchars((string) $recette['serving_size']); ?> portions</span>
                        </div>
                        <p><strong>Ingrédients:</strong></p>
                        <ul><?php renderIngredients($recette['ingredients']); ?></ul>
                        <p><strong>Instructions:</strong></p>
                        <p><?php echo nl2br(htmlspecialchars($recette['instructions'])); ?></p>
                    </div>
                    <footer class="card-footer">
                        <a href="index.php?action=editRecipe&id=<?= $recette['id']; ?>">Modifier</a>
                        <a href="index.php?action=deleteRecipe&id=<?= $recette['id']; ?>" onclick="return confirm('Êtes-vous sûr?');">Supprimer</a>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
<?php include "view/footer.php"; ?>