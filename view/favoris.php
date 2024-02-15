<?php include './admin/view/header.php'; ?>

<main>
    <section>
        <h2>Mes recettes favorites</h2>

        <div class="admin-dashboard">
            <?php foreach ($recettesFavoris as $recette): ?>
                <?php include 'recipeCard.php';?>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<?php include 'footer.php'; ?>
